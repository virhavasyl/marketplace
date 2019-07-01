<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Validation\Rule;

/**
 * Class SettingController
 *
 * @package Modules\Admin\Http\Controllers
 */
class SettingController extends BaseController
{
    /**
     * Sortable fields.
     *
     * @var array
     */
    protected $sortableFields = ['id', 'category_id', 'key', 'value'];

    /**
     * Display a settings list.
     * @return View
     */
    public function index(Request $request): View
    {
        
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = Setting::query();
        if ($request->has('id') && $request->get('id') !== null) {
            $query->where('id', 'LIKE', '%' . $request->get('id') . '%');
        }
        if ($request->has('category_id') && $request->get('category_id') !== null) {
            $query->where('category_id', $request->get('category_id'));
        }
        if ($request->has('key') && $request->get('key') !== null) {
            $query->where('key', 'LIKE', '%' . $request->get('key') . '%');
        }

        if ($request->has('value') && $request->get('value') !== null) {
            $query->where('value', 'LIKE', '%' . $request->get('value') . '%');
        }

        $data = $query->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->itemsPerPage)
            ->appends($request->all());

        return view('admin::setting.index', [
            'data' => $data,
            'pagination' => self::calculatePagination($data),
            'categories' => Setting::getCategories()
        ]);
    }

    /**
     * Create setting form.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin::setting.create', [
            'categories' => Setting::getCategories()
        ]);
    }

    /**
     * Create setting.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validate request, if fails -> redirect to the form with errors.
            $validator = Validator::make($request->all(), [
                'category_id' => ['required', 'numeric', Rule::in(array_keys(Setting::getCategories()))],
                'key' => 'required|max:255|unique:settings|regex:/^\w+$/',
                'value' => 'required|max:255'
            ]);
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withInput($request->all())
                    ->withErrors($validator);
            }

            // Create a setting.
            $setting = Setting::create($request->all());
            $request->session()->flash('success', trans('admin::setting.flash.created', [
                'setting' => $setting->key
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/settings'));
    }

    /**
     * Edit setting form.
     *
     * @param Setting $setting
     * @return View
     */
    public function edit(Setting $setting): View
    {
        return view('admin::setting.edit', [
            'model' => $setting,
            'categories' => Setting::getCategories()
        ]);
    }

    /**
     * Update setting
     *
     * @param Request $request
     * @param Setting $setting
     * @return RedirectResponse
     */
    public function update(Request $request, Setting $setting): RedirectResponse
    {
        try {
            // Validate request, if fails -> redirect to the form with errors.
            $validator = Validator::make($request->all(), [
                'category_id' => ['required', 'numeric', Rule::in(array_keys(Setting::getCategories()))],
                'key' => "required|max:255|unique:settings,key,{$setting->id}|regex:/^\w+$/",
                'value' => 'required|max:255'
            ]);
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withInput($request->all())
                    ->withErrors($validator);
            }

            // Update a setting.
            $setting->update($request->all());
            $request->session()->flash('success', trans('admin::setting.flash.updated', [
                'setting' => $setting->key
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/settings'));
    }

    
     /**
     * Delete setting
     *
     * @param Request $request
     * @param Setting $setting
     * @return RedirectResponse
     */
    public function destroy(Request $request, Setting $setting): RedirectResponse
    {
        try {
            $setting->delete();
            $request->session()->flash('success', trans('admin::setting.flash.deleted', [
                'setting' => $setting->key
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/settings'));
    }
}
