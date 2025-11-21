@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1 fw-bold text-primary-custom">เพิ่มศูนย์พักพิง</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('shelters.index') }}">ศูนย์พักพิง</a></li>
                <li class="breadcrumb-item active" aria-current="page">เพิ่มข้อมูล</li>
            </ol>
        </nav>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('shelters.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">ชื่อศูนย์พักพิง <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="capacity" class="form-label">จำนวนที่รองรับได้ (คน) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', 0) }}" min="0" required>
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">ปักหมุดตำแหน่ง (ค้นหา หรือ คลิกบนแผนที่)</label>
                        <div class="input-group mb-2 position-relative">
                            <input type="text" id="location-search" class="form-control" placeholder="พิมพ์ชื่อสถานที่เพื่อค้นหา (เช่น โรงเรียนคณะราษฎรบำรุง จังหวัดยะลา)" autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" id="btn-search">ค้นหา</button>
                            <div id="search-suggestions" class="list-group position-absolute w-100 shadow-sm" style="top: 100%; z-index: 1000; display: none; max-height: 200px; overflow-y: auto;"></div>
                        </div>
                        <div id="map" style="height: 400px;" class="rounded border"></div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="latitude" class="form-label small text-muted">ละติจูด</label>
                                <input type="text" class="form-control form-control-sm bg-light" id="latitude" name="latitude" value="{{ old('latitude') }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="longitude" class="form-label small text-muted">ลองจิจูด</label>
                                <input type="text" class="form-control form-control-sm bg-light" id="longitude" name="longitude" value="{{ old('longitude') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <a href="{{ route('shelters.index') }}" class="btn btn-secondary me-2">ยกเลิก</a>
                        <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
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
            // Default to Yala coordinates
            var map = L.map('map').setView([6.541147, 101.280393], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var marker;

            // If old values exist, set marker
            var oldLat = "{{ old('latitude') }}";
            var oldLng = "{{ old('longitude') }}";
            if (oldLat && oldLng) {
                marker = L.marker([oldLat, oldLng]).addTo(map);
                map.setView([oldLat, oldLng], 13);
            }

            map.on('click', function(e) {
                var lat = e.latlng.lat;
                var lng = e.latlng.lng;

                if (marker) {
                    map.removeLayer(marker);
                }

                marker = L.marker([lat, lng]).addTo(map);
                
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);
            });

            // Helper to update map
            function updateMap(lat, lon) {
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lon]).addTo(map);
                map.setView([lat, lon], 16);
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lon.toFixed(6);
            }

            // 1. Auto-search from Shelter Name
            const nameInput = document.getElementById('name');
            let nameDebounce;
            nameInput.addEventListener('input', function() {
                clearTimeout(nameDebounce);
                const query = this.value;
                if (query.length < 3) return;

                nameDebounce = setTimeout(() => {
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&countrycodes=th&accept-language=th`)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.length > 0) {
                                updateMap(parseFloat(data[0].lat), parseFloat(data[0].lon));
                            }
                        })
                        .catch(console.error);
                }, 1000); // 1s delay for name typing
            });

            // 2. Search Box Functionality
            const searchInput = document.getElementById('location-search');
            const suggestionsBox = document.getElementById('search-suggestions');
            let debounceTimer;

            // Autocomplete & Auto-update
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
                                // Auto-update map to the first result
                                updateMap(parseFloat(data[0].lat), parseFloat(data[0].lon));

                                // Show suggestions
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
                        .catch(error => console.error('Error fetching suggestions:', error));
                }, 800); // 800ms debounce
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (e.target !== searchInput && e.target !== suggestionsBox) {
                    suggestionsBox.style.display = 'none';
                }
            });

            document.getElementById('btn-search').addEventListener('click', function() {
                var query = document.getElementById('location-search').value;
                if (!query) return;

                fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query) + '&countrycodes=th&accept-language=th')
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            var lat = parseFloat(data[0].lat);
                            var lon = parseFloat(data[0].lon);

                            if (marker) {
                                map.removeLayer(marker);
                            }

                            marker = L.marker([lat, lon]).addTo(map);
                            map.setView([lat, lon], 16);

                            document.getElementById('latitude').value = lat.toFixed(6);
                            document.getElementById('longitude').value = lon.toFixed(6);
                            suggestionsBox.style.display = 'none';
                        } else {
                            alert('ไม่พบสถานที่ที่ค้นหา');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('เกิดข้อผิดพลาดในการค้นหา');
                    });
            });
        });
    </script>
@endpush
