@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-primary-custom">รายงานอุทกภัย</h1>
            <p class="text-muted mb-0">กรอกข้อมูลตามสถานการณ์จริงของหน่วยงานท่าน</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#shareModal">
                <i class="bi bi-share me-2"></i> แชร์แบบฟอร์ม
            </button>
            {{-- <a href="{{ route('disaster.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i> กลับไปหน้ารายการ
            </a> --}}
        </div>
    </div>

    @include('disaster_reports.partials.form_modern', [
        'id' => 'create-report-form',
        'action' => route('disaster.store'),
        'districts' => $districts,
        'affiliations' => $affiliations,
        'submitLabel' => 'บันทึกข้อมูล',
    ])

    <!-- Share Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">แชร์แบบฟอร์มรายงาน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">ลิงก์สำหรับแบบฟอร์ม</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $url }}" id="shareUrl" readonly>
                            <button class="btn btn-outline-primary" type="button" onclick="copyShareUrl()" id="copyBtn">
                                <i class="bi bi-clipboard me-1"></i> คัดลอก
                            </button>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <label class="form-label fw-bold d-block mb-2">QR Code</label>
                        <div class="d-inline-block p-3 bg-white rounded border shadow-sm" id="qrCodeContainer">
                            {!! $qrCode !!}
                        </div>
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-dark btn-sm" onclick="downloadQrCode()">
                                <i class="bi bi-download me-1"></i> ดาวน์โหลด QR Code
                            </button>
                        </div>
                        <p class="text-muted mt-2 mb-0 small">สแกนเพื่อเข้าถึงแบบฟอร์มรายงานภัยพิบัติ</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyShareUrl() {
            const copyText = document.getElementById("shareUrl");
            const copyBtn = document.getElementById("copyBtn");
            const originalHtml = copyBtn.innerHTML;

            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices

            const successCallback = () => {
                copyBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> คัดลอกแล้ว';
                copyBtn.classList.remove('btn-outline-primary');
                copyBtn.classList.add('btn-success');
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalHtml;
                    copyBtn.classList.remove('btn-success');
                    copyBtn.classList.add('btn-outline-primary');
                }, 2000);
            };

            try {
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(copyText.value).then(successCallback);
                } else {
                    document.execCommand("copy");
                    successCallback();
                }
            } catch (err) {
                console.error('Failed to copy: ', err);
            }
        }

        function downloadQrCode() {
            const svg = document.querySelector('#qrCodeContainer svg');
            if (svg) {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const svgData = new XMLSerializer().serializeToString(svg);
                const img = new Image();
                
                // Set canvas size based on SVG size (add padding)
                const size = 400; // High resolution
                canvas.width = size;
                canvas.height = size;
                
                // Create a blob from the SVG data
                const svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
                const url = URL.createObjectURL(svgBlob);
                
                img.onload = function() {
                    // Fill white background
                    ctx.fillStyle = 'white';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    
                    // Draw image centered
                    ctx.drawImage(img, 0, 0, size, size);
                    
                    // Convert to PNG and download
                    const pngUrl = canvas.toDataURL('image/png');
                    const link = document.createElement('a');
                    link.href = pngUrl;
                    link.download = 'yfis-form-qrcode.png';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    // Cleanup
                    URL.revokeObjectURL(url);
                };
                
                img.src = url;
            }
        }
    </script>
    @endpush
@endsection
