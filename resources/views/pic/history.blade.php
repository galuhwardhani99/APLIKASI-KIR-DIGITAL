@extends('layouts.app')

@section('title', 'Riwayat PIC')
@section('page_title', 'Riwayat PIC')

@section('breadcrumb')
    <li class="breadcrumb-item active">Riwayat PIC</li>
@endsection

@push('styles')
<style>
    tr.row-pic { cursor: pointer; }
    tr.row-detail td { background: #f8f9fa; padding: 0 !important; }
    tr.row-detail { display: none; }
    .icon-toggle { transition: transform .2s; }
    .icon-toggle.open { transform: rotate(180deg); }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card card-primary card-outline">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-history mr-1"></i>
            Riwayat PIC — Pengguna Barang
        </h3>
    </div>

    <div class="card-body table-responsive">

        <table class="table table-bordered table-striped table-hover" style="width:100%">

            <thead class="thead-light">
                <tr>
                    <th width="60">No</th>
                    <th>Nama Pengguna Barang</th>
                    <th>Ruangan</th>
                    <th width="140" class="text-center">Jumlah Aset</th>
                    <th width="180" class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($riwayatPic as $index => $row)

                <tr>
                    <td class="row-pic" data-target="#detail-{{ $index }}">{{ $index + 1 }}</td>
                    <td class="row-pic" data-target="#detail-{{ $index }}"><strong>{{ $row['nama'] }}</strong></td>
                    <td class="row-pic" data-target="#detail-{{ $index }}">{{ $row['ruangan_list']->implode(', ') ?: '-' }}</td>
                    <td class="row-pic text-center" data-target="#detail-{{ $index }}">
                        <span class="badge badge-primary">{{ $row['jumlah_aset'] }}</span>
                    </td>
                    <td class="text-center">
                        <i class="fas fa-chevron-down icon-toggle row-pic mr-2" data-target="#detail-{{ $index }}"></i>

                        <a href="{{ route('pic.edit-nama', ['nama' => $row['nama']]) }}"
                            class="btn btn-warning btn-sm"
                            title="Update Nama">
                                <i class="fas fa-edit"></i>
                            </a>

                        <button type="button"
                                class="btn btn-danger btn-sm"
                                data-toggle="modal"
                                data-target="#modalDelete{{ $index }}"
                                title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>

                <tr class="row-detail" id="detail-{{ $index }}">
                    <td colspan="5">
                        <div class="p-3">
                            <table class="table table-sm table-bordered mb-0 bg-white">
                                <thead class="thead-light">
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
                                    @forelse($row['asets'] as $i => $aset)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $aset->kode_barang ?? '-' }}</td>
                                        <td><strong>{{ $aset->nama_barang }}</strong></td>
                                        <td>{{ $aset->spesifikasi_nama_barang ?? '-' }}</td>
                                        <td>{{ $aset->merk_tipe ?? '-' }}</td>
                                        <td class="text-center">
                                            {{ rtrim(rtrim(number_format($aset->jumlah, 2, '.', ''), '0'), '.') }}
                                        </td>
                                        <td>{{ $aset->satuan ?? '-' }}</td>
                                        <td>{{ $aset->keterangan ?? '-' }}</td>
                                        <td class="text-center">
                                            @php
                                                $badgeMap = [
                                                    'baik'         => 'success',
                                                    'rusak_ringan' => 'warning',
                                                    'rusak_berat'  => 'danger',
                                                    'hilang'       => 'dark',
                                                ];
                                                $labelMap = [
                                                    'baik'         => 'Baik',
                                                    'rusak_ringan' => 'Rusak Ringan',
                                                    'rusak_berat'  => 'Rusak Berat',
                                                    'hilang'       => 'Hilang',
                                                ];
                                            @endphp
                                            <span class="badge badge-{{ $badgeMap[$aset->kondisi] ?? 'secondary' }}">
                                                {{ $labelMap[$aset->kondisi] ?? $aset->kondisi }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-3">
                                            Tidak ada aset.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="5" class="text-center">
                        Belum ada data pengguna barang dari KIR.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

{{-- ── MODAL UPDATE & DELETE PER ORANG ──────────────────────────────── --}}
@foreach($riwayatPic as $index => $row)

{{-- Modal Update Nama --}}
<div class="modal fade" id="modalUpdate{{ $index }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="POST" action="{{ route('pic.update-nama') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="nama_lama" value="{{ $row['nama'] }}">

                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="fas fa-edit mr-1"></i> Update Nama Pengguna Barang
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Saat Ini</label>
                        <input type="text" class="form-control bg-light" value="{{ $row['nama'] }}" disabled>
                    </div>
                    <div class="form-group mb-0">
                        <label>Nama Baru <span class="text-danger">*</span></label>
                        <input type="text" name="nama_baru" class="form-control"
                               value="{{ $row['nama'] }}" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- Modal Delete --}}
<div class="modal fade" id="modalDelete{{ $index }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="POST" action="{{ route('pic.delete-nama') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="nama" value="{{ $row['nama'] }}">

                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-trash mr-1"></i> Hapus dari Riwayat PIC
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>
                        Yakin mau hapus <strong>{{ $row['nama'] }}</strong> dari Riwayat PIC?
                    </p>
                    <p class="text-muted mb-0">
                        <small>
                            Dokumen KIR & data aset <strong>tidak</strong> akan terhapus —
                            hanya label pengguna barangnya saja yang dikosongkan,
                            sehingga nama ini tidak akan muncul lagi di halaman ini.
                        </small>
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>

            </form>

        </div>
    </div>
</div>

@endforeach

@endsection

@push('scripts')
<script>
$(function () {

    $(document).on('click', '.row-pic', function () {
        const target = $(this).data('target');
        const $detail = $(target);
        const $icon = $('.icon-toggle[data-target="' + target + '"]');

        $detail.toggle();
        $icon.toggleClass('open');
    });

});
</script>
@endpush