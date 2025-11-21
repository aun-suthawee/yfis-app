@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1 fw-bold text-primary-custom">ข้อมูลศูนย์พักพิง</h1>
        <p class="text-muted mb-0">จัดการข้อมูลศูนย์พักพิงและจุดอพยพ</p>
    </div>

    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('shelters.create') }}" class="btn btn-primary btn-sm d-flex align-items-center">
            <i class="bi bi-plus-lg me-2"></i> เพิ่มศูนย์พักพิง
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ชื่อศูนย์พักพิง</th>
                        <th>พิกัด (Lat, Long)</th>
                        <th>ความจุ (คน)</th>
                        <th class="text-end">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shelters as $shelter)
                        <tr>
                            <td>{{ $shelter->name }}</td>
                            <td>
                                @if($shelter->latitude && $shelter->longitude)
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $shelter->latitude }},{{ $shelter->longitude }}" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-geo-alt-fill text-danger"></i> {{ number_format($shelter->latitude, 6) }}, {{ number_format($shelter->longitude, 6) }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ number_format($shelter->capacity) }}</td>
                            <td class="text-end">
                                <a href="{{ route('shelters.edit', $shelter) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('shelters.destroy', $shelter) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันการลบข้อมูล?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">ไม่พบข้อมูลศูนย์พักพิง</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($shelters->hasPages())
            <div class="card-footer bg-white">
                {{ $shelters->links() }}
            </div>
        @endif
    </div>
@endsection
