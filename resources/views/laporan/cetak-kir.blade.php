@extends('layouts.app')

@section('title', 'Inventarisasi KIR')
@section('page_title', 'Inventarisasi KIR')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kir.list', $kir->ruangan_id) }}">Daftar KIR</a></li>
    <li class="breadcrumb-item active">Inventarisasi</li>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <ul class="mb-0">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

<form method="POST" action="{{ route('laporan.cetak-kir.pdf', $kir->id) }}" id="formInventarisasi">
@csrf

<div class="row">

    {{-- ── SETTING PARAMETER ──────────────────────────────────────── --}}
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-sliders-h mr-1"></i> Setting Parameter
                </h3>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label>Periode <span class="text-danger">*</span></label>
                    <input type="text" name="periode" class="form-control"
                           placeholder="Contoh: Juni 2026"
                           value="{{ old('periode', $kir->tanggal ? $kir->tanggal->locale('id')->translatedFormat('F Y') : '') }}"
                           required>
                </div>

                {{-- Pengguna Barang → dropdown pegawai --}}
                <div class="form-group">
                    <label>Pengguna Barang <span class="text-danger">*</span></label>
                    <select name="pengguna_barang_id" id="selectPenggunaBarang"
                            class="form-control @error('pengguna_barang_id') is-invalid @enderror"
                            required>
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($pegawaiList as $p)
                            <option value="{{ $p->id }}"
                                    data-nama="{{ $p->nama }}"
                                    data-nip="{{ $p->nip }}"
                                    {{ old('pengguna_barang_id',
                                        $selectedPenggunaId ?? '') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }} — {{ $p->nip }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Hidden field nama untuk dikirim ke controller --}}
                    <input type="hidden" name="pengguna_barang" id="hiddenPenggunaBarang"
                           value="{{ old('pengguna_barang', $kir->pengguna_barang ?? '') }}">
                    @error('pengguna_barang_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Kode Lokasi</label>
                    <input type="text" name="kode_lokasi" class="form-control"
                           placeholder="Contoh: 12.13.33.08.02.01.01"
                           value="{{ old('kode_lokasi', $kir->ruangan->kode_lokasi ?? '') }}">
                </div>

                <div class="form-group mb-0">
                    <label>Nama Ruangan</label>
                    <input type="text" class="form-control bg-light"
                           value="{{ $kir->ruangan->nama_ruangan }}" disabled>
                </div>

            </div>
        </div>
    </div>

    {{-- ── SETTING TANDA TANGAN ───────────────────────────────────── --}}
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-signature mr-1"></i> Setting Tanda Tangan
                </h3>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label>Tanggal TTD <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_ttd" class="form-control"
                           value="{{ old('tanggal_ttd', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="form-group">
                    <label>
                        Penandatangan Sisi Kiri
                        <small class="text-muted">(Pengurus Barang)</small>
                        <span class="text-danger">*</span>
                    </label>
                    <select name="penandatangan_kiri_id" class="form-control" required>
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($pegawaiList as $p)
                            <option value="{{ $p->id }}"
                                {{ old('penandatangan_kiri_id',
                                    $kir->pengurus_barang_id ?? '') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }} — {{ $p->nip }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label>
                        Penandatangan Sisi Kanan
                        <small class="text-muted">(Penanggung Jawab Ruangan)</small>
                        <span class="text-danger">*</span>
                    </label>
                    <select name="penandatangan_kanan_id" class="form-control" required>
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($pegawaiList as $p)
                            <option value="{{ $p->id }}"
                                {{ old('penandatangan_kanan_id',
                                    $kir->penanggung_jawab_id ?? '') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }} — {{ $p->nip }}
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
        <h3 class="card-title">
            <i class="fas fa-box mr-1"></i>
            Preview Aset dalam KIR
            <span class="badge badge-primary ml-1">{{ $kir->items->count() }} aset</span>
        </h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th width="40">No</th>
                    <th>NIBAR</th>
                    <th>No. Register</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Merk/Tipe</th>
                    <th>Tahun</th>
                    <th class="text-center">Jumlah</th>
                    <th>Satuan</th>
                    <th>Ket</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kir->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->aset->nibar ?? '-' }}</td>
                    <td>{{ $item->aset->nomor_register ?? '-' }}</td>
                    <td><small>{{ $item->aset->kode_barang ?? '-' }}</small></td>
                    <td><strong>{{ $item->aset->nama_barang }}</strong></td>
                    <td>{{ $item->aset->merk_tipe ?? '-' }}</td>
                    <td>{{ $item->aset->tahun_perolehan ?? '-' }}</td>
                    <td class="text-center">
                        {{ rtrim(rtrim(number_format($item->aset->jumlah, 2, '.', ''), '0'), '.') }}
                    </td>
                    <td>{{ $item->aset->satuan ?? '-' }}</td>
                    <td>{{ $item->aset->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-3">
                        Belum ada aset pada KIR ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── TOMBOL AKSI ──────────────────────────────────────────────── --}}
<div class="card card-outline card-light">
    <div class="card-body d-flex justify-content-between align-items-center py-3">

        <a href="{{ route('kir.list', $kir->ruangan_id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Batal
        </a>

        <div>
            {{-- Tombol Simpan: update data KIR --}}
            <button type="submit"
                    formaction="{{ route('laporan.inventarisasi.simpan', $kir->id) }}"
                    class="btn btn-primary mr-1">
                <i class="fas fa-save mr-1"></i> Simpan
            </button>

            {{-- Tombol Cetak PDF --}}
            <button type="submit"
                    formaction="{{ route('laporan.cetak-kir.pdf', $kir->id) }}"
                    class="btn btn-danger mr-1">
                <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
            </button>

            {{-- Tombol Cetak Excel --}}
            <button type="submit"
                    formaction="{{ route('laporan.cetak-kir.excel', $kir->id) }}"
                    class="btn btn-success">
                <i class="fas fa-file-excel mr-1"></i> Cetak Excel
            </button>
        </div>

    </div>
</div>

</form>

@endsection

@push('scripts')
<script>
// Saat dropdown pengguna barang dipilih → isi hidden field nama
document.getElementById('selectPenggunaBarang').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    document.getElementById('hiddenPenggunaBarang').value = opt.dataset.nama || '';
});

// Isi hidden field saat halaman load (jika ada nilai default)
(function () {
    const sel = document.getElementById('selectPenggunaBarang');
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.value) {
        document.getElementById('hiddenPenggunaBarang').value = opt.dataset.nama || '';
    }
})();
</script>
@endpush