@extends('layouts.app')

@section('title', 'Cetak KIR')
@section('page_title', 'Cetak KIR')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kir.list', $kir->ruangan_id) }}">Daftar KIR</a></li>
    <li class="breadcrumb-item active">Cetak KIR</li>
@endsection

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('laporan.cetak-kir.pdf', $kir->id) }}" id="formCetakKir">
    @csrf

    <div class="row">
        {{-- ── SETTING PARAMETER ────────────────────────────────────── --}}
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-sliders-h mr-1"></i> Setting Parameter</h3>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label>Periode <span class="text-danger">*</span></label>
                        <input type="text" name="periode" class="form-control"
                               placeholder="Contoh: Juni 2026"
                               value="{{ old('periode', $kir->tanggal ? $kir->tanggal->locale('id')->translatedFormat('F Y') : '') }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Pengguna Barang <span class="text-danger">*</span></label>
                        <input type="text" name="pengguna_barang" class="form-control"
                               placeholder="Nama pengguna barang"
                               value="{{ old('pengguna_barang', $kir->pengguna_barang ?? $kir->ruangan->pengguna_barang ?? '') }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Kode Lokasi</label>
                        <input type="text" name="kode_lokasi" class="form-control"
                               placeholder="Contoh: 12.13.33.08.02.01.01"
                               value="{{ old('kode_lokasi', $kir->ruangan->kode_lokasi ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>Nama Ruangan</label>
                        <input type="text" class="form-control bg-light" value="{{ $kir->ruangan->nama_ruangan }}" disabled>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── SETTING TANDA TANGAN ─────────────────────────────────── --}}
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-signature mr-1"></i> Setting Tanda Tangan</h3>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label>Tanggal TTD <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_ttd" class="form-control"
                               value="{{ old('tanggal_ttd', now()->format('Y-m-d')) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Penandatangan Sisi Kiri <small class="text-muted">(Pengurus Barang)</small> <span class="text-danger">*</span></label>
                        <select name="penandatangan_kiri_id" class="form-control" required>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach ($pegawaiList as $pegawai)
                                <option value="{{ $pegawai->id }}"
                                    {{ old('penandatangan_kiri_id', $kir->ruangan->pengurus_barang_id) == $pegawai->id ? 'selected' : '' }}>
                                    {{ $pegawai->nama }} — {{ $pegawai->nip }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Penandatangan Sisi Kanan <small class="text-muted">(Penanggung Jawab Ruangan)</small> <span class="text-danger">*</span></label>
                        <select name="penandatangan_kanan_id" class="form-control" required>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach ($pegawaiList as $pegawai)
                                <option value="{{ $pegawai->id }}"
                                    {{ old('penandatangan_kanan_id', $kir->ruangan->penanggung_jawab_id) == $pegawai->id ? 'selected' : '' }}>
                                    {{ $pegawai->nama }} — {{ $pegawai->nip }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ── PREVIEW DAFTAR ASET ──────────────────────────────────────── --}}
    <div class="card card-secondary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-box mr-1"></i> Preview Aset dalam KIR ({{ $kir->nomor_kir }})</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>NIBAR</th>
                        <th>Nomor Register</th>
                        <th>Nama Barang</th>
                        <th>Merk/Tipe</th>
                        <th>Tahun</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Ket</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kir->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->aset->nibar }}</td>
                            <td>{{ $item->aset->nomor_register }}</td>
                            <td>{{ $item->aset->nama_barang }}</td>
                            <td>{{ $item->aset->merk_tipe }}</td>
                            <td>{{ $item->aset->tahun_perolehan }}</td>
                            <td>{{ rtrim(rtrim(number_format($item->aset->jumlah, 2, '.', ''), '0'), '.') }}</td>
                            <td>{{ $item->aset->satuan }}</td>
                            <td>{{ $item->aset->keterangan }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center">Belum ada aset pada KIR ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer bg-white">
        <button type="submit" formaction="{{ route('laporan.cetak-kir.pdf', $kir->id) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
        </button>
        <button type="submit" formaction="{{ route('laporan.cetak-kir.excel', $kir->id) }}" class="btn btn-success">
            <i class="fas fa-file-excel mr-1"></i> Cetak Excel
        </button>
        <a href="{{ route('kir.list', $kir->ruangan_id) }}" class="btn btn-secondary">Batal</a>
    </div>

</form>

@endsection