@extends('layouts.app')

@section('content')
    <div class="text-center mb-4">
        <h1 class="h3 mb-2">บันทึกข้อมูลสำเร็จ</h1>
        <p class="text-muted">ระบบได้บันทึกรายงานของ <strong>{{ $report->organization_name }}</strong> เรียบร้อยแล้ว</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <p class="text-muted">สแกน QR Code เพื่อเปิดดูรายละเอียดรายงาน</p>
                    <div class="d-inline-block bg-white p-3 border rounded">
                        {!! $qrCodeSvg !!}
                    </div>
                    <p class="mt-3 mb-0"><a href="{{ $shareUrl }}" target="_blank" rel="noopener">{{ $shareUrl }}</a></p>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">สรุปข้อมูลรายงาน</h2>
                    <dl class="row mb-0">
                        <dt class="col-sm-5">วันที่รายงาน</dt>
                        <dd class="col-sm-7">{{ optional($report->reported_at)->format('d/m/Y H:i') }}</dd>
                        <dt class="col-sm-5">ประเภทภัยพิบัติ</dt>
                        <dd class="col-sm-7">{{ $report->disaster_type }}</dd>
                        <dt class="col-sm-5">สถานการณ์</dt>
                        <dd class="col-sm-7">{{ $report->current_status }}</dd>
                        <dt class="col-sm-5">มูลค่าความเสียหายรวม</dt>
                        <dd class="col-sm-7">{{ number_format((float) $report->damage_total_request, 2) }} บาท</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('disaster.index') }}" class="btn btn-outline-secondary">กลับหน้ารายการ</a>
                <a href="{{ route('disaster.create') }}" class="btn btn-primary">บันทึกฉบับใหม่</a>
            </div>
        </div>
    </div>
@endsection
