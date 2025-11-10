<style>
    :root {
        --dark: #1c1c1c;
        --gold: #d4af37;
        --light: #f8f9fa;
    }

    /* Navbar utama */
    .navbar {
        background-color: var(--dark) !important;
        border-bottom: 2px solid var(--gold);
    }

    /* Branding */
    .navbar-brand span {
        color: var(--gold);
        font-weight: 700;
        font-size: 1.2rem;
    }

    /* Link menu */
    .nav-link {
        color: var(--light) !important;
        font-weight: 500;
        transition: 0.3s;
        text-align: center;
    }

    /* Warna link saat hover atau aktif */
    .nav-link:hover,
    .nav-link.active {
        color: var(--gold) !important;
        text-shadow: 0 0 6px rgba(212, 175, 55, 0.6);
    }

    /* Navbar text biasa */
    .navbar-text {
        color: var(--light);
        font-weight: 500;
    }

    /* Icon default */
    .bi-person-circle,
    .bi-box-arrow-in-right {
        color: var(--light) !important;
        transition: 0.3s;
    }

    /* Icon saat link di hover */
    .nav-link:hover .bi-person-circle,
    .nav-link:hover .bi-box-arrow-in-right {
        color: var(--gold) !important;
        text-shadow: 0 0 8px rgba(212, 175, 55, 0.7);
    }

    /* Icon saat link aktif */
    .nav-link.active .bi-person-circle,
    .nav-link.active .bi-box-arrow-in-right {
        color: var(--gold) !important;
        text-shadow: 0 0 8px rgba(212, 175, 55, 0.7);
    }

    /* Navbar toggler (mobile) */
    .navbar-toggler {
        border-color: var(--gold);
    }

    .navbar-toggler-icon {
        background-image: none;
        position: relative;
    }

    .navbar-toggler-icon::before {
        content: "\2630";
        color: var(--gold);
        font-size: 1.5rem;
    }
</style>


<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container">
        <!-- Logo dan nama Quba -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('user.dashboard') }}">
            <img src="{{ asset('image/logoo.png') }}" alt="Logo" class="me-2" style="width:55px; height:55px;">
            <span>Quba Barbershop</span>
        </a>

        <!-- Toggle menu mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUser"
            aria-controls="navbarUser" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu utama -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarUser">
            <ul class="navbar-nav mb-2 mb-lg-0 text-center align-items-center">
                <li class="nav-item mx-2">
                    <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
                       href="{{ route('user.dashboard') }}">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item mx-2">
                    <a class="nav-link {{ request()->routeIs('user.layanan') ? 'active' : '' }}"
                       href="{{ route('user.layanan') }}">
                        Layanan
                    </a>
                </li>

                @auth
                    <li class="nav-item mx-2">
                        <a class="nav-link {{ request()->routeIs('user.booking') ? 'active' : '' }}"
                           href="{{ route('user.booking') }}">
                            Pesanan
                        </a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link {{ request()->routeIs('user.history') ? 'active' : '' }}"
                           href="{{ route('user.history') }}">
                            History
                        </a>
                    </li>
                @endauth
            </ul>
        </div>

        <!-- Area kanan (profil / login) -->
        <ul class="navbar-nav ms-auto d-flex align-items-center">
            @auth
                <li class="nav-item me-3">
                    <span class="navbar-text">Halo, {{ Auth::user()->nama ?? 'User' }} 👋</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('user.profile') ? 'active' : '' }}"
                       href="{{ route('user.profile') }}">
                        <i class="bi bi-person-circle fs-4"></i>
                    </a>
                </li>
            @endauth

            @guest
                @if (!request()->routeIs('user.login.form'))
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="{{ route('user.login.form') }}">
                            <i class="bi bi-box-arrow-in-right fs-4"></i>
                        </a>
                    </li>
                @endif
            @endguest
        </ul>
    </div>
</nav>
