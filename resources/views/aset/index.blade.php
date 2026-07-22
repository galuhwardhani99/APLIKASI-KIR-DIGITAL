@extends('layouts.app')

@section('title', 'Daftar Aset')
@section('page_title', 'Daftar Aset')

@section('breadcrumb')
    <li class="breadcrumb-item active">Data Aset</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.4.1/css/rowGroup.bootstrap4.min.css">
<style>
    #tableAset thead th { vertical-align: middle; text-align: center; }
    /* NIBAR (kolom ke-2) & Nomor Register (kolom ke-3) -> wrap text
       seperti di Excel, karena isinya angka panjang tanpa spasi */
    #tableAset td:nth-child(4),
    #tableAset td:nth-child(5) {
        word-break: break-all;
        white-space: normal;
        max-width: 160px;
    }
    #tableAset td:nth-child(2) {
        white-space: normal;
        min-width: 170px;
    }
    #tableAset td:nth-child(2) .badge {
        margin-bottom: 2px;
    }
    tr.dtrg-group td {
        background: #eef1f4 !important;
        font-weight: 700;
        text-transform: uppercase;
        color: #2c3e50;
    }
    tr.dtrg-level-0 td { padding-left: 10px !important; }
    tr.dtrg-level-1 td { padding-left: 26px !important; background: #f2f4f6 !important; }
    tr.dtrg-level-2 td { padding-left: 42px !important; background: #f6f7f9 !important; }
    tr.dtrg-level-3 td { padding-left: 58px !important; background: #fafbfc !important; }
</style>
@endpush

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<div class="card card-primary card-outline">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-box mr-1"></i> Daftar Aset
        </h3>

        @if(Auth::user()->role === 'admin')
        <a href="{{ route('aset.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Tambah Aset
        </a>
        @endif
    </div>

    <div class="card-body table-responsive">
        <table id="tableAset" class="table table-bordered table-striped table-hover" style="width:100%">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>NIBAR</th>
                    <th>Nomor Register</th>
                    <th>Spesifikasi Nama Barang</th>
                    <th>Merk/Tipe</th>
                    <th>Tahun Perolehan</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Keterangan</th>
                    <th>Ruangan</th>
                    <th>Kondisi</th>
                    <th>Aksi</th>
                    {{-- Kolom grouping: rantai kode+nama level 3-6 -> disembunyikan
                         dari tampilan tabel, dipakai RowGroup bikin header
                         bertingkat (poin 1, 2, 4 request awal). --}}
                    <th>Level 3</th>
                    <th>Level 4</th>
                    <th>Level 5</th>
                    <th>Level 6</th>
                </tr>
            </thead>

            <tbody>
                @foreach($asets as $i => $aset)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="text-monospace">
                        @foreach(explode('.', $aset->kode_barang_lengkap) as $segmen)
                            <span class="badge badge-light border mr-1">{{ $segmen }}</span>
                        @endforeach
                    </td>
                    <td><strong>{{ $aset->nama_barang }}</strong></td>
                    <td>{{ $aset->nibar }}</td>
                    <td>{{ $aset->nomor_register }}</td>
                    <td>{{ $aset->spesifikasi_nama_barang ?? '-' }}</td>
                    <td>{{ $aset->merk_tipe ?? '-' }}</td>
                    <td>{{ $aset->tahun_perolehan ?? '-' }}</td>
                    <td class="text-center">
                        {{ rtrim(rtrim(number_format($aset->jumlah, 2, '.', ''), '0'), '.') }}
                    </td>
                    <td>{{ $aset->satuan ?? '-' }}</td>

                    <td>{{ $aset->keterangan ?? '-' }}</td>

                    <td>
                        {{ $aset->ruangan?->nama_ruangan ?? 'Belum ditempatkan' }}
                    </td>

                    <td>
                        @php
                            $badge = [
                                'baik'         => 'success',
                                'rusak_ringan' => 'warning',
                                'rusak_berat'  => 'danger',
                                'hilang'       => 'dark',
                            ][$aset->kondisi] ?? 'secondary';
                        @endphp
                        <span class="badge badge-{{ $badge }}">
                            {{ str_replace('_', ' ', ucfirst($aset->kondisi)) }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('aset.edit', $aset->id) }}"
                           class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button"
                                class="btn btn-danger btn-sm btn-hapus"
                                data-id="{{ $aset->id }}"
                                data-nama="{{ $aset->nama_barang }}"
                                data-toggle="modal"
                                data-target="#modalHapus"
                                title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                        @else
                        <a href="{{ route('aset.show', $aset->id) }}"
                           class="btn btn-secondary btn-sm" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        @endif
                    </td>
                    <td>{{ $aset->level3_label }}</td>
                    <td>{{ $aset->level4_label }}</td>
                    <td>{{ $aset->level5_label }}</td>
                    <td>{{ $aset->level6_label }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ── MODAL HAPUS ──────────────────────────────────────────────────── --}}
@if(Auth::user()->role === 'admin')
<div class="modal fade" id="modalHapus" tabindex="-1" role="dialog" aria-hidden="true">
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
                <i class="fas fa-box-open fa-3x text-danger mb-3"></i>
                <p class="mb-1">Hapus aset berikut?</p>
                <p class="font-weight-bold mb-0" id="namaHapus">-</p>
            </div>
            <div class="modal-footer justify-content-center py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <form id="formHapus" method="POST">
                    @csrf
                    @method('DELETE')
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
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.4.1/js/dataTables.rowGroup.min.js"></script>
<script>
$(function () {

    $('#tableAset').DataTable({
        // Urutan baris SUDAH benar dari server (ikut kode Excel) -> kunci,
        // jangan biarkan user acak urutannya lewat klik header kolom
        // (soalnya bisa merusak pengelompokan RowGroup bertingkat di bawah).
        ordering: false,
        columnDefs: [
            { targets: [14, 15, 16, 17], visible: false, searchable: true }
        ],

        rowGroup: {
            dataSrc: [14, 15, 16, 17]
        },
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        pageLength: 25,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            zeroRecords: "Data tidak ditemukan",
            paginate: { first: "Awal", last: "Akhir", next: "Selanjutnya", previous: "Sebelumnya" }
        }
    });

    // Modal hapus
    $(document).on('click', '.btn-hapus', function () {
        $('#namaHapus').text($(this).data('nama'));
        $('#formHapus').attr('action', '/aset/' + $(this).data('id'));
    });

});
</script>
@endpush