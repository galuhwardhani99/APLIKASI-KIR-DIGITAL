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
        <a href="{{ route('pegawai.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Tambah Pegawai
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Unit Kerja</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
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
                    <td class="text-center">
                        <a href="{{ route('pegawai.edit', $p) }}"
                           class="btn btn-warning btn-xs">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('pegawai.destroy', $p) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus pegawai {{ $p->nama }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-xs">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        Belum ada data pegawai.
                        <a href="{{ route('pegawai.create') }}">Tambah sekarang</a>
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

@endsection
