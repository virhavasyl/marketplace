<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\Role;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/**
 * Class LoginController
 * @package Modules\Admin\Http\Controllers
 */
class LoginController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('admin::login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|null|\Symfony\Component\HttpFoundation\Response|void
     */
    public function login(Request $request)
    {
        try {
            $this->validateLogin($request);

            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            $request->request->add(['role_id' => Role::ADMINISTRATOR]);
            if ($this->attemptLogin($request)) {
                return $this->sendLoginResponse($request);
            }

            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request);
        } catch (Exception $exception) {
            $this->handleException($exception, $request, true);
        }

        return null;
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password', 'role_id');
    }

    /**
     * Where to redirect users after login.
     *
     * @return mixed
     */
    protected function redirectTo()
    {
        return LaravelLocalization::getLocalizedURL(null, '/');
    }
}
