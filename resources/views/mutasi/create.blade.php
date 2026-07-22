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


            {{-- PILIH ASET --}}
            <div class="form-group">

                <label>
                    Pilih Aset
                </label>


                <input type="text"
                       id="aset_search"
                       class="form-control"
                       list="daftar_aset"
                       placeholder="Ketik nama aset atau kode barang..."
                       autocomplete="off"
                       required>


                <datalist id="daftar_aset">

                    @foreach($asets as $aset)

                    <option
                        value="{{ $aset->nama_barang }} | Kode: {{ $aset->kode_barang ?? '-' }} | {{ $aset->ruangan?->nama_ruangan ?? 'Belum ditempatkan' }}"
                        data-id="{{ $aset->id }}">
                    </option>

                    @endforeach

                </datalist>


                <input type="hidden"
                       name="aset_id"
                       id="aset_id">


                <small class="text-muted">
                    Pilih aset berdasarkan nama atau kode barang.
                </small>


            </div>




            {{-- RUANGAN TUJUAN --}}
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




            {{-- PEMOHON --}}
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




            {{-- TANGGAL --}}
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




            {{-- ALASAN --}}
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

            <button type="submit"
                    class="btn btn-primary">

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



@push('scripts')

<script>

document.getElementById('aset_search').addEventListener('change', function () {

    let inputValue = this.value;

    let options = document.querySelectorAll('#daftar_aset option');

    let asetId = '';

    options.forEach(function(option){

        if(option.value === inputValue){

            asetId = option.dataset.id;

        }

    });


    document.getElementById('aset_id').value = asetId;


});



document.querySelector('form').addEventListener('submit', function(e){

    let asetId = document.getElementById('aset_id').value;


    if(!asetId){

        e.preventDefault();

        alert('Silahkan pilih aset yang tersedia.');

    }

});

</script>

@endpush