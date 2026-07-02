@extends('layouts.app')

@section('title', 'Kelola Aset')
@section('page_title', 'Kelola Aset')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('ruangan.index') }}">Data Ruangan</a>
</li>
<li class="breadcrumb-item active">
    Kelola Aset
</li>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card card-primary card-outline">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-boxes mr-1"></i>

            Kelola Aset Ruangan

        </h3>

    </div>

    <form action="{{ route('ruangan.simpan-aset',$ruangan->id) }}"
          method="POST">

        @csrf

        <div class="card-body">

            <div class="alert alert-info">

                <strong>Ruangan :</strong>

                {{ $ruangan->nama_ruangan }}

            </div>

            @if($asets->count())

                <table class="table table-bordered table-striped">

                    <thead>

                        <tr>

                            <th width="60">Pilih</th>

                            <th>Nama Barang</th>

                            <th>Merk / Tipe</th>

                            <th>Kondisi</th>

                            <th>Ruangan Saat Ini</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach($asets as $aset)

                        <tr>

                            <td class="text-center">

                                <input
                                    type="checkbox"
                                    name="aset[]"
                                    value="{{ $aset->id }}"

                                    {{ $aset->ruangan_id == $ruangan->id ? 'checked' : '' }}

                                >

                            </td>

                            <td>

                                {{ $aset->nama_barang }}

                            </td>

                            <td>

                                {{ $aset->merk_tipe ?? '-' }}

                            </td>

                            <td>

                                {{ ucwords(str_replace('_',' ',$aset->kondisi)) }}

                            </td>

                            <td>

                                {{ $aset->ruangan->nama_ruangan ?? '-' }}

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            @else

                <div class="alert alert-warning">

                    Belum ada aset.

                </div>

            @endif

        </div>

        <div class="card-footer">

            <button class="btn btn-primary">

                <i class="fas fa-save mr-1"></i>

                Simpan

            </button>

            <a href="{{ route('ruangan.index') }}"
               class="btn btn-secondary">

                Kembali

            </a>

        </div>

    </form>

</div>

@endsection