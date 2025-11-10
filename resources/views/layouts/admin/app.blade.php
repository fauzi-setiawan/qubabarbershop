<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Dashboard')</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <style>
    body {
        font-family: Arial, sans-serif;
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }

    /* Sidebar & Content Dashboard */
    .content {
        margin-left: 220px;
        padding: 20px;
    }

    .card-box {
        padding: 20px;
        border-radius: 8px;
        color: #fff;
    }
  </style>
</head>
<body>

  {{-- Sidebar & Footer hanya tampil kalau bukan halaman login --}}
  @if (!Route::is('admin.login.form'))
      @include('partials.admin.sidebar')
  @endif

  {{-- Main content --}}
  <div class="{{ Route::is('admin.login.form') ? 'login-page' : 'content' }}">
      @yield('content')
  </div>

  @if (!Route::is('admin.login.form'))
      @include('partials.admin.footer')
  @endif

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  @stack('scripts')
</body>
</html>
