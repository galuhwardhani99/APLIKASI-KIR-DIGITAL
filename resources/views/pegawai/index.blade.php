@extends('layouts.app')

@section('title', 'Data Pegawai')
@section('page_title', 'Data Pegawai')

@section('breadcrumb')
    <li class="breadcrumb-item active">Data Pegawai</li>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

<div class="card card-outline card-primary">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title mr-auto">
            <i class="fas fa-users mr-1"></i> Daftar Pegawai
        </h3>
        
        {{-- Hanya Admin yang bisa melihat tombol Tambah --}}
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('pegawai.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Tambah Pegawai
        </a>
        @endif
    </div>

    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th width="40">#</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Unit Kerja</th>
                    <th class="text-center" width="90">Status</th>
                    
                    {{-- Sembunyikan Header Aksi untuk Auditor --}}
                    @if(Auth::user()->role === 'admin')
                    <th class="text-center" width="100">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($pegawais as $i => $p)
                <tr>
                    <td>{{ $pegawais->firstItem() + $i }}</td>
                    <td><code>{{ $p->nip }}</code></td>
                    <td><strong>{{ $p->nama }}</strong></td>
                    <td>{{ $p->jabatan ?? '-' }}</td>
                    <td>{{ $p->unit_kerja ?? '-' }}</td>
                    <td class="text-center">
                        @if($p->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-secondary">Non-aktif</span>
                        @endif
                    </td>

                    {{-- Sembunyikan Tombol Edit/Hapus untuk Auditor --}}
                    @if(Auth::user()->role === 'admin')
                    <td class="text-center">
                        <a href="{{ route('pegawai.edit', $p) }}"
                           class="btn btn-warning btn-xs"
                           title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button"
                                class="btn btn-danger btn-xs btn-hapus"
                                data-id="{{ $p->id }}"
                                data-nama="{{ $p->nama }}"
                                data-toggle="modal"
                                data-target="#modalHapus"
                                title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ Auth::user()->role === 'admin' ? '7' : '6' }}" class="text-center text-muted py-4">
                        Belum ada data pegawai.
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('pegawai.create') }}">Tambah sekarang</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pegawais->hasPages())
    <div class="card-footer">
        {{ $pegawais->links() }}
    </div>
    @endif
</div>

{{-- MODAL HAPUS --}}
@if(Auth::user()->role === 'admin')
<div class="modal fade" id="modalHapus" tabindex="-1" role="dialog" aria-labelledby="labelModalHapus" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title" id="labelModalHapus">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Konfirmasi Hapus
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-user-times fa-3x text-danger mb-3"></i>
                <p class="mb-1">Hapus pegawai ini?</p>
                <p class="font-weight-bold mb-0" id="namaHapus">—</p>
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
@if(Auth::user()->role === 'admin')
<script>
document.querySelectorAll('.btn-hapus').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const id   = this.dataset.id;
        const nama = this.dataset.nama;
        document.getElementById('namaHapus').textContent = nama;
        document.getElementById('formHapus').action = '/pegawai/' + id;
    });
});
</script>
@endif
@endpush