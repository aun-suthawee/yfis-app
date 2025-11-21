@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">รายละเอียดรายงานภัยพิบัติ</h1>
            <p class="text-muted mb-0">{{ $report->organization_name }} • {{ optional($report->reported_at)->format('d/m/Y H:i') }}</p>
        </div>
        <a href="{{ route('disaster.index') }}" class="btn btn-outline-secondary btn-sm">กลับไปหน้ารายการ</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5">ข้อมูลเหตุการณ์</h2>
                    <dl class="row">
                        <dt class="col-sm-4">ประเภทภัยพิบัติ</dt>
                        <dd class="col-sm-8">{{ $report->disaster_type }}</dd>
                        <dt class="col-sm-4">สถานการณ์ปัจจุบัน</dt>
                        <dd class="col-sm-8">{{ $report->current_status }}</dd>
                        <dt class="col-sm-4">สถานะการเรียนการสอน</dt>
                        <dd class="col-sm-8">{{ $report->teaching_status === 'open' ? 'เปิดเรียน' : 'ปิดเรียน' }}</dd>
                        <dt class="col-sm-4">วันที่และเวลาที่รายงาน</dt>
                        <dd class="col-sm-8">{{ optional($report->reported_at)->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5">ผลกระทบ</h2>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <p class="text-muted mb-1">นักเรียนได้รับผลกระทบ</p>
                            <h4>{{ number_format($report->affected_students) }}</h4>
                            <small class="text-muted">บาดเจ็บ {{ number_format($report->injured_students) }} / เสียชีวิต {{ number_format($report->dead_students) }}</small>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">บุคลากรได้รับผลกระทบ</p>
                            <h4>{{ number_format($report->affected_staff) }}</h4>
                            <small class="text-muted">บาดเจ็บ {{ number_format($report->injured_staff) }} / เสียชีวิต {{ number_format($report->dead_staff) }}</small>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">มูลค่าความเสียหายรวม</p>
                            <h4>{{ number_format((float) $report->damage_total_request, 2) }} บาท</h4>
                        </div>
                    </div>
                    @if($report->dead_students_list)
                        <hr>
                        <p class="mb-1 fw-semibold">รายชื่อนักเรียนเสียชีวิต</p>
                        <p class="text-muted">{{ $report->dead_students_list }}</p>
                    @endif
                    @if($report->dead_staff_list)
                        <hr>
                        <p class="mb-1 fw-semibold">รายชื่อบุคลากรเสียชีวิต</p>
                        <p class="text-muted">{{ $report->dead_staff_list }}</p>
                    @endif
                    @if($report->assistance_received)
                        <hr>
                        <p class="mb-1 fw-semibold">การให้ความช่วยเหลือเยียวยา</p>
                        <p class="text-muted">{{ $report->assistance_received }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5">ข้อมูลหน่วยงาน</h2>
                    <p class="mb-1"><strong>{{ $report->organization_name }}</strong></p>
                    <p class="text-muted mb-1">อำเภอ {{ $report->district?->name }}</p>
                    <p class="text-muted">สังกัด {{ $report->affiliation?->name }}</p>
                </div>
            </div>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5">การติดต่อ</h2>
                    <p class="mb-1">{{ $report->contact_name }}</p>
                    <p class="text-muted mb-1">{{ $report->contact_position }}</p>
                    <p class="text-muted">โทร: {{ $report->contact_phone }}</p>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5">พิกัดสถานที่</h2>
                    @if($report->latitude && $report->longitude)
                        <div id="report-map" style="height: 260px;"></div>
                        <p class="text-muted mt-2 mb-0">Lat {{ $report->latitude }}, Lng {{ $report->longitude }}</p>
                    @else
                        <p class="text-muted mb-0">ไม่มีข้อมูลพิกัด</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@if($report->latitude && $report->longitude)
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-sA+4J0rGUNShUMpOWcTnBPVK8GSdOHY1Psv0IB1Q30k=" crossorigin="" />
    @endpush
    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-o9N1j7M1yXv3lKTkPWqsK0p12ug1Lzsz5G8aHqvM/Z8=" crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const map = L.map('report-map').setView([{{ $report->latitude }}, {{ $report->longitude }}], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                L.marker([{{ $report->latitude }}, {{ $report->longitude }}]).addTo(map)
                    .bindPopup(`{{ addslashes($report->organization_name) }}`)
                    .openPopup();
            });
        </script>
    @endpush
@endif
