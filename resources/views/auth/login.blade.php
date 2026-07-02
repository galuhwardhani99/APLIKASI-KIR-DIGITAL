<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | SIKAR – Sistem Informasi Kartu Inventaris Aset Ruangan</title>

    {{-- Google Font --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    {{-- AdminLTE --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
</head>
<body class="hold-transition login-page" style="background: linear-gradient(135deg, #1a237e 0%, #283593 60%, #3949ab 100%);">

<div class="login-box">

    {{-- Logo --}}
    <div class="login-logo">
        <a href="#" style="color: #fff;">
            <i class="fas fa-qrcode mr-2" style="font-size: 2rem;"></i><br>
            <b>SIKAR</b>
        </a>
        <p class="text-white-50 mt-1" style="font-size: 0.85rem;">
            Sistem Informasi Kartu Inventaris Aset Ruangan<br>
            Pemerintah Kota Kediri
        </p>
    </div>

    {{-- Card --}}
    <div class="card">
        <div class="card-header text-center border-0 pt-4 pb-0">
            <h5 class="mb-0 font-weight-bold text-secondary">Masuk ke Sistem</h5>
        </div>
        <div class="card-body">

            {{-- Error --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    {{ $errors->first() }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                {{-- Email --}}
                <div class="input-group mb-3">
                    <input type="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="Email"
                           value="{{ old('email') }}"
                           autofocus required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="input-group mb-3">
                    <input type="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Password"
                           required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="row mb-3">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Ingat saya</label>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    <i class="fas fa-sign-in-alt mr-1"></i> Masuk
                </button>
            </form>

        </div>
    </div>

    {{-- Footer --}}
    <p class="text-center text-white-50" style="font-size: 0.78rem;">
        &copy; {{ date('Y') }} Dinas Kearsipan dan Perpustakaan Kota Kediri
    </p>

</div>

{{-- jQuery --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
{{-- Bootstrap --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
{{-- AdminLTE --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
</body>
</html>
