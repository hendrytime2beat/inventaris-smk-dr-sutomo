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
                                            <label>Tanggal Transaksi</label>       
                                            <p class="ml-1 p-1">{{ \Helper::tanggalwow(@$data->transaction_date) }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Jam Transaksi</label> 
                                            <p class="ml-1 p-1">{{ @$data->transaction_time }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah Transaksi</label> 
                                    <p class="ml-1 p-1">{{ \Helper::uang(@$data->amount) }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Deskripsi</label> 
                                    <p class="ml-1 p-1">{{ @$data->description }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Catatan</label> 
                                    <p class="ml-1 p-1">{{ @$data->notes }}</p>
                                </div>
                                <div class="form-group text-end">
                                    <a type="button" href="{{ route('income') }}"
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
