<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Helper;
use App\Models\Location;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Class LocationController
 * @package Modules\Admin\Http\Controllers
 */
class LocationController extends BaseController
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
     * Display a location list.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        /** @var Builder $query */
        $query = Location::joinTranslate();
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

        $data = $query->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->itemsPerPage)
            ->appends($request->all());

        return view('admin::location.index', [
            'data' => $data,
            'pagination' => self::calculatePagination($data),
            'locality_type' => Location::TYPE_LOCALITY
        ]);
    }

    /**
     * Create location form
     *
     * @return View
     */
    public function create(): View
    {
        $selectedType = Location::TYPE_REGION;
        if (!empty($_GET['parent_id'])) {
            $location = Location::findOrFail($_GET['parent_id']);
            if ($location->type == Location::TYPE_REGION) {
                $selectedType = Location::TYPE_DISTRICT;
            } elseif ($location->type == Location::TYPE_DISTRICT) {
                $selectedType = Location::TYPE_LOCALITY;
            }
        }

        return view('admin::location.create', [
            'locations' => Location::getRegionsAndDistricts(),
            'types' => Location::getTypes(),
            'locales' => config('translatable.locales'),
            'selectedType' => $selectedType
        ]);
    }

    /**
     * Create location.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $locales = config('translatable.locales');
            $rules = [
                'parent_id' => ['nullable', 'numeric'],
                'type' => ['required', Rule::in(array_keys(Location::getTypes()))],
            ];
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

            // Create a location.
            $location = Location::create($request->all());
            $request->session()->flash('success', trans('admin::location.flash.created', [
                'location' => $location->title
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(Helper::generateTranslatableUrl('/locations', 'parent_id'));
    }

    /**
     * Edit location form.
     *
     * @param Location $location
     * @return View
     */
    public function edit(Location $location): View
    {
        return view('admin::location.edit', [
            'model' => $location,
            'locations' => Location::getRegionsAndDistricts(),
            'types' => Location::getTypes(),
            'locales' => config('translatable.locales')
        ]);
    }

    /**
     * Update location.
     *
     * @param Request $request
     * @param Location $location
     * @return RedirectResponse
     */
    public function update(Request $request, Location $location): RedirectResponse
    {
        try {
            $locales = config('translatable.locales');
            $rules = [
                'parent_id' => ['nullable', 'numeric'],
                'type' => ['required', Rule::in(array_keys(Location::getTypes()))],
            ];
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

            // Update a location.
            $location->update($request->all());
            $request->session()->flash('success', trans('admin::location.flash.updated', [
                'location' => $location->title
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(Helper::generateTranslatableUrl('/locations', 'parent_id'));
    }

    /**
     * Delete location.
     *
     * @param Request $request
     * @param Location $location
     * @return RedirectResponse
     */
    public function destroy(Request $request, Location $location): RedirectResponse
    {
        try {
            $title = $location->title;

            // ToDo: Check if exist entities (users, etc.) that belong to location
            $location->delete();
            $request->session()->flash('success', trans('admin::location.flash.deleted', [
                'location' => $title
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(Helper::generateTranslatableUrl('/locations', 'parent_id'));
    }
}
