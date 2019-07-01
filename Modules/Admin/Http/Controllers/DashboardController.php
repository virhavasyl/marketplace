<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Response;

/**
 * Class DashboardController
 * @package Modules\Admin\Http\Controllers
 */
class DashboardController extends BaseController
{
    /**
     * Display a dashboard.
     * @return Response
     */
    public function index()
    {
        return view('admin::dashboard');
    }
}
