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
                                            <label>Nama User</label>       
                                            <p class="ml-1 p-1">{{ @$data->name_user }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Username</label> 
                                            <p class="ml-1 p-1">{{ @$data->username }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Profile Picture</label> 
                                    <p class="ml-1 p-1"><img src="{{ asset('assets/img/profile_picture/'.@$data->profile_picture) }}" style="width: 200px" onerror="imgError(this)"></p>
                                </div>
                                <div class="form-group">
                                    <label>Last Login</label> 
                                    <p class="ml-1 p-1">{{ \Helper::tanggalwoah(@$data->last_login) }}</p>
                                </div>
                                <div class="form-group text-end">
                                    <a type="button" href="{{ route('user') }}"
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
