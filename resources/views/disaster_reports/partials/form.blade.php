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

<form id="{{ $formId }}" action="{{ $action }}" method="POST" class="card shadow-sm needs-validation" novalidate>
    @csrf
    @unless(in_array($httpMethod, ['GET', 'POST'], true))
        @method($httpMethod)
    @endunless
    <div class="card-body">
        <h2 class="h5 mb-3 fw-bold text-primary-custom"><i class="bi bi-exclamation-triangle me-2"></i>ข้อมูลเหตุการณ์</h2>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label" for="reported_at">วันที่และเวลาที่รายงาน *</label>
                <input type="datetime-local" class="form-control" id="reported_at" name="reported_at" value="{{ old('reported_at', optional($report?->reported_at)->format('Y-m-d\TH:i')) }}" required>
                <x-input-error name="reported_at" />
            </div>
            <div class="col-md-4">
                <label class="form-label" for="disaster_type_select">ประเภทภัยพิบัติ *</label>
                @php
                    $disasterTypes = ['น้ำท่วม', 'ไฟไหม้', 'ดินโคลนถล่ม', 'ลมพายุ'];
                    $currentType = old('disaster_type', $report->disaster_type ?? '');
                    $isCustom = !empty($currentType) && !in_array($currentType, $disasterTypes);
                @endphp
                <select class="form-select" id="disaster_type_select" required>
                    <option value="" disabled {{ empty($currentType) ? 'selected' : '' }}>เลือกประเภทภัยพิบัติ</option>
                    @foreach($disasterTypes as $type)
                        <option value="{{ $type }}" {{ $currentType == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                    <option value="other" {{ $isCustom ? 'selected' : '' }}>อื่นๆ (ระบุ)</option>
                </select>
                <input type="text" class="form-control mt-2" id="disaster_type_other" placeholder="ระบุประเภทภัยพิบัติ" value="{{ $isCustom ? $currentType : '' }}" style="{{ $isCustom ? '' : 'display: none;' }}">
                <x-input-error name="disaster_type" />
            </div>
            <div class="col-md-4">
                <label class="form-label" for="current_status_select">สถานการณ์ปัจจุบัน *</label>
                @php
                    $statusTypes = ['กำลังประสบภัย', 'สถานการณ์คลี่คลาย', 'เข้าสู่ภาวะปกติ', 'เฝ้าระวัง'];
                    $currentStatus = old('current_status', $report->current_status ?? '');
                    $isCustomStatus = !empty($currentStatus) && !in_array($currentStatus, $statusTypes);
                @endphp
                <select class="form-select" id="current_status_select" required>
                    <option value="" disabled {{ empty($currentStatus) ? 'selected' : '' }}>เลือกสถานการณ์</option>
                    @foreach($statusTypes as $status)
                        <option value="{{ $status }}" {{ $currentStatus == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                    <option value="other" {{ $isCustomStatus ? 'selected' : '' }}>อื่นๆ (ระบุ)</option>
                </select>
                <input type="text" class="form-control mt-2" id="current_status_other" placeholder="ระบุสถานการณ์" value="{{ $isCustomStatus ? $currentStatus : '' }}" style="{{ $isCustomStatus ? '' : 'display: none;' }}">
                <x-input-error name="current_status" />
            </div>
        </div>

        <hr class="my-4">
        <h2 class="h5 mb-3 fw-bold text-primary-custom"><i class="bi bi-building me-2"></i>ข้อมูลหน่วยงาน</h2>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="organization_name">ชื่อหน่วยงาน / สถานศึกษา *</label>
                <input type="text" class="form-control" id="organization_name" name="organization_name" value="{{ old('organization_name', $report->organization_name ?? '') }}" required>
                <x-input-error name="organization_name" />
            </div>
            <div class="col-md-3">
                <label class="form-label" for="district_id">อำเภอ *</label>
                <select class="form-select" id="district_id" name="district_id" required>
                    <option value="">เลือกอำเภอ</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}" @selected(old('district_id', $report->district_id ?? '') == $district->id)>{{ $district->name }}</option>
                    @endforeach
                </select>
                <x-input-error name="district_id" />
            </div>
            <div class="col-md-3">
                <label class="form-label" for="affiliation_id">สังกัด *</label>
                <select class="form-select" id="affiliation_id" name="affiliation_id" required>
                    <option value="">เลือกสังกัด</option>
                    @foreach($affiliations as $affiliation)
                        <option value="{{ $affiliation->id }}" @selected(old('affiliation_id', $report->affiliation_id ?? '') == $affiliation->id)>{{ $affiliation->name }}</option>
                    @endforeach
                </select>
                <x-input-error name="affiliation_id" />
            </div>
            <div class="col-md-3">
                <label class="form-label" for="teaching_status">สถานะการเรียนการสอน *</label>
                <select class="form-select" id="teaching_status" name="teaching_status" required>
                    <option value="">เลือกสถานะ</option>
                    <option value="open" @selected(old('teaching_status', $report->teaching_status ?? '') === 'open')>เปิดเรียนตามปกติ</option>
                    <option value="closed" @selected(old('teaching_status', $report->teaching_status ?? '') === 'closed')>ปิดเรียน</option>
                </select>
                <x-input-error name="teaching_status" />
            </div>
        </div>

        <hr class="my-4">
        <h2 class="h5 mb-3">ผลกระทบต่อนักเรียน</h2>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label" for="affected_students">ได้รับผลกระทบ *</label>
                <input type="number" min="0" class="form-control" id="affected_students" name="affected_students" value="{{ old('affected_students', $report->affected_students ?? 0) }}" required>
                <x-input-error name="affected_students" />
            </div>
            <div class="col-md-4">
                <label class="form-label" for="injured_students">บาดเจ็บ *</label>
                <input type="number" min="0" class="form-control" id="injured_students" name="injured_students" value="{{ old('injured_students', $report->injured_students ?? 0) }}" required>
                <x-input-error name="injured_students" />
            </div>
            <div class="col-md-4">
                <label class="form-label" for="dead_students">เสียชีวิต *</label>
                <input type="number" min="0" class="form-control" id="dead_students" name="dead_students" value="{{ old('dead_students', $report->dead_students ?? 0) }}" required>
                <x-input-error name="dead_students" />
            </div>
            <div class="col-12">
                <label class="form-label" for="dead_students_list">รายชื่อผู้เสียชีวิต (ถ้ามี)</label>
                <textarea class="form-control" rows="3" id="dead_students_list" name="dead_students_list">{{ old('dead_students_list', $report->dead_students_list ?? '') }}</textarea>
                <x-input-error name="dead_students_list" />
            </div>
        </div>

        <hr class="my-4">
        <h2 class="h5 mb-3">ผลกระทบต่อบุคลากร</h2>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label" for="affected_staff">ได้รับผลกระทบ *</label>
                <input type="number" min="0" class="form-control" id="affected_staff" name="affected_staff" value="{{ old('affected_staff', $report->affected_staff ?? 0) }}" required>
                <x-input-error name="affected_staff" />
            </div>
            <div class="col-md-4">
                <label class="form-label" for="injured_staff">บาดเจ็บ *</label>
                <input type="number" min="0" class="form-control" id="injured_staff" name="injured_staff" value="{{ old('injured_staff', $report->injured_staff ?? 0) }}" required>
                <x-input-error name="injured_staff" />
            </div>
            <div class="col-md-4">
                <label class="form-label" for="dead_staff">เสียชีวิต *</label>
                <input type="number" min="0" class="form-control" id="dead_staff" name="dead_staff" value="{{ old('dead_staff', $report->dead_staff ?? 0) }}" required>
                <x-input-error name="dead_staff" />
            </div>
            <div class="col-12">
                <label class="form-label" for="dead_staff_list">รายชื่อผู้เสียชีวิต (ถ้ามี)</label>
                <textarea class="form-control" rows="3" id="dead_staff_list" name="dead_staff_list">{{ old('dead_staff_list', $report->dead_staff_list ?? '') }}</textarea>
                <x-input-error name="dead_staff_list" />
            </div>
        </div>

        <hr class="my-4">
        <h2 class="h5 mb-3">ประมาณการความเสียหาย</h2>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label" for="damage_building">อาคารสถานที่ *</label>
                <input type="number" step="0.01" min="0" class="form-control" id="damage_building" name="damage_building" value="{{ old('damage_building', number_format((float) ($report->damage_building ?? 0), 2, '.', '')) }}" required>
                <x-input-error name="damage_building" />
            </div>
            <div class="col-md-3">
                <label class="form-label" for="damage_equipment">ครุภัณฑ์การศึกษา *</label>
                <input type="number" step="0.01" min="0" class="form-control" id="damage_equipment" name="damage_equipment" value="{{ old('damage_equipment', number_format((float) ($report->damage_equipment ?? 0), 2, '.', '')) }}" required>
                <x-input-error name="damage_equipment" />
            </div>
            <div class="col-md-3">
                <label class="form-label" for="damage_material">วัสดุอุปกรณ์ *</label>
                <input type="number" step="0.01" min="0" class="form-control" id="damage_material" name="damage_material" value="{{ old('damage_material', number_format((float) ($report->damage_material ?? 0), 2, '.', '')) }}" required>
                <x-input-error name="damage_material" />
            </div>
            <div class="col-md-3">
                <label class="form-label" for="damage_total_request">รวมวงเงินที่ขอรับการสนับสนุน *</label>
                <input type="number" step="0.01" min="0" class="form-control" id="damage_total_request" name="damage_total_request" value="{{ old('damage_total_request', number_format((float) ($report->damage_total_request ?? 0), 2, '.', '')) }}" required>
                <x-input-error name="damage_total_request" />
            </div>
            <div class="col-12">
                <label class="form-label" for="assistance_received">การให้ความช่วยเหลือเยียวยา (ถ้ามี)</label>
                <textarea class="form-control" rows="4" id="assistance_received" name="assistance_received">{{ old('assistance_received', $report->assistance_received ?? '') }}</textarea>
                <x-input-error name="assistance_received" />
            </div>
        </div>

        <hr class="my-4">
        <h2 class="h5 mb-3 fw-bold text-primary-custom"><i class="bi bi-person-lines-fill me-2"></i>ข้อมูลการติดต่อ</h2>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label" for="contact_name">ผู้ประสานงาน *</label>
                <input type="text" class="form-control" id="contact_name" name="contact_name" value="{{ old('contact_name', $report->contact_name ?? '') }}" required>
                <x-input-error name="contact_name" />
            </div>
            <div class="col-md-4">
                <label class="form-label" for="contact_position">ตำแหน่ง *</label>
                <input type="text" class="form-control" id="contact_position" name="contact_position" value="{{ old('contact_position', $report->contact_position ?? '') }}" required>
                <x-input-error name="contact_position" />
            </div>
            <div class="col-md-4">
                <label class="form-label" for="contact_phone">เบอร์โทรศัพท์ *</label>
                <input type="tel" class="form-control" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $report->contact_phone ?? '') }}" required pattern="[0-9+\-\s]{7,15}">
                <x-input-error name="contact_phone" />
            </div>
        </div>

        <hr class="my-4">
        <h2 class="h5 mb-3 fw-bold text-primary-custom"><i class="bi bi-geo-alt me-2"></i>พิกัดสถานที่ (ถ้ามี)</h2>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="latitude">ละติจูด</label>
                <input type="number" step="0.0000001" class="form-control" id="latitude" name="latitude" value="{{ old('latitude', $report->latitude ?? '') }}" min="-90" max="90">
                <x-input-error name="latitude" />
            </div>
            <div class="col-md-6">
                <label class="form-label" for="longitude">ลองจิจูด</label>
                <input type="number" step="0.0000001" class="form-control" id="longitude" name="longitude" value="{{ old('longitude', $report->longitude ?? '') }}" min="-180" max="180">
                <x-input-error name="longitude" />
            </div>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center bg-white py-3">
        <small class="text-muted"><i class="bi bi-info-circle me-1"></i>ระบบจะป้องกันการส่งข้อมูลซ้ำโดยอัตโนมัติ</small>
        <button type="button" class="btn btn-primary px-4" data-submit>
            <i class="bi bi-save me-2"></i>{{ $submitLabel }}
        </button>
    </div>
</form>

<div class="modal fade" id="formConfirmModal" tabindex="-1" aria-labelledby="formConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formConfirmLabel">ยืนยันการบันทึก</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ตรวจสอบความถูกต้องของข้อมูลก่อนยืนยันการบันทึก หากข้อมูลถูกต้องให้กด "ยืนยัน" เพื่อดำเนินการต่อ
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" data-confirm>{{ $submitLabel }}</button>
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
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
                const submitTrigger = form.querySelector('[data-submit]');
                const confirmTrigger = document.querySelector('#formConfirmModal [data-confirm]');
                const confirmModal = new bootstrap.Modal(document.getElementById('formConfirmModal'));

                submitTrigger.addEventListener('click', () => {
                    if (form.checkValidity()) {
                        confirmModal.show();
                    } else {
                        form.classList.add('was-validated');
                    }
                });

                confirmTrigger.addEventListener('click', () => {
                    confirmModal.hide();
                    form.submit();
                });
            });
        </script>
    @endpush
@endonce
