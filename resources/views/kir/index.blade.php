{{-- FILE: resources/views/kir/index.blade.php --}}
@extends('layouts.app')

@section('title', 'KIR – Pilih Ruangan')
@section('page_title', 'Kartu Inventaris Ruangan')

@section('breadcrumb')
    <li class="breadcrumb-item active">KIR</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-clipboard-list mr-1"></i>
            Pilih Ruangan Terlebih dahulu
        </h3>
    </div>

    <form action="{{ route('kir.pilih-ruangan') }}" method="POST">
        @csrf

        <div class="card-body">

            <p class="text-muted mb-3">
                Pilih ruangan yang akan dibuat atau dilihat Kartu Inventaris Ruangan (KIR)-nya.
            </p>

            @error('ruangan_id')
                <div class="alert alert-danger py-2">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                </div>
            @enderror

            <div class="form-group mb-0">
                <label class="font-weight-bold">Ruangan <span class="text-danger">*</span></label>
                <select name="ruangan_id"
                        class="form-control form-control-lg @error('ruangan_id') is-invalid @enderror"
                        autofocus>
                    <option value="">-- Silahkan Pilih Ruangan --</option>
                    @foreach($ruangans as $r)
                        <option value="{{ $r->id }}" {{ old('ruangan_id') == $r->id ? 'selected' : '' }}>
                            {{ $r->nama_ruangan }}
                            @if($r->kode_lokasi)
                                &nbsp;·&nbsp; {{ $r->kode_lokasi }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-lg btn-block">
                <i class="fas fa-arrow-right mr-1"></i> Lihat Daftar KIR
            </button>
        </div>

    </form>
</div>

</div>
</div>
@endsection
