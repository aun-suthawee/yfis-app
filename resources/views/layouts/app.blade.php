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
            background-color: var(--color-bg);
            color: #333;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        /* Background element only shown on dashboard page. Kept as a fixed element
           so the body has no padding and the background sits behind everything.
           We use a separate element (.page-background) instead of styling the body
           so the background can be placed behind layout elements with z-index:-1. */
        .page-background {
            position: fixed;
            inset: 0; /* top:0; right:0; bottom:0; left:0 */
            background: url('{{ asset('images/yalaview2.jpg') }}') no-repeat center center;
            background-size: cover;
            z-index: -2; /* background itself sits behind overlay */
            pointer-events: none; /* allow clicks through */
        }
        /* darker overlay to increase contrast - sits between image and the page */
        .page-background::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: -1;
        }
        #wrapper {
            overflow-x: hidden;
        }
        /* Modern glass-style sidebar */
        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: -17rem;
            transition: margin .25s ease-out;
            width: 17rem;
            background: linear-gradient(180deg, rgba(54,116,181,0.94), rgba(86,140,206,0.92));
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border-right: 1px solid rgba(255,255,255,0.08);
            box-shadow: 8px 0 30px rgba(0,0,0,0.14);
            border-top-right-radius: 18px;
            border-bottom-right-radius: 18px;
            overflow: hidden;
        }
        #sidebar-wrapper .sidebar-heading {
            padding: 1.25rem 1.25rem;
            font-size: 1.2rem;
            font-weight: 700;
            background: transparent;
            color: rgba(255,255,255,0.98);
            text-align: center;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            justify-content: center;
        }
        #sidebar-wrapper .list-group {
            width: 100%;
            padding: 0.75rem;
            box-sizing: border-box;
        }
        #sidebar-wrapper .list-group-item {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: transparent;
            color: rgba(255,255,255,0.9);
            border: none;
            padding: 0.9rem 1.1rem;
            font-weight: 500;
            border-radius: 10px;
            margin: 0.3rem 0.45rem;
            transition: all 0.18s ease;
            overflow: hidden;
        }
        /* icon container */
        #sidebar-wrapper .list-group-item .bi {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.95);
            font-size: 1.15rem;
            transition: transform 150ms ease, background 150ms ease, color 150ms ease;
        }
        #sidebar-wrapper .list-group-item:hover .bi {
            transform: translateX(4px) scale(1.03);
            background: rgba(255,255,255,0.12);
        }
        #sidebar-wrapper .list-group-item:hover {
            transform: translateX(6px);
            background: rgba(255,255,255,0.06);
            color: #fff;
        }
        /* active with subtle left indicator */
        #sidebar-wrapper .list-group-item.active {
            background: linear-gradient(90deg, rgba(255,255,255,0.12), rgba(255,255,255,0.06));
            color: #fff;
            font-weight: 700;
        }
        #sidebar-wrapper .list-group-item.active::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 8px;
            bottom: 8px;
            width: 6px;
            border-radius: 6px;
            background: linear-gradient(180deg, rgba(255,255,255,0.92), rgba(255,255,255,0.6));
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
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
        /* Make navbar transparent and remove border/shadow when used on dashboard
           so it sits visually over the background image. Adjust colors for
           readability (white text/icons). */
        .dashboard-page .navbar-light {
            background-color: transparent !important;
            border-bottom: 0 !important;
            box-shadow: none !important;
        }
        .dashboard-page .navbar-light .navbar-brand,
        .dashboard-page .navbar-light .btn,
        .dashboard-page .navbar-light .bi,
        .dashboard-page .navbar-light .bottom-nav-item {
            color: #ffffff !important;
        }
        /* Ensure the collapse toggle (hamburger) looks white on dashboard */
        .dashboard-page #sidebarToggle {
            color: #ffffff !important;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        /* Centered content wrapper used inside the page container.
           Keeps the background fixed behind everything while the content
           gets a max-width, margin:auto and normal padding. */
        .centered-content {
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
            width: 100%;
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
<body class="{{ request()->routeIs('dashboard.index') ? 'dashboard-page' : 'bg-light' }}">

    {{-- Dashboard background (fixed, behind everything) --}}
    @if(request()->routeIs('dashboard.index'))
        <div class="page-background" aria-hidden="true"></div>
    @endif

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
            @auth
                <a class="list-group-item list-group-item-action {{ request()->routeIs('shelters.*') ? 'active' : '' }}" href="{{ route('shelters.index') }}">
                    <i class="bi bi-house-heart me-2"></i> ศูนย์พักพิง
                </a>
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
                <button class="btn btn-link p-0 {{ request()->routeIs('dashboard.index') ? 'text-white' : 'text-dark' }}" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div class="d-flex align-items-center ms-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" width="40" class="me-2 d-none d-md-block">
                    <span class="navbar-brand fw-bold mb-0 h1 {{ request()->routeIs('dashboard.index') ? 'text-white' : 'text-primary-custom' }}">Yala Flood Information System</span>
                </div>
            </div>
        </nav>

        <div class="{{ request()->routeIs('dashboard.index') ? 'container-fluid' : 'container' }} pb-5 px-3">
            <div class="centered-content">
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
    @auth
        <a href="{{ route('shelters.index') }}" class="bottom-nav-item {{ request()->routeIs('shelters.*') ? 'active' : '' }}">
            <i class="bi bi-house-heart"></i>
            <span>ศูนย์พักพิง</span>
        </a>
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
