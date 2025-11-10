<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quba Barbershop')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('image/logoo.png') }}" type="image/png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        main {
            flex: 1;
        }

        /* Tombol WhatsApp */
        #whatsapp-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: #25D366;
            color: white;
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: grab;
            z-index: 9999;
            user-select: none;
            transition: transform 0.2s;
        }

        #whatsapp-btn:active {
            cursor: grabbing;
            transform: scale(0.95);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    {{-- Tombol WhatsApp Mengambang (draggable) --}}
    @if(!request()->routeIs('user.login.form') && !request()->routeIs('user.register.form'))
    <a href="https://wa.me/6282140035275?text=Halo%20min,%20saya%20mau%20bertanya%20tentang%20layanan%20Quba%20Barbershop"
       id="whatsapp-btn"
       target="_blank"
       aria-label="Chat via WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>
    @endif

    {{-- Header --}}
    @include('partials.user.header')

    {{-- Navbar --}}
    @include('partials.user.navbar')

    {{-- Main Content --}}
    <main class="d-flex flex-column">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.user.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script tombol bisa digerakkan -->
    <script>
        const btn = document.getElementById('whatsapp-btn');
        let offsetX, offsetY, isDragging = false;

        btn.addEventListener('mousedown', (e) => {
            isDragging = true;
            offsetX = e.clientX - btn.getBoundingClientRect().left;
            offsetY = e.clientY - btn.getBoundingClientRect().top;
            btn.style.transition = 'none';
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            e.preventDefault();

            const x = e.clientX - offsetX;
            const y = e.clientY - offsetY;

            // Batas layar
            const maxX = window.innerWidth - btn.offsetWidth;
            const maxY = window.innerHeight - btn.offsetHeight;

            btn.style.left = Math.min(Math.max(0, x), maxX) + 'px';
            btn.style.top = Math.min(Math.max(0, y), maxY) + 'px';
            btn.style.right = 'auto';
            btn.style.bottom = 'auto';
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
            btn.style.transition = 'transform 0.2s';
        });

        // Supaya di mobile juga bisa digeser
        btn.addEventListener('touchstart', (e) => {
            isDragging = true;
            const touch = e.touches[0];
            offsetX = touch.clientX - btn.getBoundingClientRect().left;
            offsetY = touch.clientY - btn.getBoundingClientRect().top;
            btn.style.transition = 'none';
        });

        document.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            const touch = e.touches[0];

            const x = touch.clientX - offsetX;
            const y = touch.clientY - offsetY;

            const maxX = window.innerWidth - btn.offsetWidth;
            const maxY = window.innerHeight - btn.offsetHeight;

            btn.style.left = Math.min(Math.max(0, x), maxX) + 'px';
            btn.style.top = Math.min(Math.max(0, y), maxY) + 'px';
            btn.style.right = 'auto';
            btn.style.bottom = 'auto';
        });

        document.addEventListener('touchend', () => {
            isDragging = false;
            btn.style.transition = 'transform 0.2s';
        });
    </script>
</body>
</html>
