@extends('layouts.app')

@section('title', 'Update PIC')
@section('page_title', 'Update PIC')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('pic.index') }}">Update PIC</a>
    </li>
    <li class="breadcrumb-item active">Form Update PIC</li>
@endsection

@section('content')

<div class="card card-primary card-outline">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-user-edit mr-1"></i>
            Form Update PIC
        </h3>
    </div>

    <form action="{{ route('pic.store') }}" method="POST">

        @csrf

        <div class="card-body">

            @if ($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            {{-- Pilih Ruangan --}}

            <div class="form-group">

                <label>Ruangan</label>

                <select name="ruangan_id"
                        class="form-control"
                        required>

                    <option value="">-- Pilih Ruangan --</option>

                    @foreach($ruangans as $r)

                        <option value="{{ $r->id }}"
                            {{ old('ruangan_id', $ruangan->id ?? '') == $r->id ? 'selected' : '' }}>

                            {{ $r->nama_ruangan }}

                        </option>

                    @endforeach

                </select>

            </div>

            <hr>

            <h5 class="mb-3">
                PIC Baru
            </h5>

            <div class="form-group">

                <label>Pengguna Barang</label>

                <input
                    type="text"
                    name="pengguna_barang_baru"
                    class="form-control"
                    value="{{ old('pengguna_barang_baru', $ruangan->pengguna_barang ?? '') }}"
                    required>

            </div>

            <div class="form-group">

                <label>Pengurus Barang</label>

                <select
                    name="pengurus_barang_baru_id"
                    class="form-control"
                    required>

                    @foreach($pegawai as $p)

                        <option value="{{ $p->id }}"
                            {{ old('pengurus_barang_baru_id', $ruangan->pengurus_barang_id ?? '') == $p->id ? 'selected' : '' }}>

                            {{ $p->nama }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="form-group">

                <label>Penanggung Jawab</label>

                <select
                    name="penanggung_jawab_baru_id"
                    class="form-control"
                    required>

                    @foreach($pegawai as $p)

                        <option value="{{ $p->id }}"
                            {{ old('penanggung_jawab_baru_id', $ruangan->penanggung_jawab_id ?? '') == $p->id ? 'selected' : '' }}>

                            {{ $p->nama }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="form-group">

                <label>Tanggal Perubahan</label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ now()->format('d-m-Y') }}"
                    disabled>

            </div>

            <div class="form-group">

                <label>Keterangan</label>

                <textarea
                    name="keterangan"
                    class="form-control"
                    rows="3">{{ old('keterangan') }}</textarea>

            </div>

        </div>

        <div class="card-footer">

            <button
                class="btn btn-primary">

                <i class="fas fa-save mr-1"></i>

                Simpan Perubahan

            </button>

            <a
                href="{{ route('pic.index') }}"
                class="btn btn-secondary">

                Batal

            </a>

        </div>

    </form>

</div>

@endsection