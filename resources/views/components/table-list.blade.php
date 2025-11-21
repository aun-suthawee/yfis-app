@props([
    'reports',
    'showActions' => null,
])
@php($canManage = auth()->user()?->hasAnyRole(['admin', 'data-entry']))
@php($displayActions = $showActions ?? $canManage)
@php($isAdmin = auth()->user()?->hasRole('admin'))

<form id="bulk-action-form" method="POST" action="{{ route('disaster.bulk-publish') }}">
    @csrf
    <div class="card shadow-sm">
        @if($isAdmin)
            <div class="card-header bg-light py-2">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select-all">
                        <label class="form-check-label small text-muted" for="select-all">เลือกทั้งหมด</label>
                    </div>
                    <div class="vr mx-2 d-none d-lg-block"></div>
                    <div class="d-flex gap-2 w-100 w-lg-auto mt-2 mt-lg-0">
                        <button type="submit" name="action" value="publish" class="btn btn-success btn-sm flex-fill flex-lg-grow-0" onclick="return confirm('ยืนยันการเผยแพร่รายการที่เลือก?')">
                            <i class="bi bi-eye me-1"></i> เผยแพร่
                        </button>
                        <button type="submit" name="action" value="unpublish" class="btn btn-warning btn-sm flex-fill flex-lg-grow-0" onclick="return confirm('ยืนยันการยกเลิกเผยแพร่รายการที่เลือก?')">
                            <i class="bi bi-eye-slash me-1"></i> ยกเลิกเผยแพร่
                        </button>
                    </div>
                </div>
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        @if($isAdmin)
                            <th style="width: 40px;"></th>
                        @endif
                        <th>วันที่รายงาน</th>
                        <th>ประเภทภัยพิบัติ</th>
                        <th>หน่วยงาน</th>
                        <th>อำเภอ</th>
                        <th>สถานะ</th>
                        <th>นักเรียนได้รับผลกระทบ</th>
                        <th>บุคลากรได้รับผลกระทบ</th>
                        <th>ความเสียหายรวม (บาท)</th>
                        @if($displayActions)
                            <th class="text-end">การจัดการ</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr class="{{ $report->is_published ? 'table-success' : '' }}">
                            @if($isAdmin)
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input report-checkbox" type="checkbox" name="selected_reports[]" value="{{ $report->id }}">
                                    </div>
                                </td>
                            @endif
                            <td>{{ optional($report->reported_at)->format('d/m/Y H:i') }}</td>
                            <td>{{ $report->disaster_type }}</td>
                            <td>{{ $report->organization_name }}</td>
                            <td>{{ $report->district?->name }}</td>
                            <td>
                                @if($report->is_published)
                                    <span class="badge bg-success">เผยแพร่แล้ว</span>
                                @else
                                    <span class="badge bg-warning text-dark">รอการเผยแพร่</span>
                                @endif
                            </td>
                            <td>{{ number_format($report->affected_students) }} / {{ number_format($report->injured_students) }} / {{ number_format($report->dead_students) }}</td>
                            <td>{{ number_format($report->affected_staff) }} / {{ number_format($report->injured_staff) }} / {{ number_format($report->dead_staff) }}</td>
                            <td>{{ number_format((float) $report->damage_total_request, 2) }}</td>
                            @if($displayActions)
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if($isAdmin)
                                            @if($report->is_published)
                                                <button type="submit" form="unpublish-form-{{ $report->id }}" class="btn btn-outline-warning" title="ยกเลิกการเผยแพร่">
                                                    <i class="bi bi-eye-slash"></i>
                                                </button>
                                            @else
                                                <button type="submit" form="publish-form-{{ $report->id }}" class="btn btn-outline-success" title="เผยแพร่">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            @endif
                                        @endif
                                        @if($canManage)
                                            <a href="{{ route('disaster.edit', $report) }}" class="btn btn-outline-primary" title="แก้ไข">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="submit" form="delete-form-{{ $report->id }}" class="btn btn-outline-danger" title="ลบ" onclick="return confirm('ยืนยันการลบข้อมูลนี้หรือไม่?');">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $displayActions ? ($isAdmin ? 9 : 8) : ($isAdmin ? 8 : 7) }}" class="text-center text-muted py-4">ไม่พบข้อมูล</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($reports, 'hasPages') && $reports->hasPages())
            <div class="card-footer">
                {{ $reports->withQueryString()->links() }}
            </div>
        @endif
    </div>
</form>

{{-- Hidden forms for individual actions to avoid nesting forms --}}
@foreach($reports as $report)
    @if($isAdmin)
        <form id="publish-form-{{ $report->id }}" method="POST" action="{{ route('disaster.publish', $report) }}" class="d-none">@csrf</form>
        <form id="unpublish-form-{{ $report->id }}" method="POST" action="{{ route('disaster.unpublish', $report) }}" class="d-none">@csrf</form>
    @endif
    @if($canManage)
        <form id="delete-form-{{ $report->id }}" method="POST" action="{{ route('disaster.destroy', $report) }}" class="d-none">@csrf @method('DELETE')</form>
    @endif
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.report-checkbox');

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
        }
    });
</script>
