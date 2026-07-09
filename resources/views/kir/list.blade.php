@extends('layouts.app')

@section('title', 'Daftar KIR – ' . $ruangan->nama_ruangan)
@section('page_title', 'Daftar KIR')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kir.index') }}">KIR</a></li>
    <li class="breadcrumb-item active">{{ $ruangan->nama_ruangan }}</li>
@endsection

@section('content')

{{-- NOTIFIKASI SUKSES --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

{{-- INFO RUANGAN --}}
<div class="card card-outline card-info mb-3">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-1">
                    <i class="fas fa-door-open mr-1 text-info"></i>
                    <strong>{{ $ruangan->nama_ruangan }}</strong>
                </h5>
                <small class="text-muted">
                    Kode Lokasi: <strong>{{ $ruangan->kode_lokasi ?? '-' }}</strong>
                    &nbsp;|&nbsp;
                    Pengguna Barang: <strong>{{ $ruangan->pengguna_barang ?? '-' }}</strong>
                    &nbsp;|&nbsp;
                    Pengurus: <strong>{{ $ruangan->pengurusBarang?->nama ?? '-' }}</strong>
                </small>
            </div>
            <div class="col-md-4 text-md-right mt-2 mt-md-0">
                <a href="{{ route('kir.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Ganti Ruangan
                </a>
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('kir.create', $ruangan->id) }}" class="btn btn-primary btn-sm ml-1">
                    <i class="fas fa-plus mr-1"></i> Tambah KIR
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- DAFTAR KIR + ASET PER KIR --}}
@forelse($kirs as $i => $kir)

<div class="card card-outline card-primary mb-3">

    {{-- Header KIR --}}
    <div class="card-header d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="card-title mb-0">
                <i class="fas fa-clipboard-list mr-1"></i>
                KIR #{{ $i + 1 }}
                &nbsp;—&nbsp;
                <strong>{{ $kir->tanggal->format('d/m/Y') }}</strong>
                <span class="badge badge-info ml-2">{{ $kir->items_count }} aset</span>
            </h3>
            <small class="text-muted">
                Pengguna Barang: {{ $kir->pengguna_barang ?? '-' }}
                @if($kir->keterangan)
                    &nbsp;|&nbsp; Ket: {{ $kir->keterangan }}
                @endif
            </small>
        </div>
        <div class="d-flex align-items-center">
            <button type="button"
                    class="btn btn-outline-primary btn-sm mr-1 btn-toggle"
                    data-target="#kirBody{{ $kir->id }}"
                    title="Tampilkan/Sembunyikan Aset">
                <i class="fas fa-chevron-down"></i>
            </button>
            <a href="{{ route('kir.show', $kir->id) }}"
               class="btn btn-info btn-sm mr-1" title="Lihat Detail">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('laporan.cetak-kir.form', $kir->id) }}"
               class="btn btn-primary btn-sm mr-1" title="Cetak KIR">
                <i class="fas fa-print"></i>
            </a>
            @if(Auth::user()->role === 'admin')
            <button type="button"
                    class="btn btn-danger btn-sm btn-hapus-kir"
                    data-id="{{ $kir->id }}"
                    data-tanggal="{{ $kir->tanggal->format('d/m/Y') }}"
                    data-toggle="modal"
                    data-target="#modalHapusKir"
                    title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
            @endif
        </div>
    </div>

    {{-- Tabel Aset --}}
    <div id="kirBody{{ $kir->id }}" class="kir-body">
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover table-sm mb-0">
                <thead style="background-color:#e8f4f8;">
                    <tr>
                        <th width="40">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Spesifikasi</th>
                        <th>Merk/Tipe</th>
                        <th class="text-center">Jumlah</th>
                        <th>Satuan</th>
                        <th>Keterangan</th>
                        <th class="text-center">Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kir->items as $j => $item)
                    @php
                        $a = $item->aset;
                        $badge = [
                            'baik'         => 'success',
                            'rusak_ringan' => 'warning',
                            'rusak_berat'  => 'danger',
                            'hilang'       => 'dark',
                        ][$a->kondisi] ?? 'secondary';
                    @endphp
                    <tr>
                        <td>{{ $j + 1 }}</td>
                        <td><small>{{ $a->kode_barang ?? '-' }}</small></td>
                        <td><strong>{{ $a->nama_barang }}</strong></td>
                        <td><small>{{ $a->spesifikasi_nama_barang ?? '-' }}</small></td>
                        <td>{{ $a->merk_tipe ?? '-' }}</td>
                        <td class="text-center">
                            {{ rtrim(rtrim(number_format($a->jumlah, 2, '.', ''), '0'), '.') }}
                        </td>
                        <td>{{ $a->satuan ?? '-' }}</td>
                        <td>{{ $a->keterangan ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $badge }}">
                                {{ str_replace('_', ' ', ucfirst($a->kondisi)) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-3">
                            Belum ada aset dalam KIR ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@empty

<div class="card card-outline card-secondary">
    <div class="card-body text-center py-5">
        <i class="fas fa-clipboard fa-3x text-muted mb-3"></i>
        <p class="text-muted mb-2">Belum ada KIR untuk ruangan <strong>{{ $ruangan->nama_ruangan }}</strong>.</p>
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('kir.create', $ruangan->id) }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Buat KIR Sekarang
        </a>
        @endif
    </div>
</div>

@endforelse

{{-- MODAL HAPUS --}}
@if(Auth::user()->role === 'admin')
<div class="modal fade" id="modalHapusKir" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Konfirmasi Hapus
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-clipboard-list fa-3x text-danger mb-3"></i>
                <p class="mb-1">Hapus KIR beserta seluruh data aset di dalamnya?</p>
                <p class="font-weight-bold mb-0" id="tanggalHapusKir">—</p>
            </div>
            <div class="modal-footer justify-content-center py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <form id="formHapusKir" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash mr-1"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.querySelectorAll('.btn-hapus-kir').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById('tanggalHapusKir').textContent = this.dataset.tanggal;
        document.getElementById('formHapusKir').action = '/kir/' + this.dataset.id;
    });
});

document.querySelectorAll('.btn-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const target = document.querySelector(this.dataset.target);
        const icon   = this.querySelector('i');
        if (target.style.display === 'none') {
            target.style.display = '';
            icon.classList.replace('fa-chevron-right', 'fa-chevron-down');
        } else {
            target.style.display = 'none';
            icon.classList.replace('fa-chevron-down', 'fa-chevron-right');
        }
    });
});
</script>
@endpush