<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterDisasterReportRequest;
use App\Models\Affiliation;
use App\Models\District;
use App\Models\Shelter;
use App\Services\DisasterReportService;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct(private DisasterReportService $service)
    {
        //
    }

    public function index(FilterDisasterReportRequest $request): View
    {
        $filters = $request->filters();
        $dashboard = $this->service->dashboardData($filters);

        return view('dashboard.index', [
            'filters' => $filters,
            'dashboard' => $dashboard,
            'districts' => District::orderBy('name')->get(),
            'affiliations' => Affiliation::orderBy('name')->get(),
            'shelters' => Shelter::whereNotNull('latitude')->whereNotNull('longitude')->get(),
        ]);
    }
}
