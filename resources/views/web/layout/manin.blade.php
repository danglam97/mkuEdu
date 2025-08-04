<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <base href="{{ asset('') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('/style/css/globals.css')}}" />

    <!-- CSS Bootstrap (thư viện chung) -->
    <link rel="stylesheet" href="{{ asset('/style/css/bootstrap/css/bootstrap.min.css')}}" />

    <!-- CSS Slick Carousel (thư viện slider) -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/style/css/slick/slick.css')}}" />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset('/style/css/slick/slick-theme.css')}}"
    />

    <!-- CSS Font Awesome (icon) -->
    <link rel="stylesheet" href="{{ asset('/style/css/fontawesome/fontawesome-free-6.7.2-web/css/all.min.css')}}" />

    <link rel="stylesheet" href="{{ asset('/style/css/style.css')}}" />
</head>
<style>
    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        z-index: 9999;
        transition: opacity 0.5s ease;
    }

    #loading-overlay.fade-out {
        opacity: 0;
        pointer-events: none;
    }

    .loading-content {
        text-align: center;
    }

    .loading-logo {
        width: 80px;
        margin-bottom: 15px;
    }

    .loading-spinner {
        border: 6px solid #f3f3f3;
        border-top: 6px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: 0 auto 10px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    #loading-overlay p {
        font-size: 14px;
        color: #555;
    }

</style>
<script>
    window.addEventListener("load", function() {
        let overlay = document.getElementById("loading-overlay");
        overlay.classList.add("fade-out");
        setTimeout(() => overlay.style.display = "none", 500);
    });
</script>

<body>
<div id="loading-overlay">
    <div class="loading-content">
        <img src="{{ asset('/style/images/logo-truong.png') }}" alt="Logo" class="loading-logo">
        <div class="loading-spinner"></div>
        <p>Đang tải dữ liệu...</p>
    </div>
</div>
<section class="index">
@include('web.layout.header')
@yield('content')
@include('web.layout.footer')
</section>
</body>
<script src="{{ asset('/style/js/jquery/jquery.js')}}"></script>
<script src="{{ asset('/style/js/bootstrap/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('/style/js/slick/slick-1.8.1/slick/slick.min.js')}}"></script>
<script src="{{ asset('/style/js/slide.js')}}"></script>
<script src="{{ asset('/style/js/nav-coselap.js')}}"></script>
</html>
