@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1 fw-bold text-primary-custom">ชุดข้อมูลสำหรับแดชบอร์ด</h1>
        <p class="text-muted mb-0">กรองและเตรียมข้อมูลเพื่อการวิเคราะห์เชิงลึก</p>
    </div>

    @include('components.filter-bar', [
        'action' => route('disaster.dataset'),
        'method' => 'GET',
        'filters' => $filters,
        'districts' => $districts,
        'affiliations' => $affiliations,
    ])

    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="{{ route('dashboard.export.excel', $filters) }}" class="btn btn-outline-success btn-sm d-flex align-items-center">
            <i class="bi bi-file-excel me-2"></i> Export Excel
        </a>
        <a href="{{ route('dashboard.export.pdf', $filters) }}" class="btn btn-outline-danger btn-sm d-flex align-items-center">
            <i class="bi bi-file-pdf me-2"></i> Export PDF
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-4 g-3">
                <x-card-stat title="จำนวนรายการ" :value="$reports->count()" icon="database" variant="info" />
                <x-card-stat title="มูลค่าความเสียหายรวม" :value="number_format($reports->sum(fn($item) => (float) $item->damage_total_request), 2)" icon="cash-coin" variant="danger" />
                <x-card-stat title="นักเรียนได้รับผลกระทบ" :value="number_format($reports->sum(fn($item) => (int) $item->affected_students))" icon="people" variant="primary" />
                <x-card-stat title="บุคลากรได้รับผลกระทบ" :value="number_format($reports->sum(fn($item) => (int) $item->affected_staff))" icon="person-badge" variant="warning" />
            </div>
        </div>
    </div>

    @include('components.table-list', ['reports' => $reports, 'showActions' => true])
@endsection
