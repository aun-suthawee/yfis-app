@props([
    'action',
    'method' => 'POST',
    'report' => null,
    'districts',
    'affiliations',
    'submitLabel' => 'บันทึกข้อมูล',
])
@php
    $formId = $attributes->get('id', 'disaster-report-form');
    $httpMethod = strtoupper($method);
    $isGet = $httpMethod === 'GET';
@endphp

<form id="{{ $formId }}" action="{{ $action }}" method="POST" class="needs-validation" novalidate>
    @csrf
    @unless(in_array($httpMethod, ['GET', 'POST'], true))
        @method($httpMethod)
    @endunless

    <!-- Section 1: Event & Organization -->
    <div class="row g-4 mb-4">
        <!-- Event Info -->
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3 border-bottom-0 rounded-top-4">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-exclamation-triangle-fill me-2"></i>ข้อมูลเหตุการณ์</h5>
                </div>
                <div class="card-body pt-0">
                    {{-- reported_at removed as per request --}}
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold" for="disaster_type_select">ประเภทภัยพิบัติ <span class="text-danger">*</span></label>
                        @php
                            $disasterTypes = ['น้ำท่วม', 'ไฟไหม้', 'ดินโคลนถล่ม', 'ลมพายุ'];
                            $currentType = old('disaster_type', $report->disaster_type ?? '');
                            $isCustom = !empty($currentType) && !in_array($currentType, $disasterTypes);
                        @endphp
                        <select class="form-select bg-light border-0" id="disaster_type_select" required>
                            <option value="" disabled {{ empty($currentType) ? 'selected' : '' }}>เลือกประเภทภัยพิบัติ</option>
                            @foreach($disasterTypes as $type)
                                <option value="{{ $type }}" {{ $currentType == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                            <option value="other" {{ $isCustom ? 'selected' : '' }}>อื่นๆ (ระบุ)</option>
                        </select>
                        <input type="text" class="form-control mt-2 bg-light border-0" id="disaster_type_other" placeholder="ระบุประเภทภัยพิบัติ" value="{{ $isCustom ? $currentType : '' }}" style="{{ $isCustom ? '' : 'display: none;' }}">
                        <x-input-error name="disaster_type" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold" for="current_status_select">สถานการณ์ปัจจุบัน <span class="text-danger">*</span></label>
                        @php
                            $statusTypes = ['กำลังประสบภัย', 'สถานการณ์คลี่คลาย', 'เข้าสู่ภาวะปกติ', 'เฝ้าระวัง'];
                            $currentStatus = old('current_status', $report->current_status ?? '');
                            $isCustomStatus = !empty($currentStatus) && !in_array($currentStatus, $statusTypes);
                        @endphp
                        <select class="form-select bg-light border-0" id="current_status_select" required>
                            <option value="" disabled {{ empty($currentStatus) ? 'selected' : '' }}>เลือกสถานการณ์</option>
                            @foreach($statusTypes as $status)
                                <option value="{{ $status }}" {{ $currentStatus == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                            <option value="other" {{ $isCustomStatus ? 'selected' : '' }}>อื่นๆ (ระบุ)</option>
                        </select>
                        <input type="text" class="form-control mt-2 bg-light border-0" id="current_status_other" placeholder="ระบุสถานการณ์" value="{{ $isCustomStatus ? $currentStatus : '' }}" style="{{ $isCustomStatus ? '' : 'display: none;' }}">
                        <x-input-error name="current_status" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Organization Info -->
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3 border-bottom-0 rounded-top-4">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-building-fill me-2"></i>ข้อมูลหน่วยงาน</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold" for="organization_name">ชื่อหน่วยงาน / สถานศึกษา <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light border-0" id="organization_name" name="organization_name" value="{{ old('organization_name', $report->organization_name ?? '') }}" required placeholder="ระบุชื่อโรงเรียนหรือหน่วยงาน">
                        <x-input-error name="organization_name" />
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold" for="district_id">อำเภอ <span class="text-danger">*</span></label>
                            <select class="form-select bg-light border-0" id="district_id" name="district_id" required>
                                <option value="">เลือกอำเภอ</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" @selected(old('district_id', $report->district_id ?? '') == $district->id)>{{ $district->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error name="district_id" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold" for="affiliation_id">สังกัด <span class="text-danger">*</span></label>
                            <select class="form-select bg-light border-0" id="affiliation_id" name="affiliation_id" required>
                                <option value="">เลือกสังกัด</option>
                                @foreach($affiliations as $affiliation)
                                    <option value="{{ $affiliation->id }}" @selected(old('affiliation_id', $report->affiliation_id ?? '') == $affiliation->id)>{{ $affiliation->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error name="affiliation_id" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold" for="teaching_status">สถานะการเรียนการสอน <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-0" id="teaching_status" name="teaching_status" required>
                            <option value="">เลือกสถานะ</option>
                            <option value="open" @selected(old('teaching_status', $report->teaching_status ?? '') === 'open')>เปิดเรียนตามปกติ</option>
                            <option value="closed" @selected(old('teaching_status', $report->teaching_status ?? '') === 'closed')>ปิดเรียน</option>
                        </select>
                        <x-input-error name="teaching_status" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Impact -->
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-header bg-white py-3 border-bottom-0 rounded-top-4">
            <h5 class="mb-0 fw-bold text-danger"><i class="bi bi-people-fill me-2"></i>ข้อมูลผู้ได้รับผลกระทบ</h5>
        </div>
        <div class="card-body pt-0">
            <div class="row g-4">
                <!-- Students -->
                <div class="col-md-6 border-end-md">
                    <h6 class="fw-bold text-secondary mb-3 bg-light p-2 rounded"><i class="bi bi-backpack me-2"></i>นักเรียน</h6>
                    <div class="row g-3">
                        <div class="col-4">
                            <label class="form-label text-muted small fw-bold">ได้รับผลกระทบ</label>
                            <input type="number" min="0" class="form-control text-center fw-bold text-primary" id="affected_students" name="affected_students" value="{{ old('affected_students', $report->affected_students ?? 0) }}" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label text-muted small fw-bold">บาดเจ็บ</label>
                            <input type="number" min="0" class="form-control text-center fw-bold text-warning" id="injured_students" name="injured_students" value="{{ old('injured_students', $report->injured_students ?? 0) }}" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label text-muted small fw-bold">เสียชีวิต</label>
                            <input type="number" min="0" class="form-control text-center fw-bold text-danger" id="dead_students" name="dead_students" value="{{ old('dead_students', $report->dead_students ?? 0) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">รายชื่อผู้เสียชีวิต (ถ้ามี)</label>
                            <textarea class="form-control bg-light border-0" rows="2" id="dead_students_list" name="dead_students_list" placeholder="ระบุชื่อ-นามสกุล">{{ old('dead_students_list', $report->dead_students_list ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Staff -->
                <div class="col-md-6">
                    <h6 class="fw-bold text-secondary mb-3 bg-light p-2 rounded"><i class="bi bi-person-badge me-2"></i>บุคลากร</h6>
                    <div class="row g-3">
                        <div class="col-4">
                            <label class="form-label text-muted small fw-bold">ได้รับผลกระทบ</label>
                            <input type="number" min="0" class="form-control text-center fw-bold text-primary" id="affected_staff" name="affected_staff" value="{{ old('affected_staff', $report->affected_staff ?? 0) }}" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label text-muted small fw-bold">บาดเจ็บ</label>
                            <input type="number" min="0" class="form-control text-center fw-bold text-warning" id="injured_staff" name="injured_staff" value="{{ old('injured_staff', $report->injured_staff ?? 0) }}" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label text-muted small fw-bold">เสียชีวิต</label>
                            <input type="number" min="0" class="form-control text-center fw-bold text-danger" id="dead_staff" name="dead_staff" value="{{ old('dead_staff', $report->dead_staff ?? 0) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">รายชื่อผู้เสียชีวิต (ถ้ามี)</label>
                            <textarea class="form-control bg-light border-0" rows="2" id="dead_staff_list" name="dead_staff_list" placeholder="ระบุชื่อ-นามสกุล">{{ old('dead_staff_list', $report->dead_staff_list ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Damage Estimates -->
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-header bg-white py-3 border-bottom-0 rounded-top-4">
            <h5 class="mb-0 fw-bold text-warning"><i class="bi bi-currency-exchange me-2"></i>ประมาณการความเสียหาย</h5>
        </div>
        <div class="card-body pt-0">
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold" for="damage_building">อาคารสถานที่ (บาท)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">฿</span>
                        <input type="number" step="0.01" min="0" class="form-control bg-light border-0" id="damage_building" name="damage_building" value="{{ old('damage_building', number_format((float) ($report->damage_building ?? 0), 2, '.', '')) }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold" for="damage_equipment">ครุภัณฑ์การศึกษา (บาท)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">฿</span>
                        <input type="number" step="0.01" min="0" class="form-control bg-light border-0" id="damage_equipment" name="damage_equipment" value="{{ old('damage_equipment', number_format((float) ($report->damage_equipment ?? 0), 2, '.', '')) }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold" for="damage_material">วัสดุอุปกรณ์ (บาท)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">฿</span>
                        <input type="number" step="0.01" min="0" class="form-control bg-light border-0" id="damage_material" name="damage_material" value="{{ old('damage_material', number_format((float) ($report->damage_material ?? 0), 2, '.', '')) }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold" for="damage_total_request">ขอรับการสนับสนุน (บาท)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-warning border-0 text-dark">฿</span>
                        <input type="number" step="0.01" min="0" class="form-control bg-warning bg-opacity-10 border-0 fw-bold" id="damage_total_request" name="damage_total_request" value="{{ old('damage_total_request', number_format((float) ($report->damage_total_request ?? 0), 2, '.', '')) }}" required>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold" for="assistance_received">การให้ความช่วยเหลือเยียวยาเบื้องต้น (ถ้ามี)</label>
                <textarea class="form-control bg-light border-0" rows="3" id="assistance_received" name="assistance_received" placeholder="ระบุรายละเอียดการช่วยเหลือที่ได้รับแล้ว">{{ old('assistance_received', $report->assistance_received ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Section 4: Contact Info -->
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-header bg-white py-3 border-bottom-0 rounded-top-4">
            <h5 class="mb-0 fw-bold text-info"><i class="bi bi-person-lines-fill me-2"></i>ข้อมูลผู้ประสานงาน</h5>
        </div>
        <div class="card-body pt-0">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-bold" for="contact_name">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-light border-0" id="contact_name" name="contact_name" value="{{ old('contact_name', $report->contact_name ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-bold" for="contact_position">ตำแหน่ง <span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-light border-0" id="contact_position" name="contact_position" value="{{ old('contact_position', $report->contact_position ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-bold" for="contact_phone">เบอร์โทรศัพท์ <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control bg-light border-0" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $report->contact_phone ?? '') }}" required pattern="[0-9+\-\s]{7,15}">
                </div>
            </div>
        </div>
    </div>

    <!-- Section 5: Location & Map -->
    <div class="card shadow-sm border-0 mb-5 rounded-4">
        <div class="card-header bg-white py-3 border-bottom-0 rounded-top-4">
            <h5 class="mb-0 fw-bold text-success"><i class="bi bi-geo-alt-fill me-2"></i>พิกัดสถานที่</h5>
        </div>
        <div class="card-body pt-0">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-bold" for="latitude">ละติจูด</label>
                    <input type="number" step="0.0000001" class="form-control bg-light border-0" id="latitude" name="latitude" value="{{ old('latitude', $report->latitude ?? '') }}" min="-90" max="90" placeholder="เช่น 6.54321">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-bold" for="longitude">ลองจิจูด</label>
                    <input type="number" step="0.0000001" class="form-control bg-light border-0" id="longitude" name="longitude" value="{{ old('longitude', $report->longitude ?? '') }}" min="-180" max="180" placeholder="เช่น 101.12345">
                </div>
            </div>
            
            <div class="mb-3 d-flex align-items-center flex-wrap gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" onclick="getCurrentLocation()">
                    <i class="bi bi-crosshair me-1"></i> ใช้ตำแหน่งปัจจุบัน
                </button>
                <small class="text-muted"><i class="bi bi-info-circle me-1"></i>คลิกบนแผนที่เพื่อปักหมุด</small>
            </div>

            <!-- Map Container -->
            <div id="map" style="height: 400px; width: 100%; border-radius: 16px; z-index: 1;" class="shadow-sm border"></div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="d-lg-none mb-5">
        <button type="button" class="btn btn-primary w-100 btn-lg rounded-pill shadow-sm" data-submit>
            <i class="bi bi-save me-2"></i>{{ $submitLabel }}
        </button>
    </div>

    <div class="d-none d-lg-flex justify-content-end mb-5">
        <button type="button" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm" data-submit>
            <i class="bi bi-save me-2"></i>{{ $submitLabel }}
        </button>
    </div>

</form>

<div class="modal fade" id="formConfirmModal" tabindex="-1" aria-labelledby="formConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="formConfirmLabel">ยืนยันการบันทึก</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-check-circle text-success display-1 mb-3"></i>
                <p class="mb-0 text-muted">ตรวจสอบความถูกต้องของข้อมูลก่อนยืนยันการบันทึก<br>หากข้อมูลถูกต้องให้กด "ยืนยัน" เพื่อดำเนินการต่อ</p>
            </div>
            <div class="modal-footer border-top-0 justify-content-center pb-4">
                <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary px-4 rounded-pill" data-confirm>{{ $submitLabel }}</button>
            </div>
        </div>
    </div>
</div>

@once
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Map Logic
                let map, marker;
                const defaultLat = 6.541147; // Yala City Center
                const defaultLng = 101.280394;
                
                function initMap() {
                    const latInput = document.getElementById('latitude');
                    const lngInput = document.getElementById('longitude');
                    
                    // Check if map container exists
                    if (!document.getElementById('map')) return;

                    let lat = parseFloat(latInput.value) || defaultLat;
                    let lng = parseFloat(lngInput.value) || defaultLng;
                    let zoomLevel = (latInput.value && lngInput.value) ? 16 : 13;
                    
                    map = L.map('map').setView([lat, lng], zoomLevel);
                    
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                        maxZoom: 19
                    }).addTo(map);

                    // Add marker if value exists
                    if (latInput.value && lngInput.value) {
                        marker = L.marker([lat, lng], {draggable: true}).addTo(map);
                        marker.on('dragend', function(event) {
                            const position = marker.getLatLng();
                            updateInputs(position.lat, position.lng);
                        });
                    }

                    // Map click event
                    map.on('click', function(e) {
                        const lat = e.latlng.lat;
                        const lng = e.latlng.lng;
                        
                        if (marker) {
                            marker.setLatLng([lat, lng]);
                        } else {
                            marker = L.marker([lat, lng], {draggable: true}).addTo(map);
                            marker.on('dragend', function(event) {
                                const position = marker.getLatLng();
                                updateInputs(position.lat, position.lng);
                            });
                        }
                        updateInputs(lat, lng);
                    });
                }

                function updateInputs(lat, lng) {
                    document.getElementById('latitude').value = lat.toFixed(6);
                    document.getElementById('longitude').value = lng.toFixed(6);
                }

                window.getCurrentLocation = function() {
                    if (navigator.geolocation) {
                        const btn = document.querySelector('button[onclick="getCurrentLocation()"]');
                        const originalText = btn.innerHTML;
                        btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> กำลังระบุตำแหน่ง...';
                        btn.disabled = true;

                        navigator.geolocation.getCurrentPosition(function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            map.setView([lat, lng], 16);
                            if (marker) {
                                marker.setLatLng([lat, lng]);
                            } else {
                                marker = L.marker([lat, lng], {draggable: true}).addTo(map);
                                marker.on('dragend', function(event) {
                                    const position = marker.getLatLng();
                                    updateInputs(position.lat, position.lng);
                                });
                            }
                            updateInputs(lat, lng);
                            
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        }, function(error) {
                            alert("ไม่สามารถระบุตำแหน่งได้: " + error.message);
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        });
                    } else {
                        alert("เบราว์เซอร์ของคุณไม่รองรับการระบุตำแหน่ง");
                    }
                }

                // Initialize map
                initMap();

                // Disaster Type Logic
                const dtSelect = document.getElementById('disaster_type_select');
                const dtOther = document.getElementById('disaster_type_other');
                
                function updateDisasterTypeInput() {
                    if (dtSelect.value === 'other') {
                        dtOther.style.display = 'block';
                        dtOther.required = true;
                        dtOther.name = 'disaster_type';
                        dtSelect.removeAttribute('name');
                    } else {
                        dtOther.style.display = 'none';
                        dtOther.required = false;
                        dtOther.removeAttribute('name');
                        dtSelect.name = 'disaster_type';
                    }
                }

                if (dtSelect && dtOther) {
                    dtSelect.addEventListener('change', updateDisasterTypeInput);
                    // Initial state
                    updateDisasterTypeInput();
                }

                // Current Status Logic
                const csSelect = document.getElementById('current_status_select');
                const csOther = document.getElementById('current_status_other');

                function updateCurrentStatusInput() {
                    if (csSelect.value === 'other') {
                        csOther.style.display = 'block';
                        csOther.required = true;
                        csOther.name = 'current_status';
                        csSelect.removeAttribute('name');
                    } else {
                        csOther.style.display = 'none';
                        csOther.required = false;
                        csOther.removeAttribute('name');
                        csSelect.name = 'current_status';
                    }
                }

                if (csSelect && csOther) {
                    csSelect.addEventListener('change', updateCurrentStatusInput);
                    // Initial state
                    updateCurrentStatusInput();
                }

                const form = document.getElementById('{{ $formId }}');
                const submitTriggers = form.querySelectorAll('[data-submit]');
                const confirmTrigger = document.querySelector('#formConfirmModal [data-confirm]');
                const confirmModal = new bootstrap.Modal(document.getElementById('formConfirmModal'));

                submitTriggers.forEach(trigger => {
                    trigger.addEventListener('click', () => {
                        if (form.checkValidity()) {
                            confirmModal.show();
                        } else {
                            form.classList.add('was-validated');
                            // Scroll to first invalid input
                            const firstInvalid = form.querySelector(':invalid');
                            if (firstInvalid) {
                                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                firstInvalid.focus();
                            }
                        }
                    });
                });

                confirmTrigger.addEventListener('click', () => {
                    confirmModal.hide();
                    form.submit();
                });
            });
        </script>
        <style>
            .border-end-md {
                border-right: 1px solid #dee2e6;
            }
            @media (max-width: 767.98px) {
                .border-end-md {
                    border-right: none;
                    border-bottom: 1px solid #dee2e6;
                    padding-bottom: 1.5rem;
                    margin-bottom: 1.5rem;
                }
            }
            .form-control:focus, .form-select:focus {
                border-color: var(--bs-primary);
                box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.1);
                background-color: #fff;
            }
        </style>
    @endpush
@endonce
