<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterDisasterReportRequest;
use App\Http\Requests\StoreDisasterReportRequest;
use App\Http\Requests\UpdateDisasterReportRequest;
use App\Models\Affiliation;
use App\Models\DisasterReport;
use App\Models\District;
use App\Services\DisasterReportService;
use App\Services\ExportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DisasterReportController extends Controller
{
    public function __construct(
        private DisasterReportService $service,
        private ExportService $exportService
    ) {
        $this->middleware('auth')->except(['dataset']);
        $this->middleware('role:admin,data-entry,yfis')->only(['create', 'store', 'edit', 'update', 'destroy']);
        $this->middleware('role:admin')->only(['publish', 'unpublish', 'bulkPublish']);
        $this->middleware('throttle:disaster-submissions')->only(['store', 'update']);
    }

    public function index(FilterDisasterReportRequest $request): View
    {
        $filters = $request->filters();
        $reports = $this->service->paginate($filters, 15);

        return view('disaster_reports.index', [
            'reports' => $reports,
            'filters' => $filters,
            'districts' => District::orderBy('name')->get(),
            'affiliations' => Affiliation::orderBy('name')->get(),
        ]);
    }

    public function dataset(FilterDisasterReportRequest $request): View
    {
        $filters = $request->filters();
        $reports = $this->service->dataset($filters);

        return view('disaster_reports.dataset', [
            'reports' => $reports,
            'filters' => $filters,
            'districts' => District::orderBy('name')->get(),
            'affiliations' => Affiliation::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        $url = route('disaster.create');
        $qrCode = QrCode::size(200)->generate($url);

        return view('disaster_reports.create', [
            'districts' => District::orderBy('name')->get(),
            'affiliations' => Affiliation::orderBy('name')->get(),
            'qrCode' => $qrCode,
            'url' => $url,
        ]);
    }

    public function store(StoreDisasterReportRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()
            ->route('disaster.index')
            ->with('status', __('บันทึกข้อมูลเรียบร้อยแล้ว'));
    }

    public function show(DisasterReport $disasterReport): View
    {
        return view('disaster_reports.show', [
            'report' => $disasterReport->load(['district', 'affiliation']),
        ]);
    }

    public function edit(DisasterReport $disasterReport): View
    {
        $this->authorize('update', $disasterReport);
        
        return view('disaster_reports.edit', [
            'report' => $disasterReport,
            'districts' => District::orderBy('name')->get(),
            'affiliations' => Affiliation::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateDisasterReportRequest $request, DisasterReport $disasterReport): RedirectResponse
    {
        $this->authorize('update', $disasterReport);
        
        $this->service->update($disasterReport, $request->validated());

        return redirect()
            ->route('disaster.index')
            ->with('status', __('ปรับปรุงข้อมูลเรียบร้อยแล้ว'));
    }

    public function destroy(DisasterReport $disasterReport): RedirectResponse
    {
        $this->authorize('delete', $disasterReport);
        
        $this->service->delete($disasterReport);

        return redirect()
            ->route('disaster.index')
            ->with('status', __('ลบข้อมูลเรียบร้อยแล้ว'));
    }

    public function filter(FilterDisasterReportRequest $request): RedirectResponse
    {
        return redirect()->route('disaster.index', $request->filters());
    }

    public function exportExcel(FilterDisasterReportRequest $request)
    {
        return $this->exportService->exportExcel($request->filters());
    }

    public function exportCsv(FilterDisasterReportRequest $request)
    {
        return $this->exportService->exportCsv($request->filters());
    }

    public function exportJson(FilterDisasterReportRequest $request)
    {
        return $this->exportService->exportJson($request->filters());
    }

    public function confirmation(DisasterReport $disasterReport): View
    {
        $url = route('disaster.show', $disasterReport);
        $qrCode = QrCode::format('svg')->size(220)->margin(1)->generate($url);

        return view('disaster_reports.confirmation', [
            'report' => $disasterReport->load(['district', 'affiliation']),
            'qrCodeSvg' => $qrCode,
            'shareUrl' => $url,
        ]);
    }

    public function publish(DisasterReport $disasterReport): RedirectResponse
    {
        $this->service->publish($disasterReport);

        return back()->with('status', __('เผยแพร่รายงานเรียบร้อยแล้ว'));
    }

    public function unpublish(DisasterReport $disasterReport): RedirectResponse
    {
        $this->service->unpublish($disasterReport);

        return back()->with('status', __('ยกเลิกการเผยแพร่รายงานเรียบร้อยแล้ว'));
    }

    public function bulkPublish(\Illuminate\Http\Request $request): RedirectResponse
    {
        $request->validate([
            'selected_reports' => 'required|array',
            'selected_reports.*' => 'exists:disaster_reports,id',
            'action' => 'required|in:publish,unpublish',
        ]);

        $ids = $request->input('selected_reports');
        $action = $request->input('action');

        if ($action === 'publish') {
            DisasterReport::whereIn('id', $ids)->update(['is_published' => true]);
            $message = __('เผยแพร่รายงานที่เลือกเรียบร้อยแล้ว');
        } else {
            DisasterReport::whereIn('id', $ids)->update(['is_published' => false]);
            $message = __('ยกเลิกการเผยแพร่รายงานที่เลือกเรียบร้อยแล้ว');
        }

        return back()->with('status', $message);
    }
}
