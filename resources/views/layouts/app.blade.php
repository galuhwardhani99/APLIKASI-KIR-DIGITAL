<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | SIKAR</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-disarpus.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    {{-- NAVBAR ATAS --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('dashboard') }}" class="nav-link font-weight-bold text-dark">
                    SIKAR – Kartu Inventaris Aset Ruangan
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="far fa-bell"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-user-circle mr-1"></i>
                    {{ Auth::user()->name }}
                    <span class="badge badge-{{ Auth::user()->role === 'admin' ? 'primary' : 'secondary' }} ml-1">
                        {{ Auth::user()->role === 'admin' ? 'Admin' : 'Auditor' }}
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow">
                    <span class="dropdown-item-text text-muted small">
                        {{ Auth::user()->email }}
                    </span>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    {{-- SIDEBAR --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <a href="{{ route('dashboard') }}" class="brand-link text-center"
            style="background-color:#ffffff; border-bottom:none; box-shadow:none; padding-top:2px; padding-bottom:2px; display:flex; align-items:center; justify-content:center;">
            <img src="{{ asset('images/logo-disarpus.png') }}"
                alt="Logo SIKAR"
                style="opacity:1; width:110px; max-width:45%; height:auto; margin-right:8px; box-shadow:none; filter:none;">
            <span class="brand-text font-weight-bold" style="color:#000000;">SIKAR</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <i class="fas fa-user-circle fa-2x text-white" style="line-height:1;"></i>
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-compact"
                    data-widget="treeview" role="menu" data-accordion="false">

                    {{-- Dashboard --}}
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header">MASTER DATA</li>

                    {{-- Data Aset --}}
                    @if(Auth::user()->role === 'admin')
                        <li class="nav-item {{ request()->routeIs('aset.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('aset.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-box"></i>
                                <p>Data Aset<i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('aset.create') }}" class="nav-link {{ request()->routeIs('aset.create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i><p>Tambah Aset</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('aset.index') }}" class="nav-link {{ request()->routeIs('aset.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i><p>Daftar Aset</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('aset.index') }}" class="nav-link {{ request()->routeIs('aset.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-box"></i>
                                <p>Data Aset</p>
                            </a>
                        </li>
                    @endif

                    {{-- Data Ruangan --}}
                    @if(Auth::user()->role === 'admin')
                        <li class="nav-item {{ request()->routeIs('ruangan.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('ruangan.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-door-open"></i>
                                <p>Data Ruangan<i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('ruangan.create') }}"
                                       class="nav-link {{ request()->routeIs('ruangan.create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i><p>Tambah Ruangan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('ruangan.index') }}"
                                       class="nav-link {{ request()->routeIs('ruangan.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i><p>Daftar Ruangan</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('ruangan.index') }}"
                               class="nav-link {{ request()->routeIs('ruangan.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-door-open"></i>
                                <p>Data Ruangan</p>
                            </a>
                        </li>
                    @endif

                    {{-- Data PIC --}}
                    <li class="nav-item">
                        <a href="{{ route('pic.history') }}"
                           class="nav-link {{ request()->routeIs('pic.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>Riwayat PIC</p>
                        </a>
                    </li>

                    {{-- Data Pegawai (Admin & Auditor) --}}
                    @if(in_array(Auth::user()->role, ['admin', 'auditor']))
                    <li class="nav-item">
                        <a href="{{ route('pegawai.index') }}"
                           class="nav-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Data Pegawai</p>
                        </a>
                    </li>
                    @endif

                    <li class="nav-header">INVENTARISASI</li>
                    {{-- KIR --}}
                    @php
                        $isKirActive = request()->routeIs('kir.*', 'laporan.cetak-kir.*', 'laporan.inventarisasi.*');
                    @endphp
                    <li class="nav-item {{ $isKirActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isKirActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>
                                KIR
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            @if(Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a href="{{ route('kir.index') }}"
                                class="nav-link {{ request()->routeIs('kir.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Buat KIR</p>
                                </a>
                            </li>
                            @endif

                            <li class="nav-item">
                                <a href="{{ route('kir.index') }}"
                                class="nav-link {{ request()->routeIs('kir.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Daftar KIR</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('kir.index') }}"
                                class="nav-link {{ request()->routeIs('laporan.cetak-kir.*', 'laporan.inventarisasi.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Cetak KIR</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    {{-- Mutasi Aset --}}
                    <li class="nav-item">
                        <a href="{{ route('mutasi.index') }}" class="nav-link {{ request()->routeIs('mutasi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-exchange-alt"></i>
                            <p>Mutasi Aset</p>
                        </a>
                    </li>

                    {{-- Scan QR Code --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-qrcode"></i>
                            <p>Scan QR Code</p>
                        </a>
                    </li>

                    <li class="nav-header">LAPORAN</li>

                    {{-- Laporan KIR --}}
                    @php
                        $isLaporanActive = request()->routeIs('laporan.ruangan', 'laporan.kondisi', 'laporan.mutasi', 'laporan.pic');
                    @endphp
                    <li class="nav-item {{ $isLaporanActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isLaporanActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Laporan KIR<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="#" class="nav-link {{ request()->routeIs('laporan.ruangan') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Per Ruangan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link {{ request()->routeIs('laporan.kondisi') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Kondisi Aset</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link {{ request()->routeIs('laporan.mutasi') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Mutasi Aset</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link {{ request()->routeIs('laporan.pic') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Laporan PIC</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    {{-- KONTEN UTAMA --}}
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page_title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">Home</a>
                            </li>
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="main-footer">
        <strong>
            &copy; {{ date('Y') }}
            <a href="#">Dinas Kearsipan dan Perpustakaan Kota Kediri</a>
        </strong>
        <div class="float-right d-none d-sm-inline-block">
            <b>SIKAR</b> v1.0
        </div>
    </footer>

    <div id="sidebar-overlay"></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>

@stack('scripts')
</body>
</html>