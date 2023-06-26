@extends('template')
@section('content')
  <div class="row">
    <div class="col-md-12 mt-4">
      <div class="card">
        <div class="card-header pb-0 px-3">
          <h6 class="mb-0">{{ $title }}</h6>
        </div>
        <div class="card-body pt-4 p-0">
            
            <form method="post" enctype="multipart/form-data" action="{{ @$data->id ? route('user.edit.act', $data->id) : route('user.add.act') }}" class="card-body p-3">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id" value="{{ @$data->id ? @$data->id : 0 }}">
                @if(session('message'))
                <div class="alert alert-success">
                    <div class="small text-white">{{ session('message') }}</div>
                </div>
                @endif
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Nama Lengkap</label>
                            @error('title')
                            <bR><small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input type="text" name="name_user" id="name_user" placeholder="Nama Lengkap" class="form-control" value="{{ old('name_user') ? old('name_user') : @$data->name_user }}">
                        </div>
                        <div class="col-sm-6">
                            <label>Username</label>
                            @error('title')
                            <bR><small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input type="text" name="username" id="username" placeholder="Username" class="form-control" value="{{ old('username') ? old('username') : @$data->username }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    @error('password')
                    <bR><small class="text-danger">{{ $message }}</small>
                    @enderror
                    <input type="password" name="password" id="password" placeholder="Password" class="form-control">
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
                    <a type="button" href="{{ route('income') }}" class="btn btn-warning">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>
      </div>
    </div>
  </div>
@endSection 