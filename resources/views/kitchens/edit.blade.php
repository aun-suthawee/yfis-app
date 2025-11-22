@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1 fw-bold text-primary-custom">แก้ไขโรงครัว</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('kitchens.index') }}">รายงานโรงครัว</a></li>
                <li class="breadcrumb-item active" aria-current="page">แก้ไขข้อมูล</li>
            </ol>
        </nav>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('kitchens.update', $kitchen) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">ชื่อโรงครัว / สถานที่ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $kitchen->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label for="district_id" class="form-label">อำเภอ <span class="text-danger">*</span></label>
                        <select class="form-select @error('district_id') is-invalid @enderror" id="district_id" name="district_id" required>
                            <option value="">เลือกอำเภอ</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ old('district_id', $kitchen->district_id) == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                            @endforeach
                        </select>
                        @error('district_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="affiliation_id" class="form-label">สังกัด <span class="text-danger">*</span></label>
                        <select class="form-select @error('affiliation_id') is-invalid @enderror" id="affiliation_id" name="affiliation_id" required>
                            <option value="">เลือกสังกัด</option>
                            @foreach($affiliations as $affiliation)
                                <option value="{{ $affiliation->id }}" {{ old('affiliation_id', $kitchen->affiliation_id) == $affiliation->id ? 'selected' : '' }}>{{ $affiliation->name }}</option>
                            @endforeach
                        </select>
                        @error('affiliation_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="status" class="form-label">สถานะ <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="closed" {{ old('status', $kitchen->status) == 'closed' ? 'selected' : '' }}>ปิด</option>
                            <option value="open" {{ old('status', $kitchen->status) == 'open' ? 'selected' : '' }}>เปิดให้บริการ</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="contact_name" class="form-label">ชื่อผู้ประสานงาน</label>
                        <input type="text" class="form-control @error('contact_name') is-invalid @enderror" id="contact_name" name="contact_name" value="{{ old('contact_name', $kitchen->contact_name) }}">
                    </div>

                    <div class="col-md-3">
                        <label for="contact_phone" class="form-label">เบอร์โทรศัพท์</label>
                        <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $kitchen->contact_phone) }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">สิ่งอำนวยความสะดวก</label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="facilities_water" name="facilities[water]" {{ old('facilities.water', $kitchen->facilities['water'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="facilities_water">
                                        <i class="bi bi-droplet-fill text-primary"></i> น้ำดื่ม
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="facilities_food" name="facilities[food]" {{ old('facilities.food', $kitchen->facilities['food'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="facilities_food">
                                        <i class="bi bi-cup-hot-fill text-warning"></i> อาหาร
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="water_bottles" class="form-label">ปริมาณน้ำดื่ม (ขวด)</label>
                        <input type="number" class="form-control @error('water_bottles') is-invalid @enderror" id="water_bottles" name="water_bottles" value="{{ old('water_bottles', $kitchen->water_bottles ?? 0) }}" min="0">
                        @error('water_bottles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="food_boxes" class="form-label">ปริมาณอาหาร (กล่อง)</label>
                        <input type="number" class="form-control @error('food_boxes') is-invalid @enderror" id="food_boxes" name="food_boxes" value="{{ old('food_boxes', $kitchen->food_boxes ?? 0) }}" min="0">
                        @error('food_boxes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">หมายเหตุ</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $kitchen->notes) }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label">ปักหมุดตำแหน่ง</label>
                        <div class="input-group mb-2 position-relative">
                            <input type="text" id="location-search" class="form-control" placeholder="พิมพ์ชื่อสถานที่เพื่อค้นหา" autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" id="btn-search">ค้นหา</button>
                            <div id="search-suggestions" class="list-group position-absolute w-100 shadow-sm" style="top: 100%; z-index: 1000; display: none; max-height: 200px; overflow-y: auto;"></div>
                        </div>
                        <div id="map" style="height: 400px;" class="rounded border"></div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="latitude" class="form-label small text-muted">ละติจูด</label>
                                <input type="text" class="form-control form-control-sm bg-light" id="latitude" name="latitude" value="{{ old('latitude', $kitchen->latitude) }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="longitude" class="form-label small text-muted">ลองจิจูด</label>
                                <input type="text" class="form-control form-control-sm bg-light" id="longitude" name="longitude" value="{{ old('longitude', $kitchen->longitude) }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <a href="{{ route('kitchens.index') }}" class="btn btn-secondary me-2">ยกเลิก</a>
                        <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var oldLat = parseFloat("{{ old('latitude', $kitchen->latitude) }}") || 6.541147;
            var oldLng = parseFloat("{{ old('longitude', $kitchen->longitude) }}") || 101.280393;
            var hasCoords = "{{ $kitchen->latitude }}" && "{{ $kitchen->longitude }}";
            
            var map = L.map('map').setView([oldLat, oldLng], hasCoords ? 13 : 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var marker;
            if (hasCoords) {
                marker = L.marker([oldLat, oldLng]).addTo(map);
            }

            map.on('click', function(e) {
                if (marker) map.removeLayer(marker);
                marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
                document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
                document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
            });

            function updateMap(lat, lon) {
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lon]).addTo(map);
                map.setView([lat, lon], 16);
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lon.toFixed(6);
            }

            const searchInput = document.getElementById('location-search');
            const suggestionsBox = document.getElementById('search-suggestions');
            let debounceTimer;

            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value;
                if (query.length < 3) {
                    suggestionsBox.style.display = 'none';
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&countrycodes=th&accept-language=th`)
                        .then(response => response.json())
                        .then(data => {
                            suggestionsBox.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(item => {
                                    const a = document.createElement('a');
                                    a.href = '#';
                                    a.className = 'list-group-item list-group-item-action';
                                    a.textContent = item.display_name;
                                    a.onclick = function(e) {
                                        e.preventDefault();
                                        searchInput.value = item.display_name;
                                        suggestionsBox.style.display = 'none';
                                        updateMap(parseFloat(item.lat), parseFloat(item.lon));
                                    };
                                    suggestionsBox.appendChild(a);
                                });
                                suggestionsBox.style.display = 'block';
                            } else {
                                suggestionsBox.style.display = 'none';
                            }
                        })
                        .catch(console.error);
                }, 800);
            });

            document.addEventListener('click', function(e) {
                if (e.target !== searchInput && e.target !== suggestionsBox) {
                    suggestionsBox.style.display = 'none';
                }
            });

            document.getElementById('btn-search').addEventListener('click', function() {
                var query = searchInput.value;
                if (!query) return;

                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=th&accept-language=th`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            updateMap(parseFloat(data[0].lat), parseFloat(data[0].lon));
                            suggestionsBox.style.display = 'none';
                        } else {
                            alert('ไม่พบสถานที่ที่ค้นหา');
                        }
                    })
                    .catch(error => alert('เกิดข้อผิดพลาดในการค้นหา'));
            });
        });
    </script>
@endpush
