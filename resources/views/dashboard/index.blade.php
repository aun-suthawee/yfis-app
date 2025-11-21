@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1 fw-bold text-white">แดชบอร์ดสถานการณ์อุทกภัยจังหวัดยะลา</h1>
        <p class="text-white mb-0" style="text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);">
            ข้อมูลเรียลไทม์สำหรับการตัดสินใจเชิงนโยบาย</p>
    </div>

    {{-- @include('components.filter-bar', [
        'action' => route('dashboard.index'),
        'method' => 'GET',
        'filters' => $filters,
        'districts' => $districts,
        'affiliations' => $affiliations,
    ]) --}}

    <div class="d-flex justify-content-end gap-2 mb-4">
        {{-- <a href="{{ route('dashboard.export.pdf', $filters) }}" class="btn btn-outline-danger btn-sm d-flex align-items-center">
            <i class="bi bi-file-pdf me-2"></i> Export PDF
        </a> --}}
        {{-- <a href="{{ route('dashboard.export.excel', $filters) }}"
            class="btn btn-outline-success btn-sm d-flex align-items-center">
            <i class="bi bi-file-excel me-2"></i> Export Excel
        </a> --}}
    </div>

    {{-- Main charts with Sparkline Charts --}}
    <div class="row g-3 mb-4">
        <!-- big left / big right layout first row then mixed below -->
        <!-- 1. Affected Institutions (large) -->
        <div class="col-lg-6">
            <a href="{{ route('disaster.index') }}" class="text-decoration-none">
                <div class="card card-lg h-100 border-0 shadow-sm hover-shadow transition-all position-relative overflow-hidden"
                    style="background: rgba(13, 110, 253, 0.3) !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="grow">
                                <h6 class="text-white text-uppercase small fw-bold mb-2">สถานศึกษาที่ได้รับผลกระทบ</h6>
                                <div class="d-flex align-items-baseline mb-1">
                                    <h2 class="mb-0 me-2 text-white fw-bold">
                                        {{ number_format($dashboard['summary']['total_affected']) }}</h2>
                                    <span class="text-white small">แห่ง</span>
                                </div>
                                <small class="text-white fw-bold">
                                    <i
                                        class="bi bi-pie-chart-fill me-1"></i>{{ number_format($dashboard['summary']['affected_percent'], 2) }}%
                                </small>
                                <span class="text-white small d-block">จาก
                                    {{ number_format($dashboard['summary']['total_schools_base']) }} แห่ง</span>
                            </div>
                            <div class="stat-icon-circle bg-light bg-opacity-25">
                                <i class="bi bi-building-fill text-light fs-4"></i>
                            </div>
                        </div>
                            <div class="mt-3">
                                <canvas id="sparkline1" height="100"></canvas>
                            </div>
                    </div>
                    <div class="card-footer border-0 py-2 small text-white"
                        style="background: rgba(13, 110, 253, 0.2) !important;">
                        <i class="bi bi-graph-up me-1"></i>แนวโน้ม 7 วันล่าสุด
                    </div>
                </div>
            </a>
        </div>

        <!-- 2. Estimated Damage (large) -->
        <div class="col-lg-6">
            <div class="card card-lg h-100 border-0 shadow-sm position-relative overflow-hidden"
                style="background: rgba(108, 117, 125, 0.3) !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="grow">
                            <h6 class="text-white text-uppercase small fw-bold mb-2">ประมาณการความเสียหาย</h6>
                            <div class="d-flex align-items-baseline mb-1">
                                <h2 class="mb-0 me-2 text-white fw-bold">
                                    {{ number_format($dashboard['summary']['total_damage'] / 1000000, 1) }}</h2>
                                <span class="text-white small">ล้านบาท</span>
                            </div>
                            <small class="text-white d-block">อาคาร + ครุภัณฑ์ + วัสดุ</small>
                        </div>
                        <div class="stat-icon-circle bg-light bg-opacity-25">
                            <i class="bi bi-cash-stack text-light fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <canvas id="sparkline5" height="100"></canvas>
                    </div>
                </div>
                <div class="card-footer border-0 py-2 small text-white"
                    style="background: rgba(108, 117, 125, 0.2) !important;">
                    <i class="bi bi-graph-up me-1"></i>แนวโน้ม 7 วันล่าสุด
                </div>
            </div>
        </div>

        <!-- 3. Affected Students (medium) -->
        <div class="col-md-4">
            <div class="card card-md h-100 border-0 shadow-sm position-relative overflow-hidden"
                style="background: rgba(255, 193, 7, 0.3) !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="grow">
                            <h6 class="text-white text-uppercase small fw-bold mb-2">นักเรียนที่ได้รับผลกระทบ</h6>
                            <div class="d-flex align-items-baseline mb-1">
                                <h2 class="mb-0 me-2 text-white fw-bold">
                                    {{ number_format($dashboard['humanImpact']['students']['affected']) }}</h2>
                                <span class="text-white small">คน</span>
                            </div>
                            <div class="small">
                                <span class="text-white me-2"><i class="bi bi-heartbreak-fill"></i>
                                    {{ number_format($dashboard['humanImpact']['students']['dead']) }}</span>
                                <span class="text-white"><i class="bi bi-bandaid-fill"></i>
                                    {{ number_format($dashboard['humanImpact']['students']['injured']) }}</span>
                            </div>
                        </div>
                        <div class="stat-icon-circle bg-light bg-opacity-25">
                            <i class="bi bi-people-fill text-light fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <canvas id="sparkline3" height="80"></canvas>
                    </div>
                </div>
                <div class="card-footer border-0 py-2 small text-white"
                    style="background: rgba(255, 193, 7, 0.2) !important;">
                    <i class="bi bi-graph-up me-1"></i>แนวโน้ม 7 วันล่าสุด
                </div>
            </div>
        </div>

        <!-- 4. Affected Staff (medium) -->
        <div class="col-md-4">
            <div class="card card-md h-100 border-0 shadow-sm position-relative overflow-hidden"
                style="background: rgba(13, 202, 240, 0.3) !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="grow">
                            <h6 class="text-white text-uppercase small fw-bold mb-2">ครู/บุคลากรที่ได้รับผลกระทบ</h6>
                            <div class="d-flex align-items-baseline mb-1">
                                <h2 class="mb-0 me-2 text-white fw-bold">
                                    {{ number_format($dashboard['humanImpact']['staff']['affected']) }}</h2>
                                <span class="text-white small">คน</span>
                            </div>
                            <div class="small">
                                <span class="text-white me-2"><i class="bi bi-heartbreak-fill"></i>
                                    {{ number_format($dashboard['humanImpact']['staff']['dead']) }}</span>
                                <span class="text-white"><i class="bi bi-bandaid-fill"></i>
                                    {{ number_format($dashboard['humanImpact']['staff']['injured']) }}</span>
                            </div>
                        </div>
                        <div class="stat-icon-circle bg-light bg-opacity-25">
                            <i class="bi bi-person-badge-fill text-light fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <canvas id="sparkline4" height="80"></canvas>
                    </div>
                </div>
                <div class="card-footer border-0 py-2 small text-white"
                    style="background: rgba(13, 202, 240, 0.2) !important;">
                    <i class="bi bi-graph-up me-1"></i>แนวโน้ม 7 วันล่าสุด
                </div>
            </div>
        </div>

        <!-- 5. Closed Institutions (medium) -->
        <div class="col-md-4">
            <a href="{{ route('disaster.index', ['teaching_status' => 'closed']) }}" class="text-decoration-none">
                <div class="card card-md h-100 border-0 shadow-sm hover-shadow transition-all position-relative overflow-hidden"
                    style="background: rgba(220, 53, 69, 0.3) !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="grow">
                                <h6 class="text-white text-uppercase small fw-bold mb-2">ปิดการเรียนการสอน</h6>
                                <div class="d-flex align-items-baseline mb-1">
                                    <h2 class="mb-0 me-2 text-white fw-bold">
                                        {{ number_format($dashboard['summary']['total_closed']) }}</h2>
                                    <span class="text-white small">แห่ง</span>
                                </div>
                                <small class="text-white fw-bold">
                                    <i class="bi bi-pie-chart-fill me-1"></i>{{ number_format($dashboard['summary']['closed_percent'], 1) }}%
                                </small>
                                <span class="text-white small d-block">ของผู้ได้รับผลกระทบ</span>
                            </div>
                            <div class="stat-icon-circle bg-light bg-opacity-25">
                                <i class="bi bi-x-circle-fill text-light fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <canvas id="sparkline2" height="80"></canvas>
                        </div>
                    </div>
                    <div class="card-footer border-0 py-2 small text-white" style="background: rgba(220, 53, 69, 0.2) !important;">
                        <i class="bi bi-graph-up me-1"></i>แนวโน้ม 7 วันล่าสุด
                    </div>
                </div>
            </a>
        </div>

        <!-- 6. MOE Shelters (small) -->
        <div class="col-md-4">
            <a href="{{ route('shelters.index') }}" class="text-decoration-none">
                <div class="card card-sm h-100 border-0 shadow-sm hover-shadow transition-all position-relative overflow-hidden"
                    style="background: rgba(25, 135, 84, 0.3) !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="grow">
                                <h6 class="text-white text-uppercase small fw-bold mb-2">ศูนย์พักพิง (ศธ.)</h6>
                                <div class="d-flex align-items-baseline mb-1">
                                    <h2 class="mb-0 me-2 text-white fw-bold">
                                        {{ number_format($dashboard['shelterStats']['total_shelters']) }}</h2>
                                    <span class="text-white small">แห่ง</span>
                                </div>
                                <small class="text-white"><i
                                        class="bi bi-house-heart-fill me-1"></i>พร้อมให้บริการ</small>
                            </div>
                            <div class="stat-icon-circle bg-light bg-opacity-25">
                                <i class="bi bi-house-heart-fill text-light fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <canvas id="sparkline6" height="60"></canvas>
                        </div>
                    </div>
                    <div class="card-footer border-0 py-2 small text-white"
                        style="background: rgba(25, 135, 84, 0.2) !important;">
                        <i class="bi bi-graph-up me-1"></i>สถานะเปิดรับ
                    </div>
                </div>
            </a>
        </div>

        <!-- 7. MOE Kitchens (small) -->
        <div class="col-md-4">
            <div class="card card-sm h-100 border-0 shadow-sm position-relative overflow-hidden"
                style="background: rgba(253, 126, 20, 0.3) !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="grow">
                            <h6 class="text-white text-uppercase small fw-bold mb-2">โรงครัว (ศธ.)</h6>
                            <div class="d-flex align-items-baseline mb-1">
                                <h2 class="mb-0 me-2 fw-bold text-white">
                                    {{ number_format($dashboard['shelterStats']['total_kitchens']) }}</h2>
                                <span class="text-white small">แห่ง</span>
                            </div>
                            <small class="text-white"><i class="bi bi-cup-hot-fill me-1"></i>สนับสนุนอาหาร</small>
                        </div>
                        <div class="stat-icon-circle bg-light bg-opacity-25">
                            <i class="bi bi-cup-hot-fill text-light fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <canvas id="sparkline7" height="60"></canvas>
                    </div>
                </div>
                <div class="card-footer border-0 py-2 small text-white"
                    style="background: rgba(253, 126, 20, 0.2) !important;">
                    <i class="bi bi-graph-up me-1"></i>สนับสนุนอาหาร
                </div>
            </div>
        </div>

        <!-- 8. Severe Impact (small) -->
        <div class="col-md-4">
            <div class="card card-sm h-100 border-0 shadow-sm position-relative overflow-hidden"
                style="background: rgba(33, 37, 41, 0.3) !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="grow">
                            <h6 class="text-white text-uppercase small fw-bold mb-2">ผลกระทบร้ายแรง</h6>
                            <div class="d-flex align-items-baseline mb-1">
                                <h2 class="mb-0 me-2 text-white fw-bold">
                                    {{ number_format($dashboard['summary']['severe_count']) }}</h2>
                                <span class="text-white small">แห่ง</span>
                            </div>
                            <small class="text-white d-block">น้ำท่วม + เสียหายทรัพย์สิน</small>
                        </div>
                        <div class="stat-icon-circle bg-light bg-opacity-25">
                            <i class="bi bi-exclamation-triangle-fill text-light fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <canvas id="sparkline8" height="60"></canvas>
                    </div>
                </div>
                <div class="card-footer border-0 py-2 small text-white"
                    style="background: rgba(33, 37, 41, 0.2) !important;">
                    <i class="bi bi-graph-up me-1"></i>แนวโน้ม 7 วันล่าสุด
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">ค่าเสียหายแยกตามประเภท</h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 220px;">
                        <canvas id="damageBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">สัดส่วนประเภทภัยพิบัติ</h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 220px;">
                        <canvas id="disasterPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">จำนวนเหตุการณ์ตามวันเวลา</h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 220px;">
                        <canvas id="timelineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">สถานการณ์ปัจจุบัน</h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 220px;">
                        <canvas id="statusDonutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">สถานะการเปิดเรียน</h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 220px;">
                        <canvas id="teachingStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">ผลกระทบต่อบุคคล (บาดเจ็บ/เสียชีวิต)</h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 220px;">
                        <canvas id="humanImpactChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">ความเสียหายรายอำเภอ (บาท)</h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 300px;">
                        <canvas id="damageDistrictChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h2 class="h6 mb-0">จำนวนรายงานแยกตามสังกัด</h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 300px;">
                        <canvas id="affiliationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white border-0">
            <h2 class="h6 mb-0">แผนที่หน่วยงานที่ได้รับผลกระทบ</h2>
        </div>
        <div class="card-body">
            <div id="dashboard-map" style="height: 420px;"></div>
        </div>
    </div>

    <!-- Risk Assessment Section -->
    <div class="card shadow-sm mt-4 border-0 rounded-4">
        <div class="card-header bg-white py-3 border-bottom-0 rounded-top-4">
            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-cloud-lightning-rain-fill me-2"></i>เฝ้าระวังสถานการณ์
                (Risk Assessment)</h5>
        </div>
        <div class="card-body pt-0">
            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <div class="alert alert-info border-0 bg-info bg-opacity-25 rounded-3 mb-0 h-100">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                            <div>
                                <strong>แหล่งข้อมูล:</strong> Open-Meteo Weather API<br>
                                <small class="text-muted">
                                    ข้อมูลพยากรณ์อากาศจาก NOAA, DWD, และ Met Office UK
                                    <span class="badge bg-info bg-opacity-55 text-dark ms-1">อัปเดตล่าสุด:
                                        {{ $riskAssessment['updated_at'] ?? now()->setTimezone('Asia/Bangkok')->format('H:i') }}
                                        น.</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row g-3 text-center mb-4">
                <div class="col-md-3">
                    <div class="p-3 rounded-4 bg-danger text-white h-100 position-relative overflow-hidden">
                        <h3 class="fw-bold mb-1">{{ $riskAssessment['high'] }}</h3>
                        <small>เสี่ยงสูง (High)</small>
                        <div class="small opacity-75 mt-1">> 90 มม.</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 rounded-4 bg-warning text-dark h-100 position-relative overflow-hidden">
                        <h3 class="fw-bold mb-1">{{ $riskAssessment['medium'] }}</h3>
                        <small>เสี่ยงปานกลาง (Medium)</small>
                        <div class="small opacity-75 mt-1">35-90 มม.</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 rounded-4 bg-info text-white h-100 position-relative overflow-hidden">
                        <h3 class="fw-bold mb-1">{{ $riskAssessment['low'] }}</h3>
                        <small>เสี่ยงน้อย (Low)</small>
                        <div class="small opacity-75 mt-1">10-35 มม.</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 rounded-4 bg-success text-white h-100 position-relative overflow-hidden">
                        <h3 class="fw-bold mb-1">{{ $riskAssessment['none'] }}</h3>
                        <small>ไม่มีความเสี่ยง (None)</small>
                        <div class="small opacity-75 mt-1">
                            < 10 มม.</div>
                        </div>
                    </div>
                </div>

                <!-- Detailed District Information -->
                @if (isset($riskAssessment['details']) && count($riskAssessment['details']) > 0)
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3 text-muted"><i class="bi bi-list-ul me-2"></i>รายละเอียดตามอำเภอ</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>อำเภอ</th>
                                        <th class="text-end">ปริมาณฝน (มม.)</th>
                                        <th class="text-center" style="width: 150px;">ระดับความเสี่ยง</th>
                                        <th class="text-center" style="width: 120px;">สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Sort by risk level (high to low) then by rain amount
                                        $sortedDetails = collect($riskAssessment['details'])->sortByDesc(function (
                                            $detail,
                                        ) {
                                            $priority = ['high' => 4, 'medium' => 3, 'low' => 2, 'none' => 1];
                                            return $priority[$detail['level']] * 1000 + $detail['rain_mm'];
                                        });
                                    @endphp

                                    @foreach ($sortedDetails as $index => $detail)
                                        <tr>
                                            <td class="fw-bold">
                                                <i class="bi bi-geo-alt-fill text-primary me-1"></i>
                                                {{ $detail['district'] }}
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-secondary bg-opacity-55 text-dark px-3 py-2">
                                                    <i
                                                        class="bi bi-droplet-fill me-1"></i>{{ number_format($detail['rain_mm'], 1) }}
                                                    มม.
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if ($detail['level'] === 'high')
                                                    <span class="badge bg-danger px-3 py-2">
                                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>เสี่ยงสูง
                                                    </span>
                                                @elseif($detail['level'] === 'medium')
                                                    <span class="badge bg-warning text-dark px-3 py-2">
                                                        <i class="bi bi-exclamation-circle-fill me-1"></i>ปานกลาง
                                                    </span>
                                                @elseif($detail['level'] === 'low')
                                                    <span class="badge bg-info px-3 py-2">
                                                        <i class="bi bi-info-circle-fill me-1"></i>เสี่ยงน้อย
                                                    </span>
                                                @else
                                                    <span class="badge bg-success px-3 py-2">
                                                        <i class="bi bi-check-circle-fill me-1"></i>ปกติ
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($detail['level'] === 'high')
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-danger" role="progressbar"
                                                            style="width: 100%"></div>
                                                    </div>
                                                    <small class="text-danger fw-bold">ติดตามอย่างใกล้ชิด</small>
                                                @elseif($detail['level'] === 'medium')
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" role="progressbar"
                                                            style="width: 75%"></div>
                                                    </div>
                                                    <small class="text-warning fw-bold">ระมัดระวัง</small>
                                                @elseif($detail['level'] === 'low')
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: 50%"></div>
                                                    </div>
                                                    <small class="text-info">เฝ้าติดตาม</small>
                                                @else
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: 25%"></div>
                                                    </div>
                                                    <small class="text-success">ปกติ</small>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Risk Level Legend -->
                        <div class="mt-3 p-3 bg-light rounded-3">
                            <div class="row g-2 small">
                                <div class="col-md-3">
                                    <strong class="text-danger"><i
                                            class="bi bi-exclamation-triangle-fill me-1"></i>เสี่ยงสูง:</strong> > 90 มม.
                                    (ฝนตกหนักมาก)
                                </div>
                                <div class="col-md-3">
                                    <strong class="text-warning"><i
                                            class="bi bi-exclamation-circle-fill me-1"></i>ปานกลาง:</strong> 35-90 มม.
                                    (ฝนตกหนัก)
                                </div>
                                <div class="col-md-3">
                                    <strong class="text-info"><i
                                            class="bi bi-info-circle-fill me-1"></i>เสี่ยงน้อย:</strong> 10-35 มม.
                                    (ฝนปานกลาง)
                                </div>
                                <div class="col-md-3">
                                    <strong class="text-success"><i
                                            class="bi bi-check-circle-fill me-1"></i>ปกติ:</strong>
                                    < 10 มม. (ฝนเล็กน้อย/ไม่มี) </div>
                                </div>
                            </div>
                        </div>
                @endif
            </div>
        </div>
    @endsection

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
        <style>
            /* Glass Card Effect */
            .card {
                background: rgba(255, 255, 255, 0.85) !important;
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.3) !important;
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15) !important;
            }

            /* Sizes for mixed layout */
            .card-lg {
                min-height: 260px;
            }

            .card-md {
                min-height: 200px;
            }

            .card-sm {
                min-height: 150px;
            }

            .card-header {
                background: rgba(255, 255, 255, 0.6) !important;
                backdrop-filter: blur(5px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.3) !important;
            }

            .card-footer {
                background: rgba(255, 255, 255, 0.6) !important;
                backdrop-filter: blur(5px);
                border-top: 1px solid rgba(255, 255, 255, 0.3) !important;
            }

            /* Header Text with Shadow */
            .text-primary-custom,
            h1,
            h2,
            h5 {
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            }

            .text-muted {
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            }

            /* Stat Icon Circle */
            .stat-icon-circle {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .hover-shadow {
                transition: all 0.3s ease;
            }

            .hover-shadow:hover {
                transform: translateY(-2px);
                box-shadow: 0 0.5rem 1rem rgba(31, 38, 135, 0.25) !important;
            }

            /* Alert boxes with glass effect */
            .alert {
                background: rgba(255, 255, 255, 0.9) !important;
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.3) !important;
            }

            /* Button enhancements */
            .btn {
                backdrop-filter: blur(5px);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .custom-tooltip .leaflet-tooltip-content {
                font-family: 'Prompt', sans-serif;
                font-size: 0.8rem;
            }

            /* Custom Cluster Colors for Severity */
            .marker-cluster-small {
                background-color: rgba(253, 126, 20, 0.4);
                /* Orange */
            }

            .marker-cluster-small div {
                background-color: rgba(253, 126, 20, 0.7);
            }

            .marker-cluster-medium {
                background-color: rgba(220, 53, 69, 0.4);
                /* Red */
            }

            .marker-cluster-medium div {
                background-color: rgba(220, 53, 69, 0.7);
            }

            .marker-cluster-large {
                background-color: rgba(139, 0, 0, 0.4);
                /* Dark Red */
            }

            .marker-cluster-large div {
                background-color: rgba(139, 0, 0, 0.7);
            }

            /* Increase Cluster Size */
            .marker-cluster {
                background-clip: padding-box;
                border-radius: 30px;
            }

            .marker-cluster div {
                width: 50px;
                height: 50px;
                margin-left: 5px;
                margin-top: 5px;
                text-align: center;
                border-radius: 25px;
                font-family: 'Prompt', sans-serif;
                font-size: 1.2rem;
                font-weight: bold;
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .marker-cluster span {
                line-height: 50px;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Ensure Chart.js is loaded
                if (typeof Chart === 'undefined') {
                    console.error('Chart.js library not loaded');
                    return;
                }

                // Helper to create charts safely
                const createChart = (id, type, data, options = {}) => {
                    const canvas = document.getElementById(id);
                    if (!canvas) {
                        console.warn(`Canvas element ${id} not found`);
                        return;
                    }

                    try {
                        // Destroy existing chart if any (though unlikely on page load)
                        const existingChart = Chart.getChart(canvas);
                        if (existingChart) existingChart.destroy();

                        new Chart(canvas, {
                            type: type,
                            data: data,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            boxWidth: 12,
                                            padding: 15,
                                            font: {
                                                family: "'Prompt', sans-serif"
                                            }
                                        }
                                    },
                                    tooltip: {
                                        titleFont: {
                                            family: "'Prompt', sans-serif"
                                        },
                                        bodyFont: {
                                            family: "'Prompt', sans-serif"
                                        }
                                    }
                                },
                                ...options
                            }
                        });
                    } catch (e) {
                        console.error(`Error creating chart ${id}:`, e);
                    }
                };

                // 1. Damage Bar Chart
                const damageData = @json(array_values($dashboard['damageByCategory']));
                const damageLabels = ['อาคาร', 'ครุภัณฑ์', 'วัสดุ'];

                createChart('damageBarChart', 'bar', {
                    labels: damageLabels,
                    datasets: [{
                        label: 'มูลค่าความเสียหาย (บาท)',
                        data: damageData,
                        backgroundColor: ['#0d6efd', '#20c997', '#ffc107'],
                        borderWidth: 1
                    }]
                }, {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('th-TH', {
                                        notation: "compact"
                                    }).format(value);
                                },
                                font: {
                                    family: "'Prompt', sans-serif"
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    family: "'Prompt', sans-serif"
                                }
                            }
                        }
                    }
                });

                // 2. Disaster Type Pie Chart
                const typeData = @json(array_values($dashboard['disasterTypeTotals']->toArray()));
                const typeLabels = @json(array_keys($dashboard['disasterTypeTotals']->toArray()));

                createChart('disasterPieChart', 'pie', {
                    labels: typeLabels,
                    datasets: [{
                        data: typeData,
                        backgroundColor: [
                            '#0d6efd', '#6610f2', '#d63384', '#fd7e14', '#198754', '#20c997',
                            '#0dcaf0', '#ffc107'
                        ],
                        borderWidth: 1
                    }]
                });

                // 3. Timeline Line Chart
                const timelineLabels = @json(array_keys($dashboard['timeline']->toArray()));
                const timelineData = @json(array_values($dashboard['timeline']->toArray()));

                createChart('timelineChart', 'line', {
                    labels: timelineLabels,
                    datasets: [{
                        label: 'จำนวนเหตุการณ์',
                        data: timelineData,
                        fill: true,
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderColor: '#0d6efd',
                        borderWidth: 2,
                        tension: 0, // Linear lines
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#0d6efd',
                        pointBorderWidth: 2
                    }]
                }, {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    family: "'Prompt', sans-serif"
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    family: "'Prompt', sans-serif"
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#000',
                            bodyColor: '#000',
                            borderColor: '#dee2e6',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return `จำนวน: ${context.parsed.y} เหตุการณ์`;
                                }
                            }
                        }
                    }
                });

                // 4. Status Donut Chart
                const statusLabels = @json(array_keys($dashboard['statusBreakdown']->toArray()));
                const statusData = @json(array_values($dashboard['statusBreakdown']->toArray()));

                // Map status to colors
                const statusColors = statusLabels.map(status => {
                    if (status === 'เสร็จสิ้น') return '#198754'; // Success
                    if (status === 'กำลังดำเนินการ') return '#ffc107'; // Warning
                    if (status === 'รอดำเนินการ') return '#dc3545'; // Danger
                    return '#0dcaf0'; // Info/Default
                });

                createChart('statusDonutChart', 'doughnut', {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: statusColors,
                        borderWidth: 1
                    }]
                });

                // 5. Teaching Status Chart
                const teachingDataRaw = @json($dashboard['teachingStatus']);
                const teachingLabels = Object.keys(teachingDataRaw).map(k => k === 'open' ? 'เปิดเรียนปกติ' :
                    'ปิดเรียน');
                const teachingData = Object.values(teachingDataRaw);

                createChart('teachingStatusChart', 'pie', {
                    labels: teachingLabels,
                    datasets: [{
                        data: teachingData,
                        backgroundColor: ['#198754', '#dc3545'],
                        borderWidth: 1
                    }]
                });

                // 6. Human Impact Chart (Grouped Bar)
                const humanImpact = @json($dashboard['humanImpact']);

                createChart('humanImpactChart', 'bar', {
                    labels: ['นักเรียน', 'บุคลากร'],
                    datasets: [{
                            label: 'บาดเจ็บ',
                            data: [humanImpact.students.injured, humanImpact.staff.injured],
                            backgroundColor: '#ffc107'
                        },
                        {
                            label: 'เสียชีวิต',
                            data: [humanImpact.students.dead, humanImpact.staff.dead],
                            backgroundColor: '#dc3545'
                        }
                    ]
                }, {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                });

                // 7. Damage by District (Horizontal Bar)
                const districtDataRaw = @json($dashboard['damageByDistrict']);
                const districtLabels = Object.keys(districtDataRaw);
                const districtData = Object.values(districtDataRaw);

                createChart('damageDistrictChart', 'bar', {
                    labels: districtLabels,
                    datasets: [{
                        label: 'มูลค่าความเสียหาย',
                        data: districtData,
                        backgroundColor: '#0d6efd',
                        borderRadius: 4
                    }]
                }, {
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('th-TH', {
                                        notation: "compact"
                                    }).format(value);
                                }
                            }
                        }
                    }
                });

                // 8. Reports by Affiliation (Horizontal Bar)
                const affiliationDataRaw = @json($dashboard['reportsByAffiliation']);
                const affiliationLabels = Object.keys(affiliationDataRaw);
                const affiliationData = Object.values(affiliationDataRaw);

                createChart('affiliationChart', 'bar', {
                    labels: affiliationLabels,
                    datasets: [{
                        label: 'จำนวนรายงาน',
                        data: affiliationData,
                        backgroundColor: '#20c997',
                        borderRadius: 4
                    }]
                }, {
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                });

                // Create Sparkline Charts for Stat Cards (Single Line)
                const createSparkline = (id, data, color) => {
                    const canvas = document.getElementById(id);
                    if (!canvas) return;

                    try {
                        new Chart(canvas, {
                            type: 'line',
                            data: {
                                labels: data.map((_, i) => ''),
                                datasets: [{
                                    data: data,
                                    borderColor: color,
                                    backgroundColor: `${color}20`,
                                    borderWidth: 2,
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 0,
                                    pointHoverRadius: 4,
                                    pointHoverBackgroundColor: color,
                                    pointHoverBorderColor: '#fff',
                                    pointHoverBorderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        enabled: true,
                                        displayColors: false,
                                        backgroundColor: 'rgba(0, 0, 0, 0.6)',
                                        titleColor: '#fff',
                                        bodyColor: '#fff',
                                        borderColor: 'rgba(255,255,255,0.08)',
                                        borderWidth: 1,
                                        callbacks: {
                                            title: () => '',
                                            label: (context) => `จำนวน: ${context.parsed.y}`
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        display: false
                                    },
                                    y: {
                                        display: false,
                                        beginAtZero: true
                                    }
                                },
                                interaction: {
                                    mode: 'index',
                                    intersect: false
                                }
                            }
                        });
                    } catch (e) {
                        console.error(`Error creating sparkline ${id}:`, e);
                    }
                };

                // Create Multi-Line Sparkline Charts (2+ Lines)
                const createMultiSparkline = (id, datasets) => {
                    const canvas = document.getElementById(id);
                    if (!canvas) return;

                    try {
                        const chartDatasets = datasets.map(dataset => ({
                            label: dataset.label,
                            data: dataset.data,
                            borderColor: dataset.color,
                            backgroundColor: `${dataset.color}20`,
                            borderWidth: 2,
                            fill: dataset.fill !== false,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 4,
                            pointHoverBackgroundColor: dataset.color,
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 2
                        }));

                        new Chart(canvas, {
                            type: 'line',
                            data: {
                                labels: datasets[0].data.map((_, i) => ''),
                                datasets: chartDatasets
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'bottom',
                                        labels: {
                                            boxWidth: 10,
                                            boxHeight: 2,
                                            color: '#fff',
                                            padding: 8,
                                            font: {
                                                size: 9,
                                                family: "'Prompt', sans-serif"
                                            },
                                            usePointStyle: true,
                                            pointStyle: 'line'
                                        }
                                    },
                                    tooltip: {
                                        enabled: true,
                                        displayColors: true,
                                        backgroundColor: 'rgba(0,0,0,0.6)',
                                        titleColor: '#fff',
                                        bodyColor: '#fff',
                                        borderColor: 'rgba(255,255,255,0.08)',
                                        borderWidth: 1,
                                        padding: 10,
                                        callbacks: {
                                            title: () => '',
                                            label: (context) =>
                                                `${context.dataset.label}: ${context.parsed.y}`
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        display: false
                                    },
                                    y: {
                                        display: false,
                                        beginAtZero: true
                                    }
                                },
                                interaction: {
                                    mode: 'index',
                                    intersect: false
                                }
                            }
                        });
                    } catch (e) {
                        console.error(`Error creating multi-sparkline ${id}:`, e);
                    }
                };

                // Get sparkline data from backend (real data from database)
                const sparklines = @json($dashboard['sparklines'] ?? []);
                console.log('Sparklines data:', sparklines);

                // Check if sparklines data is available
                if (sparklines && Object.keys(sparklines).length > 0) {
                    // 1. Affected Institutions - จำนวนสถานศึกษาที่รายงานข้อมูลต่อวัน
                    if (sparklines.affected_institutions) {
                        createSparkline('sparkline1', sparklines.affected_institutions, '#0d6efd');
                    }

                    // 2. Closed Institutions - จำนวนที่ปิดเรียนต่อวัน
                    if (sparklines.closed_institutions) {
                        createSparkline('sparkline2', sparklines.closed_institutions, '#dc3545');
                    }

                    // 3. Students Affected - แสดง 2 เส้น: affected และ dead
                    if (sparklines.students_affected && sparklines.students_dead) {
                        createMultiSparkline('sparkline3', [{
                                label: 'ได้รับผลกระทบ',
                                data: sparklines.students_affected,
                                color: '#ffc107',
                                fill: true
                            },
                            {
                                label: 'เสียชีวิต',
                                data: sparklines.students_dead,
                                color: '#dc3545',
                                fill: false
                            }
                        ]);
                    }

                    // 4. Staff Affected - แสดง 2 เส้น: affected และ dead
                    if (sparklines.staff_affected && sparklines.staff_dead) {
                        createMultiSparkline('sparkline4', [{
                                label: 'ได้รับผลกระทบ',
                                data: sparklines.staff_affected,
                                color: '#0dcaf0',
                                fill: true
                            },
                            {
                                label: 'เสียชีวิต',
                                data: sparklines.staff_dead,
                                color: '#dc3545',
                                fill: false
                            }
                        ]);
                    }

                    // 5. Damage - แสดง 3 เส้น: building + equipment + material
                    if (sparklines.damage_building && sparklines.damage_equipment && sparklines.damage_material) {
                        createMultiSparkline('sparkline5', [{
                                label: 'อาคาร',
                                data: sparklines.damage_building.map(v => v / 1000000),
                                color: '#0d6efd',
                                fill: true
                            },
                            {
                                label: 'ครุภัณฑ์',
                                data: sparklines.damage_equipment.map(v => v / 1000000),
                                color: '#20c997',
                                fill: true
                            },
                            {
                                label: 'วัสดุ',
                                data: sparklines.damage_material.map(v => v / 1000000),
                                color: '#ffc107',
                                fill: true
                            }
                        ]);
                    }

                    // 6. Shelters - จำนวนศูนย์พักพิงที่ลงทะเบียนต่อวัน
                    if (sparklines.shelters) {
                        createSparkline('sparkline6', sparklines.shelters, '#198754');
                    }

                    // 7. Kitchens - จำนวนโรงครัวที่ลงทะเบียนต่อวัน
                    if (sparklines.kitchens) {
                        createSparkline('sparkline7', sparklines.kitchens, '#fd7e14');
                    }

                    // 8. Severe Impact - จำนวนผลกระทบร้ายแรงต่อวัน
                    if (sparklines.severe_impact) {
                        createSparkline('sparkline8', sparklines.severe_impact, '#212529');
                    }
                } else {
                    console.warn('Sparklines data not available or empty');
                }

                // 9. Leaflet Map
                if (typeof L !== 'undefined') {
                    const mapElement = document.getElementById('dashboard-map');
                    if (mapElement) {
                        // Default view: Yala Province
                        const map = L.map('dashboard-map').setView([6.541, 101.281], 9);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 18,
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);

                        const points = @json($dashboard['mapPoints']);

                        // Create Marker Cluster Group
                        const markers = L.markerClusterGroup({
                            showCoverageOnHover: true,
                            zoomToBoundsOnClick: true,
                            maxClusterRadius: 50, // Cluster radius in pixels
                            iconCreateFunction: function(cluster) {
                                const childCount = cluster.getChildCount();
                                let c = ' marker-cluster-';
                                if (childCount < 10) {
                                    c += 'small';
                                } else if (childCount < 100) {
                                    c += 'medium';
                                } else {
                                    c += 'large';
                                }

                                return new L.DivIcon({
                                    html: '<div><span>' + childCount + '</span></div>',
                                    className: 'marker-cluster' + c,
                                    iconSize: new L.Point(60, 60)
                                });
                            }
                        });

                        points.forEach(point => {
                            if (point.lat && point.lng) {
                                // Determine color based on damage severity
                                let pinColor = '#198754'; // Green (Low)
                                let severityClass = 'text-success';

                                if (point.damage > 1000000) {
                                    pinColor = '#dc3545'; // Red (High)
                                    severityClass = 'text-danger';
                                } else if (point.damage > 100000) {
                                    pinColor = '#fd7e14'; // Orange (Medium)
                                    severityClass = 'text-warning';
                                }

                                // Create custom icon
                                const customIcon = L.divIcon({
                                    className: 'custom-pin',
                                    html: `<i class="bi bi-geo-alt-fill" style="color: ${pinColor}; font-size: 2rem; filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3));"></i>`,
                                    iconSize: [32, 32],
                                    iconAnchor: [16, 32],
                                    popupAnchor: [0, -32]
                                });

                                const marker = L.marker([point.lat, point.lng], {
                                    icon: customIcon
                                });

                                const popupContent = `
                                <div class="p-2">
                                    <h6 class="fw-bold mb-1">${point.organization}</h6>
                                    <span class="badge bg-${point.status === 'เสร็จสิ้น' ? 'success' : (point.status === 'กำลังดำเนินการ' ? 'warning' : 'danger')} mb-2">
                                        ${point.status}
                                    </span>
                                    <div class="small text-muted">
                                        มูลค่าความเสียหาย: <span class="fw-bold ${severityClass}">${new Intl.NumberFormat('th-TH').format(point.damage)}</span> บาท
                                    </div>
                                </div>
                            `;

                                marker.bindPopup(popupContent);

                                // Tooltip
                                const tooltipContent = `
                                <div class="text-center">
                                    <strong>${point.organization}</strong><br>
                                    <small class="${severityClass}">เสียหาย: ${new Intl.NumberFormat('th-TH', { notation: "compact" }).format(point.damage)} บ.</small>
                                </div>
                            `;

                                marker.bindTooltip(tooltipContent, {
                                    permanent: false,
                                    direction: 'top',
                                    className: 'custom-tooltip'
                                });

                                markers.addLayer(marker);
                            }
                        });

                        map.addLayer(markers);

                        // Add Shelters Markers
                        const shelters = @json($shelters ?? []);
                        shelters.forEach(shelter => {
                            if (shelter.latitude && shelter.longitude) {
                                const shelterIcon = L.divIcon({
                                    className: 'custom-pin-shelter',
                                    html: `<i class="bi bi-house-heart-fill" style="color: #198754; font-size: 2rem; filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3));"></i>`,
                                    iconSize: [32, 32],
                                    iconAnchor: [16, 32],
                                    popupAnchor: [0, -32]
                                });

                                const marker = L.marker([shelter.latitude, shelter.longitude], {
                                        icon: shelterIcon
                                    })
                                    .addTo(map);

                                const popupContent = `
                                <div class="p-2">
                                    <h6 class="fw-bold mb-1 text-success"><i class="bi bi-house-heart-fill me-1"></i> ${shelter.name}</h6>
                                    <div class="small text-muted">
                                        ความจุ: <span class="fw-bold text-dark">${new Intl.NumberFormat('th-TH').format(shelter.capacity)}</span> คน
                                    </div>
                                    <div class="mt-2">
                                        <a href="https://www.google.com/maps/search/?api=1&query=${shelter.latitude},${shelter.longitude}" target="_blank" class="btn btn-sm btn-outline-success w-100">
                                            <i class="bi bi-geo-alt"></i> นำทาง
                                        </a>
                                    </div>
                                </div>
                            `;

                                marker.bindPopup(popupContent);
                                marker.bindTooltip(`<strong>${shelter.name}</strong>`, {
                                    permanent: false,
                                    direction: 'top',
                                    className: 'custom-tooltip'
                                });
                            }
                        });
                    }
                } else {
                    console.error('Leaflet library not loaded');
                }
            });
        </script>
    @endpush
