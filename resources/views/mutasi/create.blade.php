@extends('layouts.app')

@section('title','Tambah Permintaan Mutasi')
@section('page_title','Tambah Permintaan Mutasi')


@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('mutasi.index') }}">
        Mutasi
    </a>
</li>

<li class="breadcrumb-item active">
    Tambah Permintaan
</li>
@endsection



@section('content')

<div class="card card-primary card-outline">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-exchange-alt"></i>
            Form Permintaan Mutasi Aset
        </h3>
    </div>


    <form method="POST" action="{{ route('mutasi.store') }}">
        @csrf


        <div class="card-body">


            <div class="form-group">
                <label>
                    Pilih Aset
                </label>

                <select name="aset_id"
                        class="form-control"
                        required>

                    <option value="">
                        -- Pilih Aset --
                    </option>


                    @foreach($asets as $aset)

                    <option value="{{ $aset->id }}">

                        {{ $aset->nama_barang }}

                        -
                        {{ $aset->ruangan?->nama_ruangan ?? 'Belum ditempatkan' }}

                    </option>

                    @endforeach

                </select>

            </div>




            <div class="form-group">

                <label>
                    Ruangan Tujuan
                </label>


                <select name="ruangan_tujuan_id"
                        class="form-control"
                        required>


                    <option value="">
                        -- Pilih Ruangan --
                    </option>


                    @foreach($ruangans as $ruangan)

                    <option value="{{ $ruangan->id }}">
                        {{ $ruangan->nama_ruangan }}
                    </option>

                    @endforeach


                </select>

            </div>




            <div class="form-group">

                <label>
                    Pemohon Mutasi
                </label>


                <select name="pemohon_id"
                        class="form-control"
                        required>


                    <option value="">
                        -- Pilih Pegawai --
                    </option>


                    @foreach($pegawais as $pegawai)

                    <option value="{{ $pegawai->id }}">
                        {{ $pegawai->nama }}
                        -
                        {{ $pegawai->jabatan }}
                    </option>

                    @endforeach


                </select>


            </div>




            <div class="form-group">

                <label>
                    Tanggal Pengajuan
                </label>


                <input type="date"
                       name="tanggal_pengajuan"
                       class="form-control"
                       value="{{ date('Y-m-d') }}"
                       required>

            </div>




            <div class="form-group">

                <label>
                    Alasan Mutasi
                </label>


                <textarea
                    name="alasan"
                    class="form-control"
                    rows="3"
                    placeholder="Masukkan alasan perpindahan aset"></textarea>

            </div>



        </div>



        <div class="card-footer">

            <button class="btn btn-primary">

                <i class="fas fa-paper-plane"></i>
                Ajukan Mutasi

            </button>


            <a href="{{ route('mutasi.index') }}"
               class="btn btn-secondary">

                Batal

            </a>

        </div>


    </form>


</div>


@endsection