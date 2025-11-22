@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1 fw-bold text-primary-custom">รายงานโรงครัว</h1>
        <p class="text-muted mb-0">จัดการข้อมูลโรงครัวสำหรับสนับสนุนผู้ประสบภัย</p>
    </div>

    @if(auth()->user()?->hasAnyRole(['admin', 'data-entry', 'yfis']))
        <div class="mb-3 d-flex justify-content-end">
            <a href="{{ route('kitchens.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-2"></i> เพิ่มโรงครัว
            </a>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if($kitchens->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p>ยังไม่มีข้อมูลโรงครัว</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ชื่อโรงครัว</th>
                                <th>อำเภอ</th>
                                <th>สังกัด</th>
                                <th>สถานะ</th>
                                <th>สิ่งอำนวยความสะดวก</th>
                                <th>ผู้ประสานงาน</th>
                                <th class="text-end">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kitchens as $kitchen)
                                <tr>
                                    <td class="fw-bold">{{ $kitchen->name }}</td>
                                    <td>{{ $kitchen->district->name ?? '-' }}</td>
                                    <td>{{ $kitchen->affiliation->name ?? '-' }}</td>
                                    <td>
                                        @if($kitchen->status === 'open')
                                            <span class="badge bg-success">เปิดให้บริการ</span>
                                        @else
                                            <span class="badge bg-secondary">ปิด</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($kitchen->facilities)
                                            @php
                                                $facilityIcons = [
                                                    'water' => ['icon' => 'bi-droplet-fill', 'label' => 'น้ำดื่ม'],
                                                    'food' => ['icon' => 'bi-cup-hot-fill', 'label' => 'อาหาร'],
                                                ];
                                            @endphp
                                            <div class="d-flex gap-2">
                                                @foreach($facilityIcons as $key => $facility)
                                                    @if($kitchen->facilities[$key] ?? false)
                                                        <span class="badge bg-info" title="{{ $facility['label'] }}">
                                                            <i class="bi {{ $facility['icon'] }}"></i> {{ $facility['label'] }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($kitchen->contact_name)
                                            {{ $kitchen->contact_name }}
                                            @if($kitchen->contact_phone)
                                                <br><small class="text-muted">{{ $kitchen->contact_phone }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('kitchens.show', $kitchen) }}" class="btn btn-outline-primary" title="ดูรายละเอียด">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @can('update', $kitchen)
                                                <a href="{{ route('kitchens.edit', $kitchen) }}" class="btn btn-outline-warning" title="แก้ไข">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan
                                            @can('delete', $kitchen)
                                                <form action="{{ route('kitchens.destroy', $kitchen) }}" method="POST" class="d-inline" onsubmit="return confirm('ต้องการลบข้อมูลนี้?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="ลบ">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $kitchens->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
