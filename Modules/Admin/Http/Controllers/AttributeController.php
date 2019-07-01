<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\Attribute;
use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/**
 * Class AttributeController
 * @package Modules\Admin\Http\Controllers
 */
class AttributeController extends BaseController
{
    /**
     * Default sort field.
     *
     * @var string
     */
    protected $sortBy = 'title';

    /**
     * Sortable fields.
     *
     * @var array
     */
    protected $sortableFields = ['id', 'title'];

    /**
     * Display a categories list.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        /** @var Builder $query */
        $query = Attribute::joinTranslate();
        if ($request->has('id') && $request->get('id') !== null) {
            $query->where('id', 'LIKE', '%' . $request->get('id') . '%');
        }
        if ($request->has('type') && $request->get('type') !== null) {
            $query->where('type', $request->get('type'));
        }
        if ($request->has('title') && $request->get('title') !== null) {
            $query->whereTranslationLike('title', '%' . $request->get('title') . '%');
        }
        if ($request->has('category_id') && $request->get('category_id') !== null) {
            $query->category($request->get('category_id'));
        }

        $data = $query->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->itemsPerPage)
            ->appends($request->all());

        return view('admin::attribute.index', [
            'data' => $data,
            'pagination' => self::calculatePagination($data),
            'categories' => Category::getTree(),
            'types' => Attribute::getTypes()
        ]);
    }

    /**
     * Create category form
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin::attribute.create', [
            'categories' => Category::getTree(),
            'locales' => config('translatable.locales'),
            'types' => Attribute::getTypes(),
            'list_type' => Attribute::TYPE_LIST
        ]);
    }

    /**
     * Create attribute.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $locales = config('translatable.locales');
            $rules = [
                'category_id' => ['required', 'array'],
                'category_id.*' => ['required', 'exists:categories,id'],
                'type' => ['required', Rule::in(array_keys(Attribute::getTypes()))],
                'variations' => ['required_if:type,' . Attribute::TYPE_LIST]
            ];

            foreach ($locales as $locale) {
                $rules["title:$locale"] = ['required', 'min:2'];
                $rules["variations.*.title:$locale"] = ['required_if:type,' . Attribute::TYPE_LIST];
            }

            // Validate request, if fails -> redirect to the form with errors.
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $old = $this->setMissingData($request->all());
                return redirect()
                    ->back()
                    ->withInput($old)
                    ->withErrors($validator);
            }

            // Create an attribute.
            DB::transaction(function () use ($request) {
                /** @var Attribute $attribute */
                $attribute = Attribute::create($request->all());

                // Assign categories to attribute.
                $attribute->categories()->syncWithoutDetaching($request->get('category_id'));

                // If type == list => create attribute variations.
                if ($request->get('type') == Attribute::TYPE_LIST) {
                    $variations = $request->get('variations');
                    foreach ($variations as $variation) {
                        $data = array_merge(['attribute_id' => $attribute->id], $variation);
                        $attribute->variations()->create($data);
                    }
                }

                $request->session()->flash('success', trans('admin::attribute.flash.created', [
                    'attribute' => $attribute->title
                ]));
            });
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/attributes'));
    }

    /**
     * Edit attribute form.
     *
     * @param Attribute Attribute
     * @return View
     */
    public function edit(Attribute $attribute): View
    {
        $locales = config('translatable.locales');
        $selectedVariations = [];
        foreach ($attribute->variations as $variation) {
            foreach ($locales as $locale) {
                $selectedVariations[$variation->id]["title:$locale"] = $variation->{"title:$locale"};
            }
        }
        return view('admin::attribute.edit', [
            'model' => $attribute,
            'selectedCategories' => $attribute->categories->pluck('id')->toArray(),
            'selectedVariations' => $selectedVariations,
            'categories' => Category::getTree(),
            'locales' => $locales,
            'types' => Attribute::getTypes(),
            'list_type' => Attribute::TYPE_LIST
        ]);
    }

    /**
     * Update attribute.
     *
     * @param Request $request
     * @param Attribute $attribute
     * @return RedirectResponse
     */
    public function update(Request $request, Attribute $attribute): RedirectResponse
    {
        try {
            $locales = config('translatable.locales');
            $rules = [
                'category_id' => ['required', 'array'],
                'category_id.*' => ['required', 'exists:categories,id'],
                'type' => ['required', Rule::in(array_keys(Attribute::getTypes()))],
                'variations' => ['required_if:type,' . Attribute::TYPE_LIST]
            ];

            foreach ($locales as $locale) {
                $rules["title:$locale"] = ['required', 'min:2'];
                $rules["variations.*.title:$locale"] = ['required_if:type,' . Attribute::TYPE_LIST];
            }

            // Validate request, if fails -> redirect to the form with errors.
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $old = $this->setMissingData($request->all());
                return redirect()
                    ->back()
                    ->withInput($old)
                    ->withErrors($validator);
            }

            // Update an attribute.
            DB::transaction(function () use ($request, $attribute) {
                /** @var Attribute $attribute */
                $attribute->update($request->all());

                // Sync attribute categories.
                $attribute->categories()->sync($request->get('category_id'));

                // If type == list => sync attribute variations, else delete attribute variations.
                if ($request->get('type') == Attribute::TYPE_LIST) {
                    $variations = $request->get('variations');
                    $currentVariations = $attribute->variations->pluck('id')->toArray();
                    $selectedVariations = array_keys($variations);
                    // Delete needed variations.
                    $deleteVariations = array_diff($currentVariations, $selectedVariations);
                    if ($deleteVariations) {
                        $attribute->variations()->whereIn('id', $deleteVariations)->delete();
                    }
                    // Create or update variations.
                    foreach ($variations as $id => $attributes) {
                        $attribute->variations()->updateOrCreate(['id' => $id], $attributes);
                    }
                } else {
                    // Delete all variations.
                    $attribute->variations()->delete();
                }

                $request->session()->flash('success', trans('admin::attribute.flash.updated', [
                    'attribute' => $attribute->title
                ]));
            });
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/attributes'));
    }

    /**
     * Delete attribute.
     *
     * @param Request $request
     * @param Attribute $attribute
     * @return RedirectResponse
     */
    public function destroy(Request $request, Attribute $attribute): RedirectResponse
    {
        try {
            $title = $attribute->title;

            $attribute->delete();

            $request->session()->flash('success', trans('admin::attribute.flash.deleted', [
                'attribute' => $title
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/attributes'));
    }

    /**
     * Set missing request data
     *
     * @param $data array
     *
     * @return array
     */
    private function setMissingData(array $data): array
    {
        if (!isset($data['category_id'])) {
            $data['category_id'] = [];
        }
        if ($data['type'] == Attribute::TYPE_LIST && !isset($data['variations'])) {
            $data['variations'] = [];
        }

        return $data;
    }
}
