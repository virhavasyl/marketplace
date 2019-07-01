<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Currency;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/**
 * Class CurrencyController
 * @package Modules\Admin\Http\Controllers
 */
class CurrencyController extends BaseController
{
    /**
     * Sortable fields.
     *
     * @var array
     */
    protected $sortableFields = ['id', 'iso_code'];

    /**
     * Display a currency list.
     * @return View
     */
    public function index(Request $request): View
    {
        
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = Currency::query();
        if ($request->has('id') && $request->get('id') !== null) {
            $query->where('id', 'LIKE', '%' . $request->get('id') . '%');
        }

        if ($request->has('iso_code') && $request->get('iso_code') !== null) {
            $query->where('iso_code', 'LIKE', '%' . $request->get('iso_code') . '%');
        }

        $data = $query->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->itemsPerPage)
            ->appends($request->all());

        return view('admin::currency.index', [
            'data' => $data,
            'pagination' => self::calculatePagination($data),
        ]);
    }

    /**
     * Create currency form.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin::currency.create');
    }
    
    /**
     * Create currency.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validate request, if fails -> redirect to the form with errors.
            $validator = Validator::make($request->all(), [
                'iso_code' => 'required|max:3|unique:currencies',
                'sign' => 'required|max:1'
            ]);
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withInput($request->all())
                    ->withErrors($validator);
            }

            // Create a currency.
            $currency = Currency::create($request->all());
            $request->session()->flash('success', trans('admin::currency.flash.created', [
                'currency' => $currency->iso_code
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/currencies'));
    }
    
    /**
     * Edit currency form.
     *
     * @param Currency $currency
     * @return View
     */
    public function edit(Currency $currency): View
    {
        return view('admin::currency.edit', [
            'model' => $currency
        ]);
    }

    /**
     * Update currency
     *
     * @param Request $request
     * @param Currency $currency
     * @return RedirectResponse
     */
    public function update(Request $request, Currency $currency): RedirectResponse
    {
        try {
            // Validate request, if fails -> redirect to the form with errors.
            $validator = Validator::make($request->all(), [
                'iso_code' => "required|max:3|unique:currencies,iso_code,{$currency->id}",
                'sign' => 'required|max:1'
            ]);
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withInput($request->all())
                    ->withErrors($validator);
            }

            // Update a currency.
            $currency->update($request->all());
            $request->session()->flash('success', trans('admin::currency.flash.updated', [
                'currency' => $currency->iso_code
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/currencies'));
    }

     /**
     * Delete currency
     *
     * @param Request $request
     * @param Currency $currency
     * @return RedirectResponse
     */
    public function destroy(Request $request, Currency $currency): RedirectResponse
    {
        try {
            $currency->delete();
            $request->session()->flash('success', trans('admin::currency.flash.deleted', [
                'currency' => $currency->iso_code
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/currencies'));
    }
}
