@extends('layouts.app')


@section('title','Permintaan Mutasi')
@section('page_title','Permintaan Mutasi Aset')


@section('breadcrumb')

<li class="breadcrumb-item active">
    Permintaan Mutasi
</li>

@endsection



@section('content')


@if(session('success'))

<div class="alert alert-success">
    {{ session('success') }}
</div>

@endif



<div class="card card-primary card-outline">


<div class="card-header d-flex justify-content-between">


<h3 class="card-title">

<i class="fas fa-exchange-alt"></i>

Daftar Permintaan Mutasi

</h3>



@if(Auth::user()->role === 'admin')
<a href="{{ route('mutasi.create') }}"
   class="btn btn-primary btn-sm">

<i class="fas fa-plus"></i>

Tambah Permintaan

</a>
@endif


</div>





<div class="card-body">


<table class="table table-bordered table-striped">


<thead>

<tr>

<th>No</th>

<th>Aset</th>

<th>Dari Ruangan</th>

<th>Ke Ruangan</th>

<th>Pemohon</th>

<th>Tanggal</th>

<th>Keterangan</th>

<th>Status</th>

@if(Auth::user()->role === 'auditor')
<th class="text-center" style="width:140px">Aksi</th>
@endif


</tr>


</thead>



<tbody>


@forelse($mutasis as $i => $mutasi)


<tr>


<td>
{{ $i+1 }}
</td>



<td>

<strong>
{{ $mutasi->aset->nama_barang }}
</strong>

<br>

<small>
Kode:
{{ $mutasi->aset->kode_barang }}
</small>


</td>



<td>

{{ $mutasi->ruanganAsal?->nama_ruangan ?? '-' }}

</td>



<td>

{{ $mutasi->ruanganTujuan?->nama_ruangan ?? '-' }}

</td>



<td>

{{ $mutasi->pemohon?->nama ?? '-' }}

</td>



<td>

{{ $mutasi->tanggal_pengajuan->format('d-m-Y') }}

</td>

<td>

{{ $mutasi->alasan ?? '-' }}

</td>


<td>


@if($mutasi->status == 'pending')

<span class="badge badge-warning">
Pending
</span>


@elseif($mutasi->status == 'disetujui')


<span class="badge badge-success">
Disetujui
</span>


@else


<span class="badge badge-danger">
Ditolak
</span>


@endif



</td>


@if(Auth::user()->role === 'auditor')
<td class="text-center">
    @if($mutasi->status === 'pending')
    <button type="button"
            class="btn btn-primary btn-sm"
            data-toggle="modal"
            data-target="#modalValidasi{{ $mutasi->id }}">
        <i class="fas fa-check-double mr-1"></i> Validasi
    </button>
    @else
    <span class="text-muted small">
        Sudah diproses
        @if($mutasi->tanggal_validasi)
            <br>{{ $mutasi->tanggal_validasi->format('d-m-Y') }}
        @endif
    </span>
    @endif
</td>
@endif



</tr>


@empty


<tr>

<td colspan="{{ Auth::user()->role === 'auditor' ? 8 : 7 }}"
class="text-center">

Belum ada permintaan mutasi.

</td>


</tr>


@endforelse



</tbody>


</table>


</div>


</div>

{{-- ── MODAL VALIDASI (hanya auditor, hanya untuk mutasi pending) ────────── --}}
@if(Auth::user()->role === 'auditor')
@foreach($mutasis as $mutasi)
@if($mutasi->status === 'pending')
<div class="modal fade" id="modalValidasi{{ $mutasi->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="POST" action="{{ route('mutasi.validasi', $mutasi->id) }}">
                @csrf
                @method('PUT')

                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-exchange-alt mr-1"></i> Validasi Permintaan Mutasi
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <table class="table table-sm table-borderless mb-3">
                        <tr>
                            <td class="text-muted" width="140">Aset</td>
                            <td>: <strong>{{ $mutasi->aset->nama_barang }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dari Ruangan</td>
                            <td>: {{ $mutasi->ruanganAsal?->nama_ruangan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ke Ruangan</td>
                            <td>: {{ $mutasi->ruanganTujuan?->nama_ruangan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pemohon</td>
                            <td>: {{ $mutasi->pemohon?->nama ?? '-' }}</td>
                        </tr>
                        @if($mutasi->alasan)
                        <tr>
                            <td class="text-muted">Alasan</td>
                            <td>: {{ $mutasi->alasan }}</td>
                        </tr>
                        @endif
                    </table>

                    <div class="form-group">
                        <label>Catatan Validasi <small class="text-muted">(opsional)</small></label>
                        <textarea name="catatan_validasi" class="form-control" rows="2"
                                  placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>

                    <input type="hidden" name="status" id="statusInput{{ $mutasi->id }}" value="">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit"
                            class="btn btn-danger"
                            onclick="document.getElementById('statusInput{{ $mutasi->id }}').value='ditolak'">
                        <i class="fas fa-times mr-1"></i> Tolak
                    </button>
                    <button type="submit"
                            class="btn btn-success"
                            onclick="document.getElementById('statusInput{{ $mutasi->id }}').value='disetujui'">
                        <i class="fas fa-check mr-1"></i> Setujui
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endif
@endforeach
@endif

@endsection