<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\File;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductController
 * @package Modules\Admin\Http\Controllers
 */
class ProductController extends BaseController
{
    /**
     * Default sort field.
     *
     * @var string
     */
    protected $sortBy = 'updated_at';

    /**
     * Default sort direction.
     *
     * @var string
     */
    protected $sortDir = 'desc';

     /**
     * Sortable fields.
     *
     * @var array
     */
    protected $sortableFields = ['id', 'title', 'fullname', 'category', 'price', 'status', 'condition'];

    /**
     * Display a listing of the resource.
     * @return View
     */
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'user']);
        if ($request->has('id') && $request->get('id') !== null) {
            $query->where('id', 'LIKE', '%' . $request->get('id') . '%');
        }
        if ($request->has('title') && $request->get('title') !== null) {
            $query->where('title', 'LIKE', '%' . $request->get('title') . '%');
        }
        if ($request->has('category_id') && $request->get('category_id') !== null) {
            $query->where('category_id', 'LIKE', '%' . $request->get('category_id') . '%');
        }
        if ($request->has('fullname') && $request->get('fullname') !== null) {
            $fullname = $request->get('fullname');
            $query->whereHas('user', function ($q) use ($fullname) {
                $q->whereRaw("CONCAT_NULL(firstname, ' ', lastname) LIKE '%$fullname%'");
            });
        }
        if ($request->has('price') && $request->get('price') !== null) {
            $query->where('price', $request->get('price'));
        }
        if ($request->has('status') && $request->get('status') !== null) {
            $query->where('status', $request->get('status'));
        }
        if ($request->has('condition') && $request->get('condition') !== null) {
            $query->where('condition', $request->get('condition'));
        }

        // Order by fullname
        if ($this->sortBy == 'fullname') {
            $query->join('users', 'products.user_id', '=', 'users.id');
            $query->select('products.*');
            $query->orderByRaw("CONCAT_NULL(firstname, ' ', lastname) " . $this->sortDir);
        } elseif ($this->sortBy == 'category') {
            // Order by category title
            $query->join('category_translations as t', function ($join) {
                $join->on('products.category_id', '=', 't.category_id')
                    ->where("t.locale", config('app.locale'));
            });
            $query->select('products.*');
            $query->orderBy('t.title', $this->sortDir);
        } else {
            // Standard order
            $query->orderBy($this->sortBy, $this->sortDir);
        }

        $products = $query->paginate($this->itemsPerPage)
            ->appends($request->all());
            
        return view('admin::product.index', [
            'data' => $products,
            'pagination' => self::calculatePagination($products),
            'statuses' => Product::getStatuses(),
            'categories' => Category::getTree(),
            'conditions' => Product::getConditions(),
        ]);
    }

    /**
     * Create product form.
     *
     * @return View
     */
    public function create(): View
    {
        $data = [
            'categories' => Category::structureByLevel(),
            'currencies' => Currency::all(),
            'status_active' => Product::STATUS_ACTIVE,
            'conditions' => Product::getConditions(),
            'selected_user' => null
        ];

        if (old('category_id')) {
            /** @var Category $category */
            $category = Category::find(old('category_id'));
            $data['all_attributes'] = $category ? $category->getAllAttributes() : [];
            $data['selected_attributes'] = [];
        }

        $userId = old('user_id');
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $data['selected_user'] =  $user;
            }
        }

        return view('admin::product.create', $data);
    }

    /**
     * Create product.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validate request, if fails -> redirect to the form with errors.
            $validator =Validator::make($request->all(), [
                'title' => 'required',
                'category_id' => 'required|exists:categories,id',
                'user_id' => 'required|exists:users,id',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable',
                'files' => 'base64image',
                'condition' => [
                    'required',
                    'numeric',
                    Rule::in(array_keys(Product::getConditions()))
                ]
            ]);

            if ($validator->fails()) {
                $old = $request->all();
                if (isset($old['files'])) {
                    $request->session()->put('product_images_0', $old['files']);
                    $old['_id'] = 0;
                } else {
                    $request->session()->put('product_images_0', []);
                }

                return redirect()
                    ->back()
                    ->withInput($old)
                    ->withErrors($validator);
            }

            // Create an product.
            DB::transaction(function () use ($request) {
                /** @var Product $product */
                $product = Product::create($request->all());
                $attributes = $request->get('attributes');
                if ($attributes) {
                    foreach ($attributes as $key => $attribute) {
                        if ($attribute) {
                            $product->attributes()->attach($key, ['value' => $attribute]);
                        }
                    };
                }

                $images = $request->get('files');
                if ($images) {
                    foreach ($images as $imageBase64) {
                        $path = File::generateDirPath(PRODUCT_IMAGE_PATH);
                        $path = File::storeBase64Image($imageBase64, $path);

                        $product->images()->create([
                            'path' => $path,
                        ]);
                    };
                }

                $request->session()->forget('product_images_0');

                $request->session()->flash('success', trans('admin::product.flash.created', [
                    'title' => $product->title
                ]));
            });

            return redirect(LaravelLocalization::getLocalizedURL(null, '/products'));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);

            return redirect()
                ->back()
                ->withInput($request->all());
        }
    }

    /**
     * Edit product form.
     *
     * @param Product $product
     * @return View
     */
    public function edit(Product $product): View
    {
        $all_attributes = $product->category ? $product->category->getAllAttributes() : [];
        $is_repeat = old('attributes') ? true : false;

        $data = [
            'model' => $product,
            'categories' => Category::structureByLevel(),
            'currencies' => Currency::all(),
            'status_active' => Product::STATUS_ACTIVE,
            'conditions' => Product::getConditions(),
            'selected_user' => null,
            'all_attributes' => $all_attributes,
            'selected_attributes' => $product->makeAttributeVariationArray($is_repeat)
        ];

        $userId = old('user_id', $product->user_id);
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $data['selected_user'] =  $user;
            }
        }

        return view('admin::product.edit', $data);
    }

    /**
     * Update product
     *
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        try {
            // Validate request, if fails -> redirect to the form with errors.
            $validator =Validator::make($request->all(), [
                'title' => 'required',
                'category_id' => 'required|exists:categories,id',
                'user_id' => 'required|exists:users,id',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable',
                'files' => 'base64image',
                'condition' => [
                    'required',
                    'numeric',
                    Rule::in(array_keys(Product::getConditions()))
                ]
            ]);

            if ($validator->fails()) {
                $old = $request->all();
                if (isset($old['files'])) {
                    $request->session()->put('product_images_' . $product->id, $old['files']);
                } else {
                    $request->session()->put('product_images_' . $product->id, []);
                }

                return redirect()
                  ->back()
                  ->withInput($old)
                  ->withErrors($validator);
            }

            DB::transaction(function () use ($request, $product) {
                $product->update($request->all());
                if (!$request->status) {
                    $product->update([
                      'status' => Product::STATUS_INACTIVE
                    ]);
                }

                $sync = [];
                $attributes = $request->get('attributes');
                if ($attributes) {
                    foreach ($attributes as $key => $attribute) {
                        if ($attribute) {
                            $sync[$key] = ['value' => $attribute];
                        }
                    }
                }
                $product->attributes()->sync($sync);

                $images = $product->images;
                $product->images()->delete();
                $new_images = $request->get('files');

                if ($new_images) {
                    foreach ($new_images as $imageBase64) {
                        $path = File::generateDirPath(PRODUCT_IMAGE_PATH);
                        $path = File::storeBase64Image($imageBase64, $path);

                        $product->images()->create([
                            'path' => $path,
                        ]);
                    };
                }


                foreach ($images as $image) {
                    Storage::delete($image->path);
                }

                $request->session()->forget('product_images_' . $product->id);

                $request->session()->flash('success', trans('admin::product.flash.updated', [
                        'title' => $product->title
                    ]));
            });
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/products'));
    }

    /**
     * Delete product
     *
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Request $request, Product $product): RedirectResponse
    {
        try {
            $title = $product->title;
            $images = $product->images;

            if ($product->delete()) {
                foreach ($images as $image) {
                    Storage::disk('public')->delete($image->path);
                }
                $request->session()->flash('success', trans('admin::product.flash.deleted', [
                    'title' => $title
                ]));
            } else {
                $request->session()->flash('error', trans('admin::flash.internal_error'));
            }
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/products'));
    }

    /**
     * Activate product
     *
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function activate(Request $request, Product $product): RedirectResponse
    {
        try {
            $product->update([
                'status' => Product::STATUS_ACTIVE
            ]);

            $request->session()->flash('success', trans('admin::product.flash.activated', [
                'title' => $product->title
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/products'));
    }

    /**
     * Block product
     *
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function block(Request $request, Product $product): RedirectResponse
    {
        try {
            $product->update([
                'status' => Product::STATUS_INACTIVE
            ]);

            $request->session()->flash('success', trans('admin::product.flash.blocked', [
                'title' => $product->title
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/products'));
    }

    /**
     * Get product images from session or database.
     *
     * @param $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function images(Request $request, $id): JsonResponse
    {
        $data = [];
        if ($request->session()->has('product_images_' . $id)) {
            $files = $request->session()->get('product_images_' . $id);
            foreach ($files as $file) {
                $data[] = [
                    'name' => str_random() . File::getFileExtensionFromBase64($file),
                    'size' => File::getFileSizeFromBase64($file),
                    'dataURL' => $file
                ];
            }
        } else {
            $images = ProductImage::where('product_id', $id)->get();
            foreach ($images as $image) {
                $fullPath = Storage::url($image->path);
                $base64 = File::encodeImageToBase64($fullPath);
                $data[] = [
                    'name' => basename($fullPath),
                    'size' => Storage::size($image->path),
                    'dataURL' => $base64
                ];
            }
        }

        return response()->json($data);
    }
}
