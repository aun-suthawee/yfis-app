@props([
    'action',
    'method' => 'GET',
    'districts' => collect(),
    'affiliations' => collect(),
    'filters' => [],
    'showAffiliation' => true,
])
@php($httpMethod = strtolower($method))

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom-0">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold">
                <i class="bi bi-sliders me-2"></i>ตัวกรองข้อมูล
            </h5>
        </div>
    </div>
    <div class="card-body pt-0">
        <form method="{{ $httpMethod === 'get' ? 'GET' : 'POST' }}" action="{{ $action }}">
            @if($httpMethod !== 'get')
                @csrf
            @endif
            @if(!in_array($httpMethod, ['get', 'post'], true))
                @method($method)
            @endif

            <div class="row g-3">
                {{-- Search Text --}}
                <div class="col-md-4">
                    <label class="form-label text-muted small">ค้นหา</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="organization_name" class="form-control border-start-0 bg-light" 
                               value="{{ $filters['organization_name'] ?? '' }}" 
                               placeholder="ชื่อหน่วยงาน...">
                    </div>
                </div>

                {{-- Dropdowns Group --}}
                <div class="col-md-2">
                    <label class="form-label text-muted small">อำเภอ</label>
                    <select class="form-select" name="district_id">
                        <option value="">ทั้งหมด</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" @selected(($filters['district_id'] ?? '') == $district->id)>
                                {{ $district->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($showAffiliation)
                <div class="col-md-2">
                    <label class="form-label text-muted small">สังกัด</label>
                    <select class="form-select" name="affiliation_id">
                        <option value="">ทั้งหมด</option>
                        @foreach($affiliations as $affiliation)
                            <option value="{{ $affiliation->id }}" @selected(($filters['affiliation_id'] ?? '') == $affiliation->id)>
                                {{ $affiliation->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-2">
                    <label class="form-label text-muted small">สถานะการเรียน</label>
                    <select class="form-select" name="teaching_status">
                        <option value="">ทั้งหมด</option>
                        <option value="open" @selected(($filters['teaching_status'] ?? '') === 'open')>เปิดการสอน</option>
                        <option value="closed" @selected(($filters['teaching_status'] ?? '') === 'closed')>ปิดการสอน</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label text-muted small">ประเภทภัย</label>
                    <input type="text" name="disaster_type" class="form-control" 
                           value="{{ $filters['disaster_type'] ?? '' }}" placeholder="ระบุประเภท...">
                </div>

                <div class="col-md-2">
                    <label class="form-label text-muted small">สถานะเผยแพร่</label>
                    <select class="form-select" name="is_published">
                        <option value="">ทั้งหมด</option>
                        <option value="1" @selected(isset($filters['is_published']) && $filters['is_published'] == '1')>เผยแพร่แล้ว</option>
                        <option value="0" @selected(isset($filters['is_published']) && $filters['is_published'] == '0')>รอการเผยแพร่</option>
                    </select>
                </div>

                {{-- Date Range --}}
                <div class="col-md-2">
                    <label class="form-label text-muted small">ตั้งแต่วันที่</label>
                    <input type="date" name="reported_from" class="form-control" value="{{ $filters['reported_from'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small">ถึงวันที่</label>
                    <input type="date" name="reported_to" class="form-control" value="{{ $filters['reported_to'] ?? '' }}">
                </div>

                {{-- Action Buttons --}}
                <div class="col-md-8 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-search me-1"></i> ค้นหา
                    </button>
                    <a href="{{ request()->url() }}" class="btn btn-light text-muted border">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> ล้างค่า
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
