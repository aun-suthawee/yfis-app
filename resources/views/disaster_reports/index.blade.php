@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1 fw-bold text-primary-custom">รายการรายงานภัยพิบัติ</h1>
        <p class="text-muted mb-0">ติดตามสถานการณ์และสถานะของหน่วยงานในพื้นที่</p>
    </div>

    @include('components.filter-bar', [
        'action' => route('disaster.filter'),
        'method' => 'GET',
        'filters' => $filters,
        'districts' => $districts,
        'affiliations' => $affiliations,
    ])

    <div class="d-flex justify-content-end flex-wrap gap-2 mb-4">
        <a href="{{ route('export.excel', $filters) }}" class="btn btn-outline-success btn-sm d-flex align-items-center">
            <i class="bi bi-file-excel me-2"></i> Export Excel
        </a>
        <a href="{{ route('export.csv', $filters) }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center">
            <i class="bi bi-filetype-csv me-2"></i> Export CSV
        </a>
        <a href="{{ route('export.json', $filters) }}" class="btn btn-outline-info btn-sm d-flex align-items-center">
            <i class="bi bi-filetype-json me-2"></i> Export JSON
        </a>
        @if(auth()->user()?->hasAnyRole(['admin', 'data-entry']))
            <a href="{{ route('disaster.create') }}" class="btn btn-primary btn-sm d-flex align-items-center">
                <i class="bi bi-plus-lg me-2"></i> เพิ่มรายงานใหม่
            </a>
        @endif
    </div>

    @include('components.table-list', ['reports' => $reports])
@endsection
