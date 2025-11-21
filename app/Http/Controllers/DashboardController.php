<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterDisasterReportRequest;
use App\Models\Affiliation;
use App\Models\District;
use App\Models\Shelter;
use App\Services\DisasterReportService;
use App\Services\WeatherService;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private DisasterReportService $service,
        private WeatherService $weatherService
    ) {
    }

    public function index(FilterDisasterReportRequest $request): View
    {
        $filters = $request->filters();
        $dashboard = $this->service->dashboardData($filters);
        $riskAssessment = $this->weatherService->getRiskAssessment();

        // Shelter Stats
        $shelterQuery = Shelter::query();
        // If we had an affiliation filter, we could apply it here too, but shelters might not be linked to the report filters directly.
        // For now, global stats for shelters.
        $moeSheltersCount = $shelterQuery->count();
        $moeKitchensCount = $shelterQuery->where('is_kitchen', true)->count();

        // Build shelter sparkline timeline (last 7 days)
        $shelterSparklines = $this->buildShelterSparklines();

        $dashboard['shelterStats'] = [
            'total_shelters' => $moeSheltersCount,
            'total_kitchens' => $moeKitchensCount,
        ];

        // Merge shelter sparklines into dashboard sparklines
        if (!isset($dashboard['sparklines'])) {
            $dashboard['sparklines'] = [];
        }
        $dashboard['sparklines']['shelters'] = $shelterSparklines['shelters'];
        $dashboard['sparklines']['kitchens'] = $shelterSparklines['kitchens'];

        return view('dashboard.index', [
            'filters' => $filters,
            'dashboard' => $dashboard,
            'riskAssessment' => $riskAssessment,
            'districts' => District::orderBy('name')->get(),
            'affiliations' => Affiliation::orderBy('name')->get(),
            'shelters' => Shelter::whereNotNull('latitude')->whereNotNull('longitude')->get(),
        ]);
    }

    /**
     * Build sparkline timeline for shelters and kitchens
     */
    private function buildShelterSparklines(): array
    {
        // Get last 7 days dates
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $last7Days->push(now()->subDays($i)->format('Y-m-d'));
        }

        // Get all shelters grouped by creation date
        $shelters = Shelter::all();
        
        $sheltersByDay = $shelters->groupBy(fn ($item) => optional($item->created_at)->format('Y-m-d'))
            ->map(fn ($items) => $items->count());
        
        $kitchensByDay = $shelters->filter(fn ($s) => $s->is_kitchen)
            ->groupBy(fn ($item) => optional($item->created_at)->format('Y-m-d'))
            ->map(fn ($items) => $items->count());

        // Map to last 7 days with cumulative count (since shelters are created once and stay)
        // We'll show daily new registrations instead
        return [
            'shelters' => $last7Days->map(fn ($date) => $sheltersByDay->get($date, 0))->values()->toArray(),
            'kitchens' => $last7Days->map(fn ($date) => $kitchensByDay->get($date, 0))->values()->toArray(),
        ];
    }
}
