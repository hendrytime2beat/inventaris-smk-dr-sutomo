@extends('template')
@section('content')
<div class="row">
    
    <div class="container-fluid">
        <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('../assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
          <span class="mask bg-gradient-primary opacity-6"></span>
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                    <img src="{{ $data->profile_picture ? asset('assets/img/profile_picture/'.$data->profile_picture) : asset('assets/img/;logo/logo.png') }}" onerror="imgError(this)" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                    <h5 class="mb-1">
                        {{ session('name_user') }}
                    </h5>
                    <p class="mb-0 font-weight-bold text-sm">
                        {{ session('level') }}
                    </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                    <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                        <li class="nav-item">
                        </li>
                    </ul>
                    </div>
                </div>
            </div>
        
            <div class="col-12 col-xl-12 mt-3">
                <div class="card bg-white h-100 w-100">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">Profile</h6>
                    </div>
                    <form method="post" enctype="multipart/form-data" action="{{ route('access.profile.act') }}" class="card-body p-3">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        @if(session('message'))
                        <div class="alert alert-success">
                            <div class="small text-white">{{ session('message') }}</div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            @error('nama_lengkap')
                            <bR><small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input type="text" name="name_user" id="name_user" placeholder="Nama Lengkap" class="form-control" value="{{ @$data->name_user }}">
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Username</label>
                                    @error('username')
                                    <bR><small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <input type="text" name="username" id="username" placeholder="Username" class="form-control" value="{{ @$data->username }}">
                                </div>
                                <div class="col-sm-6">
                                    <label>Password</label>
                                    @error('password')
                                    <bR><small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <input type="password" name="password" id="password" placeholder="Password" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Picture</label>     
                            @error('profile_picture')
                            <bR><small class="text-danger">{{ $message }}</small>
                            @enderror
                            <div class="alert alert-secondary text-center col-sm-6">
                                <img id="blah_profile_picture" src="{{ @$data->profile_picture ? asset('assets/img/profile_picture/'.$data->profile_picture) : asset('assets/img/logo/logo.png') }}" style="width:200px;" onerror="imgError(this)" alt="..." loading="lazy">
                            </div>
                            <input class="form-control" name="profile_picture" style="display:none;" id="profile_picture" type="file" onchange="readURL(this, 'profile_picture');">
                            <button class="btn btn-outline-success btn-sm" type="button" onclick="$('#profile_picture').click();">Upload Foto</button>
                        </div>
                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
      </div>
  </div>
@endSection