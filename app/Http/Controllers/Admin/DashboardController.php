<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * @var DashboardService
     */
    private $dashboardService;

    /**
     * BrandController constructor.
     *
     * @param DashboardService $dashboardService
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Displays a dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('admin.dashboard.index', $this->dashboardService->index($request));
    }

    /**
     * Displays a dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function statistical(Request $request)
    {
        return view('admin.statistical.index', $this->dashboardService->statistical($request));
    }


}
