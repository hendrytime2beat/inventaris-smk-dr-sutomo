@extends('template')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-body pt-4 p-0">
                <div class="row">
                    <div class="col-md-12 mt-4">
                        <div class="card">
                            <div class="card-header pb-0 px-3">
                                <h6 class="mb-0">{{ $title }}</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Tgl Perencanaan</label>       
                                            <p class="ml-1 p-1">{{ \Helper::tanggalwow(@$data->created_at) }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Anggaran</label> 
                                            <p class="ml-1 p-1">{{ \Helper::uang(@$data->anggaran) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Nama Item</label> 
                                    <p class="ml-1 p-1">{{ @$data->nama_item }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Jenis</label> 
                                    <p class="ml-1 p-1">{{ @$data->jenis }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Kategori (KBKI)</label> 
                                    <p class="ml-1 p-1">{{ @$data->nama_kategori }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Harga</label> 
                                    <p class="ml-1 p-1">{{ \Helper::uang(@$data->harga) }}</p>
                                </div>
                                <div class="form-group">
                                    <label>User Input</label> 
                                    <p class="ml-1 p-1">{{ @$data->nama_user_create }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Status Perencanaan</label> 
                                    <p class="ml-1 p-1">{{ ucfirst(@$data->status_perencanaan) }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Keterangan</label> 
                                    <p class="ml-1 p-1">{{ @$data->keterangan }}</p>
                                </div>
                                <div class="form-group text-end">
                                    <a type="button" href="{{ route('perencanaan') }}"
                                        class="btn btn-warning">Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endSection
