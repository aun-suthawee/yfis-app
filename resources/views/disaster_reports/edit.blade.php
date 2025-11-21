@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-primary-custom">แก้ไขรายงานภัยพิบัติ</h1>
            <p class="text-muted mb-0">อัปเดตข้อมูลให้ตรงกับสถานการณ์ล่าสุด</p>
        </div>
        <a href="{{ route('disaster.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> กลับไปหน้ารายการ
        </a>
    </div>

    @include('disaster_reports.partials.form_modern', [
        'id' => 'edit-report-form',
        'action' => route('disaster.update', $report),
        'method' => 'PUT',
        'report' => $report,
        'districts' => $districts,
        'affiliations' => $affiliations,
        'submitLabel' => 'บันทึกการเปลี่ยนแปลง',
    ])
@endsection
