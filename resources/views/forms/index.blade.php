<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'YFIS') }} - เลือกแบบฟอร์ม</title>
    
    <!-- Fonts & Styles -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <style>
        :root {
            --color-primary: #3674B5;
            --color-bg: #F9F8F6;
        }
        body {
            font-family: 'Prompt', sans-serif;
            background-color: var(--color-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }
        .transition-all {
            transition: all 0.3s ease;
        }
        .empty-state-icon {
            font-size: 5rem;
            color: #ccc;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            @if(empty($forms))
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-cloud-rain-heavy text-secondary" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="text-muted fw-bold mb-3">ระบบยังไม่เปิดรับแจ้งข้อมูลในขณะนี้</h2>
                    <p class="text-secondary lead">
                        ทางเราขอส่งกำลังใจให้ผู้ประสบภัยทุกท่าน <br>
                        หากท่านต้องการความช่วยเหลือเร่งด่วน กรุณาติดต่อสายด่วน 1784 <br>หรือหน่วยงานในพื้นที่ได้ตลอด 24 ชั่วโมง
                    </p>
                </div>
            @else
                <!-- Form Selection -->
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold text-primary">เลือกแบบฟอร์ม</h1>
                    <p class="lead text-muted">กรุณาเลือกรายการที่ต้องการดำเนินการ</p>
                </div>

                <div class="row g-4 justify-content-center">
                    @foreach($forms as $form)
                    <div class="col-md-6">
                        <a href="{{ route($form['route']) }}" class="text-decoration-none">
                            <div class="card h-100 shadow-sm hover-shadow transition-all border-{{ $form['color'] ?? 'primary' }}">
                                <div class="card-body text-center p-5">
                                    <div class="mb-3">
                                        <i class="bi {{ $form['icon'] ?? 'bi-file-text' }} display-4 text-{{ $form['color'] ?? 'primary' }}"></i>
                                    </div>
                                    <h3 class="card-title text-dark">{{ $form['title'] }}</h3>
                                    <p class="card-text text-muted">{{ $form['description'] }}</p>
                                    <button class="btn btn-outline-{{ $form['color'] ?? 'primary' }} mt-3">
                                        เข้าสู่แบบฟอร์ม <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</div>

</body>
</html>
