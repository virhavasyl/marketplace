<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Helper;
use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * Class CategoryController
 * @package Modules\Admin\Http\Controllers
 */
class CategoryController extends BaseController
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
    protected $sortableFields = ['id', 'title', 'description'];

    /**
     * Display a categories list.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        /** @var Builder $query */
        $query = Category::joinTranslate();
        if (empty($request->get('global'))) {
            if (!empty($request->get('parent_id'))) {
                $query->where('parent_id', $request->get('parent_id'));
            } else {
                $query->root();
            }
        }
        if ($request->has('id') && $request->get('id') !== null) {
            $query->where('id', 'LIKE', '%' . $request->get('id') . '%');
        }
        if ($request->has('title') && $request->get('title') !== null) {
            $query->whereTranslationLike('title', '%' . $request->get('title') . '%');
        }
        if ($request->has('description') && $request->get('description') !== null) {
            $query->whereTranslationLike('description', '%' . $request->get('description') . '%');
        }

        $data = $query->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->itemsPerPage)
            ->appends($request->all());

        return view('admin::category.index', [
            'data' => $data,
            'pagination' => self::calculatePagination($data),
        ]);
    }

    /**
     * Create category form
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin::category.create', [
            'categories' => Category::getTree(),
            'locales' => config('translatable.locales'),
        ]);
    }

    /**
     * Create category.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $locales = config('translatable.locales');
            $rules['parent_id'] = ['nullable', 'numeric'];

            foreach ($locales as $locale) {
                $rules["title:$locale"] = ['required', 'min:2'];
            }

            // Validate request, if fails -> redirect to the form with errors.
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withInput($request->all())
                    ->withErrors($validator);
            }

            // Create a category.
            $category = Category::create($request->all());
            $request->session()->flash('success', trans('admin::category.flash.created', [
                'category' => $category->title
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(Helper::generateTranslatableUrl('/categories', 'parent_id'));
    }

    /**
     * Edit category form.
     *
     * @param Category $category
     * @return View
     */
    public function edit(Category $category): View
    {
        return view('admin::category.edit', [
            'model' => $category,
            'categories' => Category::getTree(),
            'locales' => config('translatable.locales')
        ]);
    }

    /**
     * Update category.
     *
     * @param Request $request
     * @param Category $category
     * @return RedirectResponse
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        try {
            $locales = config('translatable.locales');
            $rules['parent_id'] = ['nullable', 'numeric'];

            foreach ($locales as $locale) {
                $rules["title:$locale"] = ['required', 'min:2'];
            }

            // Validate request, if fails -> redirect to the form with errors.
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withInput($request->all())
                    ->withErrors($validator);
            }

            // Update a category.
            $category->update($request->all());
            $request->session()->flash('success', trans('admin::category.flash.updated', [
                'category' => $category->title
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(Helper::generateTranslatableUrl('/categories', 'parent_id'));
    }

    /**
     * Delete category.
     *
     * @param Request $request
     * @param Category $category
     * @return RedirectResponse
     */
    public function destroy(Request $request, Category $category): RedirectResponse
    {
        try {
            $title = $category->title;

            if ($category->getProductCount()) {
                throw new Exception('Trying to delete category with existing products.');
            } else {
                $category->delete();
                $request->session()->flash('success', trans('admin::category.flash.deleted', [
                    'category' => $title
                ]));
            }
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(Helper::generateTranslatableUrl('/categories', 'parent_id'));
    }

    /**
     * Attributes by category form.
     *
     * @param Request $request
     * @param Category $category
     *
     * @return View
     */
    public function attributes(Request $request, Category $category): View
    {
        $attributes = $category->getAllAttributes();

        return view('admin::category.includes._attributes', [
            'all_attributes' => $attributes,
            'selected_attributes' => []
        ]);
    }
}
