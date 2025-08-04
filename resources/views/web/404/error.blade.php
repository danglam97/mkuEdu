<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Trang không tồn tại</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .error-container {
            min-height: 100vh;
        }
        .error-code {
            font-size: 8rem;
            font-weight: bold;
            color: #dc3545;
        }
        .error-message {
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
<div
    class="container d-flex align-items-center justify-content-center error-container flex-column text-center"
>
    <div class="error-code">404</div>
    <div class="error-message mb-3">
        Xin lỗi, trang bạn tìm kiếm không tồn tại.
    </div>
    <a href="{{route('home')}}" class="btn btn-primary">Về trang chủ</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
