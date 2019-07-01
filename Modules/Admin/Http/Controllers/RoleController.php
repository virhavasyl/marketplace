<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\Role;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/**
 * Class RoleController
 * @package Modules\Admin\Http\Controllers
 */
class RoleController extends BaseController
{
    /**
     * Sortable fields.
     *
     * @var array
     */
    protected $sortableFields = ['id', 'title'];

    /**
     * Display a roles list.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        /** @var Builder $query */
        $query = Role::withCount('users');
        if ($request->has('id') && $request->get('id') !== null) {
            $query->where('id', 'LIKE', '%' . $request->get('id') . '%');
        }

        if ($request->has('title') && $request->get('title') !== null) {
            $query->where('title', 'LIKE', '%' . $request->get('title') . '%');
        }

        $data = $query->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->itemsPerPage)
            ->appends($request->all());

        return view('admin::role.index', [
            'data' => $data,
            'pagination' => self::calculatePagination($data),
        ]);
    }

    /**
     * Create role form.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin::role.create');
    }

    /**
     * Create role.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validate request, if fails -> redirect to the form with errors.
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:roles|min:2',
            ]);
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withInput($request->all())
                    ->withErrors($validator);
            }

            // Create a role.
            $role = Role::create([
                'title' => $request->get('title')
            ]);
            $request->session()->flash('success', trans('admin::role.flash.created', [
                'role' => $role->title
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/roles'));
    }

    /**
     * Edit role form.
     *
     * @param Role $role
     * @return View
     */
    public function edit(Role $role): View
    {
        return view('admin::role.edit', [
            'model' => $role
        ]);
    }

    /**
     * Update role
     *
     * @param Request $request
     * @param Role $role
     * @return RedirectResponse
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        try {
            // Validate request, if fails -> redirect to the form with errors.
            $validator = Validator::make($request->all(), [
                'title' => "required|unique:roles,title,{$role->id}|min:2",
            ]);
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withInput($request->all())
                    ->withErrors($validator);
            }

            // Update a role.
            $role->update($request->only('title'));
            $request->session()->flash('success', trans('admin::role.flash.updated', [
                'role' => $role->title
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/roles'));
    }

    /**
     * Delete role
     *
     * @param Request $request
     * @param Role $role
     * @return RedirectResponse
     */
    public function destroy(Request $request, Role $role): RedirectResponse
    {
        try {
            $title = $role->title;
            // If role contains users - don't delete.
            if ($role->users_count) {
                throw new Exception('Trying to delete role with existing users.');
            } else {
                $role->delete();
                $request->session()->flash('success', trans('admin::role.flash.deleted', [
                    'role' => $title
                ]));
            }
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/roles'));
    }
}
