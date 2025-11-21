<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Primary Meta Tags -->
    <title>{{ config('app.name', 'YFIS') }} - ระบบสารสนเทศเพื่อการบริหารจัดการสถานการณ์อุทกภัย</title>
    <meta name="title" content="{{ config('app.name', 'YFIS') }} - ระบบสารสนเทศเพื่อการบริหารจัดการสถานการณ์อุทกภัย">
    <meta name="description" content="ระบบสารสนเทศเพื่อการบริหารจัดการสถานการณ์อุทกภัย สำนักงานศึกษาธิการจังหวัดยะลา ติดตามสถานการณ์ รายงานความเสียหาย และข้อมูลโรงเรียนที่ได้รับผลกระทบ">
    <meta name="keywords" content="น้ำท่วม, ยะลา, ภัยพิบัติ, โรงเรียน, การศึกษา, YFIS, Flood, Yala, สำนักงานศึกษาธิการจังหวัดยะลา">
    <meta name="author" content="สำนักงานศึกษาธิการจังหวัดยะลา">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ config('app.name', 'YFIS') }} - ระบบสารสนเทศเพื่อการบริหารจัดการสถานการณ์อุทกภัย">
    <meta property="og:description" content="ติดตามสถานการณ์ รายงานความเสียหาย และข้อมูลโรงเรียนที่ได้รับผลกระทบจากอุทกภัยในจังหวัดยะลา">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ config('app.name', 'YFIS') }} - ระบบสารสนเทศเพื่อการบริหารจัดการสถานการณ์อุทกภัย">
    <meta property="twitter:description" content="ติดตามสถานการณ์ รายงานความเสียหาย และข้อมูลโรงเรียนที่ได้รับผลกระทบจากอุทกภัยในจังหวัดยะลา">
    <meta property="twitter:image" content="{{ asset('images/logo.png') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --color-primary: #3674B5;
            --color-secondary: #578FCA;
            --color-cream: #D1F8EF;
            --color-bg: #F9F8F6;
        }
        body {
            font-family: 'Prompt', sans-serif;
            background-color: var(--color-bg) !important;
            color: #333;
        }
        #wrapper {
            overflow-x: hidden;
        }
        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: -17rem;
            transition: margin .25s ease-out;
            background-color: var(--color-primary);
            width: 17rem;
        }
        #sidebar-wrapper .sidebar-heading {
            padding: 1.5rem 1.25rem;
            font-size: 1.4rem;
            font-weight: 600;
            background-color: rgba(0, 0, 0, 0.1);
            color: white;
            text-align: center;
        }
        #sidebar-wrapper .list-group {
            width: 17rem;
        }
        #sidebar-wrapper .list-group-item {
            background-color: transparent;
            color: rgba(255, 255, 255, 0.85);
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 400;
            transition: all 0.2s;
        }
        #sidebar-wrapper .list-group-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            padding-left: 1.75rem;
        }
        #sidebar-wrapper .list-group-item.active {
            background-color: var(--color-cream);
            color: var(--color-primary);
            font-weight: 600;
            border-left: 5px solid var(--color-secondary);
        }
        #page-content-wrapper {
            min-width: 100vw;
        }
        body.sb-sidenav-toggled #wrapper #sidebar-wrapper {
            margin-left: 0;
        }
        .btn-primary {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }
        .btn-primary:hover {
            background-color: var(--color-secondary);
            border-color: var(--color-secondary);
        }
        .text-primary-custom {
            color: var(--color-primary);
        }
        .bg-primary-custom {
            background-color: var(--color-primary);
        }
        .navbar-light {
            background-color: white !important;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }
        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }
            #page-content-wrapper {
                min-width: 0;
                width: 100%;
            }
            body.sb-sidenav-toggled #wrapper #sidebar-wrapper {
                margin-left: -17rem;
            }
        }

        /* Mobile App View */
        @media (max-width: 767.98px) {
            #sidebar-wrapper {
                display: none !important;
            }
            #sidebarToggle {
                display: none !important;
            }
            #page-content-wrapper {
                padding-bottom: 90px; /* Space for bottom nav */
            }
            .navbar-brand {
                font-size: 0.95rem;
                white-space: normal; /* Allow wrapping */
                line-height: 1.2;
            }
            
            /* Bottom Navigation */
            .bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
                z-index: 1040;
                display: flex;
                justify-content: space-around;
                padding: 0.75rem 0.5rem;
                padding-bottom: calc(0.75rem + env(safe-area-inset-bottom));
                border-top-left-radius: 20px;
                border-top-right-radius: 20px;
            }
            .bottom-nav-item {
                text-decoration: none;
                color: #9ca3af;
                display: flex;
                flex-direction: column;
                align-items: center;
                font-size: 0.7rem;
                font-weight: 500;
                flex: 1;
                transition: all 0.2s;
                border: none;
                background: none;
            }
            .bottom-nav-item i {
                font-size: 1.4rem;
                margin-bottom: 4px;
                transition: all 0.2s;
            }
            .bottom-nav-item.active {
                color: var(--color-primary);
            }
            .bottom-nav-item.active i {
                transform: translateY(-2px);
            }
            .bottom-nav-item:active {
                transform: scale(0.95);
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-light">

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="border-end" id="sidebar-wrapper">
        <div class="sidebar-heading border-bottom d-flex flex-column align-items-center justify-content-center py-4">
            <div class="lh-1">YFIS</div>
        </div>
        <div class="list-group list-group-flush pt-2">
            <a class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.index') ? 'active' : '' }}" href="{{ route('dashboard.index') }}">
                <i class="bi bi-speedometer2 me-2"></i> แดชบอร์ด
            </a>
            <a class="list-group-item list-group-item-action {{ request()->routeIs('disaster.dataset') ? 'active' : '' }}" href="{{ route('disaster.dataset') }}">
                <i class="bi bi-table me-2"></i> ชุดข้อมูล
            </a>
            <a class="list-group-item list-group-item-action {{ request()->routeIs('shelters.*') ? 'active' : '' }}" href="{{ route('shelters.index') }}">
                <i class="bi bi-house-heart me-2"></i> ศูนย์พักพิง
            </a>
            @auth
                <a class="list-group-item list-group-item-action {{ request()->routeIs('disaster.index') ? 'active' : '' }}" href="{{ route('disaster.index') }}">
                    <i class="bi bi-file-earmark-text me-2"></i> จัดการรายงาน
                </a>
            @endauth
        </div>
        
        <div class="mt-auto p-4 border-top border-white-10 text-white">
            @auth
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <span class="small fw-bold">{{ auth()->user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light w-100 btn-sm">ออกจากระบบ</button>
                </form>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="btn btn-light text-primary w-100 fw-bold">เข้าสู่ระบบ</a>
            @endguest
        </div>
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm mb-4 py-3">
            <div class="container-fluid">
                <button class="btn btn-link text-dark p-0" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div class="d-flex align-items-center ms-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" width="40" class="me-2 d-none d-md-block">
                    <span class="navbar-brand fw-bold text-primary-custom mb-0 h1">Yala Flood Information System</span>
                </div>
            </div>
        </nav>

        <div class="container pb-5">
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<!-- Bottom Navigation for Mobile -->
<div class="bottom-nav d-md-none">
    <a href="{{ route('dashboard.index') }}" class="bottom-nav-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span>แดชบอร์ด</span>
    </a>
    <a href="{{ route('disaster.dataset') }}" class="bottom-nav-item {{ request()->routeIs('disaster.dataset') ? 'active' : '' }}">
        <i class="bi bi-table"></i>
        <span>ชุดข้อมูล</span>
    </a>
    <a href="{{ route('shelters.index') }}" class="bottom-nav-item {{ request()->routeIs('shelters.*') ? 'active' : '' }}">
        <i class="bi bi-house-heart"></i>
        <span>ศูนย์พักพิง</span>
    </a>
    @auth
        <a href="{{ route('disaster.index') }}" class="bottom-nav-item {{ (request()->routeIs('disaster.*') && !request()->routeIs('disaster.dataset')) ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i>
            <span>จัดการ</span>
        </a>
        <button type="button" class="bottom-nav-item" data-bs-toggle="modal" data-bs-target="#profileModal">
            <i class="bi bi-person-circle"></i>
            <span>โปรไฟล์</span>
        </button>
    @else
        <a href="{{ route('login') }}" class="bottom-nav-item {{ request()->routeIs('login') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>เข้าสู่ระบบ</span>
        </a>
    @endauth
</div>

<!-- Profile Modal for Mobile -->
@auth
<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-4">
                <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 72px; height: 72px;">
                    <i class="bi bi-person-fill fs-1"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ auth()->user()->name }}</h5>
                <p class="text-muted small mb-4">{{ auth()->user()->email }}</p>
                
                <div class="d-grid gap-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100 rounded-pill py-2">
                            <i class="bi bi-box-arrow-right me-2"></i> ออกจากระบบ
                        </button>
                    </form>
                    <button type="button" class="btn btn-light w-100 rounded-pill py-2 text-muted" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    window.addEventListener('DOMContentLoaded', event => {
        const sidebarToggle = document.body.querySelector('#sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', event => {
                event.preventDefault();
                document.body.classList.toggle('sb-sidenav-toggled');
            });
        }
    });
</script>
@stack('scripts')
</body>
</html>
