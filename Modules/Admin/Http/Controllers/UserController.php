<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\File;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Validation\Rule;

/**
 * Class UserController
 * @package Modules\Admin\Http\Controllers
 */
class UserController extends BaseController
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
    protected $sortableFields = ['id', 'email', 'firstname', 'lastname', 'role_id', 'status', 'phone'];

    /**
     * Display a users list.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = User::with('role');
        if ($request->has('id') && $request->get('id') !== null) {
            $query->where('id', 'LIKE', '%' . $request->get('id') . '%');
        }
        if ($request->has('email') && $request->get('email') !== null) {
            $query->where('email', 'LIKE', '%' . $request->get('email') . '%');
        }
        if ($request->has('firstname') && $request->get('firstname') !== null) {
            $query->where('firstname', 'LIKE', '%' . $request->get('firstname') . '%');
        }
        if ($request->has('lastname') && $request->get('lastname') !== null) {
            $query->where('lastname', 'LIKE', '%' . $request->get('lastname') . '%');
        }
        if ($request->has('role_id') && $request->get('role_id') !== null) {
            $query->where('role_id', $request->get('role_id'));
        }
        if ($request->has('status') && $request->get('status') !== null) {
            $query->where('status', $request->get('status'));
        }
        if ($request->has('phone') && $request->get('phone') !== null) {
            $query->where('phone', 'LIKE', '%' . $request->get('phone') . '%');
        }

        $users = $query->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->itemsPerPage)
            ->appends($request->all());
            
        return view('admin::user.index', [
            'data' => $users,
            'pagination' => self::calculatePagination($users),
            'statuses' => User::getStatuses(),
            'roles' => Role::orderBy('title')->get()
        ]);
    }

    /**
     * Create user form.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin::user.create', [
            'statuses' => User::getStatuses(),
            'roles' => Role::orderBy('title')->get(),
            'active_status' => User::STATUS_ACTIVE
        ]);
    }

    /**
     * Create user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validate request, if fails -> redirect to the form with errors.
            $validator =Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'firstname' => 'required',
                'avatar_path' => 'nullable|image|mimetypes:image/png,image/gif,image/jpeg',
                'fb_link' => 'nullable|regex:/^(http(s)?):\/\/(www.)?facebook\.com\/.+$/',
                'instagram_link' => 'nullable|regex:/^(http(s)?):\/\/(www.)?instagram\.com\/.+$/',
                'phone' => 'nullable|regex:/\+38\(\d{3}\) \d{3}\-\d{4}/',
                'status' => ['required', Rule::in(array_keys(User::getStatuses()))],
                'role_id' => 'required|exists:roles,id'
            ]);

            if ($validator->fails()) {
                $old = $request->all();
                $avatarFile = $request->file('avatar');
                if ($avatarFile) {
                    $tempPath = File::generateTempSessionPath(USER_AVATAR_PATH);
                    $path = $avatarFile->store($tempPath);
                    $old['avatar_realpath'] = asset(STORAGE_PATH . DIRECTORY_SEPARATOR . $path);
                    $old['avatar_filename'] = $avatarFile->getClientOriginalName();
                }

                return redirect()
                    ->back()
                    ->withInput($old)
                    ->withErrors($validator);
            }

            // Create a user.
            /** @var User $user */
            $data = $request->all();
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);

            // Save avatar image.
            $path = $this->saveAvatar($request);
            if ($path !== false) {
                $user->update(['avatar_path' => $path]);
            }

            $request->session()->flash('success', trans('admin::user.flash.created', [
                'fullname' => $user->fullname
            ]));

            return redirect(LaravelLocalization::getLocalizedURL(null, '/users'));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);

            return redirect()
                ->back()
                ->withInput($request->all());
        }
    }

    /**
     * Edit user form.
     *
     * @param User $user
     * @return View
     */
    public function edit(User $user): View
    {
        return view('admin::user.edit', [
            'model' => $user,
            'statuses' => User::getStatuses(),
            'roles' => Role::orderBy('title')->get(),
            'avatar_asset' => $user->avatar_path ? asset(STORAGE_PATH . DIRECTORY_SEPARATOR . $user->avatar_path) : null
        ]);
    }

    /**
     * Update user
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        try {
            // Validate request, if fails -> redirect to the form with errors.
            $validator = Validator::make($request->all(), [
                'email' => "required|email|unique:users,email,{$user->id}",
                'firstname' => 'required',
                'avatar_path' => 'nullable|image|mimetypes:image/png,image/gif,image/jpeg',
                'fb_link' => 'nullable|regex:/^(http(s)?):\/\/(www.)?facebook\.com\/.+$/',
                'instagram_link' => 'nullable|regex:/^(http(s)?):\/\/(www.)?instagram\.com\/.+$/',
                'phone' => 'nullable|regex:/\+38\(\d{3}\) \d{3}\-\d{4}/',
                'status' => ['required', Rule::in(array_keys(User::getStatuses()))],
                'role_id' => 'required|exists:roles,id'
            ]);
            if ($validator->fails()) {
                $old = $request->all();
                $avatarFile = $request->file('avatar');
                if ($avatarFile) {
                    $tempPath = File::generateTempSessionPath(USER_AVATAR_PATH);
                    $path = $avatarFile->store($tempPath);
                    $old['avatar_realpath'] = asset(STORAGE_PATH . DIRECTORY_SEPARATOR . $path);
                    $old['avatar_filename'] = $avatarFile->getClientOriginalName();
                }

                return redirect()
                    ->back()
                    ->withInput($old)
                    ->withErrors($validator);
            }

            // Update a user.
            $user->update($request->all());

            // Save avatar image.
            $path = $this->saveAvatar($request, $user->avatar_path);
            if ($path !== false) {
                $user->update(['avatar_path' => $path]);
            }

            $request->session()->flash('success', trans('admin::user.flash.updated', [
                'fullname' => $user->fullname
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/users'));
    }

    /**
     * Delete user
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        try {
            $cloneUser = clone $user;

            $user->delete();

            // Remove avatar file.
            Storage::delete($cloneUser->avatar_path);

            $request->session()->flash('success', trans('admin::user.flash.deleted', [
                'fullname' => $cloneUser->fullname
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/users'));
    }

    /**
     * Activate user
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function activate(Request $request, User $user): RedirectResponse
    {
        try {
            $user->update([
                'status' => User::STATUS_ACTIVE
            ]);

            $request->session()->flash('success', trans('admin::user.flash.activated', [
                'fullname' => $user->fullname
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/users'));
    }

    /**
     * Block user
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function block(Request $request, User $user): RedirectResponse
    {
        try {
            $user->update([
                'status' => User::STATUS_BLOCKED
            ]);

            $request->session()->flash('success', trans('admin::user.flash.blocked', [
                'fullname' => $user->fullname
            ]));
        } catch (Exception $exception) {
            $this->handleException($exception, $request);
        }

        return redirect(LaravelLocalization::getLocalizedURL(null, '/users'));
    }

    /**
     * Search users
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $users = User::whereRaw("CONCAT_NULL(firstname, ' ', lastname) LIKE '%{$request->searchTerm}%'")
            ->limit(10)
            ->get();

        $formattedUsers = [];
        foreach ($users as $user) {
            $formattedUsers[] = ['id' => $user->id, 'text' => $user->fullname];
        }

        return response()->json($formattedUsers);
    }

    /**
     * Save avatar file in the storage.
     *
     * @param Request $request
     * @param null $currentPath
     *
     * @return bool|false|null|string
     */
    private function saveAvatar(Request $request, string $currentPath = null)
    {
        $avatarFile = $request->file('avatar');
        $distPath = File::generateDirPath(USER_AVATAR_PATH);
        $distFile = false;
        if ($avatarFile) {
            $distFile = $avatarFile->store($distPath);
        } elseif ($request->has('avatar_realpath')) {
            $source = parse_url($request->get('avatar_realpath'), PHP_URL_PATH);
            $source = str_replace(DIRECTORY_SEPARATOR . STORAGE_PATH . DIRECTORY_SEPARATOR, '', $source);
            if ($source != $currentPath) {
                $dist = $distPath . DIRECTORY_SEPARATOR . basename($source);
                if (Storage::move($source, $dist)) {
                    $distFile = $dist;
                }
            }
        } else {
            $distFile = null;
        }

        if ($distFile !== false && $distFile != $currentPath && Storage::exists($currentPath)) {
            Storage::delete($currentPath);
        }

        $tempFolder = File::generateTempSessionPath(USER_AVATAR_PATH);
        Storage::deleteDirectory($tempFolder);

        return $distFile;
    }
}
