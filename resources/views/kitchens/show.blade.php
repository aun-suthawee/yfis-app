@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1 fw-bold text-primary-custom">{{ $kitchen->name }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('kitchens.index') }}">รายงานโรงครัว</a></li>
                <li class="breadcrumb-item active" aria-current="page">รายละเอียด</li>
            </ol>
        </nav>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <h5 class="text-primary mb-3"><i class="bi bi-info-circle me-2"></i>ข้อมูลทั่วไป</h5>
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">ชื่อโรงครัว</th>
                            <td>{{ $kitchen->name }}</td>
                        </tr>
                        <tr>
                            <th>อำเภอ</th>
                            <td>{{ $kitchen->district->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>สังกัด</th>
                            <td>{{ $kitchen->affiliation->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>สถานะ</th>
                            <td>
                                @if($kitchen->status === 'open')
                                    <span class="badge bg-success">เปิดให้บริการ</span>
                                @else
                                    <span class="badge bg-secondary">ปิด</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="text-primary mb-3"><i class="bi bi-person me-2"></i>ข้อมูลติดต่อ</h5>
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">ผู้ประสานงาน</th>
                            <td>{{ $kitchen->contact_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>เบอร์โทรศัพท์</th>
                            <td>{{ $kitchen->contact_phone ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-12">
                    <h5 class="text-primary mb-3"><i class="bi bi-ui-checks me-2"></i>สิ่งอำนวยความสะดวก</h5>
                    @if($kitchen->facilities)
                        <div class="d-flex flex-wrap gap-2">
                            @if($kitchen->facilities['water'] ?? false)
                                <span class="badge bg-primary fs-6"><i class="bi bi-droplet-fill me-1"></i> น้ำดื่ม</span>
                            @endif
                            @if($kitchen->facilities['food'] ?? false)
                                <span class="badge bg-warning fs-6"><i class="bi bi-cup-hot-fill me-1"></i> อาหาร</span>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">ไม่มีข้อมูล</p>
                    @endif
                </div>

                @if($kitchen->notes)
                    <div class="col-12">
                        <h5 class="text-primary mb-3"><i class="bi bi-sticky me-2"></i>หมายเหตุ</h5>
                        <p class="text-muted">{{ $kitchen->notes }}</p>
                    </div>
                @endif

                <!-- Production Quantities -->
                <div class="col-12">
                    <h5 class="text-primary mb-3"><i class="bi bi-box-seam me-2"></i>ปริมาณที่มี</h5>
                    <div class="row text-center">
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <i class="bi bi-droplet-fill text-primary fs-1"></i>
                                    <h3 class="mt-2 mb-0">{{ number_format($kitchen->water_bottles ?? 0) }}</h3>
                                    <small class="text-muted">ขวดน้ำดื่ม</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <i class="bi bi-cup-hot-fill text-warning fs-1"></i>
                                    <h3 class="mt-2 mb-0">{{ number_format($kitchen->food_boxes ?? 0) }}</h3>
                                    <small class="text-muted">กล่องอาหาร</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($kitchen->latitude && $kitchen->longitude)
                    <div class="col-12">
                        <h5 class="text-primary mb-3"><i class="bi bi-geo-alt me-2"></i>ตำแหน่งที่ตั้ง</h5>
                        <div id="map" style="height: 400px;" class="rounded border"></div>
                        <p class="text-muted mt-2 small">
                            <i class="bi bi-pin-map"></i> {{ $kitchen->latitude }}, {{ $kitchen->longitude }}
                        </p>
                    </div>
                @endif

                <div class="col-12 text-end">
                    <a href="{{ route('kitchens.index') }}" class="btn btn-secondary">กลับ</a>
                    @can('update', $kitchen)
                        <a href="{{ route('kitchens.edit', $kitchen) }}" class="btn btn-warning">แก้ไข</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection

@if($kitchen->latitude && $kitchen->longitude)
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var lat = {{ $kitchen->latitude }};
                var lng = {{ $kitchen->longitude }};
                
                var map = L.map('map').setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                L.marker([lat, lng]).addTo(map)
                    .bindPopup('<b>{{ $kitchen->name }}</b>')
                    .openPopup();
            });
            
            // Edit production function
            window.editProduction = function(id, date, water, food, notes) {
                const row = document.getElementById('prod-row-' + id);
                const formHtml = `
                    <td colspan="5">
                        <form action="{{ route('kitchens.productions.update', [$kitchen, '']) }}/${id}" method="POST" class="row g-2">
                            @csrf
                            @method('PUT')
                            <div class="col-md-2">
                                <input type="date" name="production_date" class="form-control form-control-sm" value="${date}" max="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="water_bottles" class="form-control form-control-sm" value="${water}" min="0" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="food_boxes" class="form-control form-control-sm" value="${food}" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="notes" class="form-control form-control-sm" value="${notes}" maxlength="500" placeholder="หมายเหตุ">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-sm btn-success me-1"><i class="bi bi-check"></i></button>
                                <button type="button" class="btn btn-sm btn-secondary" onclick="location.reload()"><i class="bi bi-x"></i></button>
                            </div>
                        </form>
                    </td>
                `;
                row.innerHTML = formHtml;
            };
        </script>
    @endpush
@endif
