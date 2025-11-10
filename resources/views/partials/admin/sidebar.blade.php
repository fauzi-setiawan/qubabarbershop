<div class="sidebar d-flex flex-column text-white">
    <style>
        /* sidebar utama */
        .sidebar {
            width: 230px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #1A1A1A; 
            padding-top: 20px;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.4);
        }

        /* styling logo dan teks */
        .sidebar img {
            border-radius: 8px;
        }

        .sidebar h5 {
            color: #C19A6B; 
            letter-spacing: 0.5px;
        }

        .sidebar p {
            color: #B0B0B0;
            font-size: 14px;
        }

        /* link menu */
        .sidebar .nav-link {
            color: #E0E0E0;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 8px;
            padding: 10px 12px;
            transition: all 0.2s ease-in-out;
        }

        /* efek hover */
        .sidebar .nav-link:hover {
            background-color: #2B2B2B;
            color: #C19A6B; 
        }

        /* link aktif */
        .sidebar .nav-link.active {
            background-color: #C19A6B;
            color: #1A1A1A;
            font-weight: 600;
        }

        .sidebar .nav-link i {
            font-size: 18px;
        }

        /* area logout di bawah */
        .sidebar .logout {
            margin-top: auto;
            border-top: 1px solid #333;
            padding: 12px;
        }

        .sidebar .logout a {
            color: #B0B0B0;
            font-size: 15px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: color 0.2s ease;
        }

        .sidebar .logout a:hover {
            color: #C9A227;
        }
    </style>

    <!-- header sidebar -->
    <div class="text-center py-3">
        <img src="{{ asset('image/logoo.png') }}" alt="Logo" width="60" class="mb-2">
        <h5 class="fw-bold">QUBA BARBERSHOP</h5>
        <p class="mb-0">
            Selamat Datang, {{ Auth::user()->nama ?? 'Admin' }} 
        </p>
    </div>

    <!-- menu navigasi -->
    <ul class="nav flex-column px-3 mt-3">
        <!-- dashboard -->
        <li class="nav-item mb-1">
            <a href="{{ url('/admin/dashboard') }}" 
               class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>

        <!-- transaksi -->
        <li class="nav-item mb-1">
            <a href="{{ route('admin.transaksi.index') }}" 
               class="nav-link {{ request()->is('admin/transaksi*') ? 'active' : '' }}">
                <i class="bi bi-bag"></i> Transaksi
            </a>
        </li> 

        <!-- layanan -->
        <li class="nav-item mb-1">
            <a href="{{ url('/admin/layanan') }}" 
               class="nav-link {{ request()->is('admin/layanan*') ? 'active' : '' }}">
               <i class="bi bi-list-check"></i> Layanan
            </a>
        </li>

        <!-- petugas -->
        <li class="nav-item mb-1">
            <a href="{{ route('admin.petugas.index') }}" 
               class="nav-link {{ request()->is('admin/petugas*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> Petugas
            </a>
        </li>

        <!-- customer -->
        <li class="nav-item mb-1">
            <a href="{{ url('/admin/customer') }}" 
               class="nav-link {{ request()->is('admin/customer') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Customer
            </a>
        </li>

        <!-- laporan -->
        <li class="nav-item mb-1">
            <a href="{{ route('admin.laporan.index') }}" 
               class="nav-link {{ request()->is('admin/laporan*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-data"></i> Laporan
            </a>
        </li>
    </ul>

    <!-- tombol logout -->
    <div class="logout px-3">
        <a href="{{ route('logout') }}" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
           <i class="bi bi-box-arrow-right"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>
