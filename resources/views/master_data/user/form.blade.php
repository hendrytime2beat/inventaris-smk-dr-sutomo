@extends('template')
@section('content')
  <div class="row">
    <div class="col-md-12 mt-4">
      <div class="card">
        <div class="card-header pb-0 px-3">
          <h6 class="mb-0">{{ $title }}</h6>
        </div>
        <div class="card-body pt-4 p-0">
            
            <form method="post" enctype="multipart/form-data" action="{{ route('master_data.user.act') }}" class="card-body p-3">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id" value="{{ @$data->id ? @$data->id : 0 }}">
                @if(session('message'))
                <div class="alert alert-success">
                    <div class="small text-white">{{ session('message') }}</div>
                </div>
                @endif
                <div class="form-group">
                    <label>Nama User</label>
                    @error('nama')
                    <bR><small class="text-danger">{{ $message }}</small>
                    @enderror
                    <input type="text" name="nama" id="nama" placeholder="Nama" class="form-control" value="{{ old('nama') ? old('nama') : @$data->nama }}">
                </div>
                <div class="form-group">
                    <label>Grup</label>
                    @error('code_book')
                    <bR><small class="text-danger">{{ $message }}</small>
                    @enderror
                    <select class="form-control" name="id_user_grup" id="id_user_grup">
                        <option value="">Pilih Grup</option>
                        @foreach ($user_grup as $key)
                            <option value="{{ $key->id }}" {{ @$data->id_user_grup == $key->id ? 'selected' : '' }}>{{ $key->nama_grup }}</option>   
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Username</label>
                            @error('username')
                            <bR><small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input type="text" name="username" id="username" placeholder="Username" class="form-control" value="{{ old('username') ? old('username') : @$data->username }}">
                        </div>
                        <div class="col-sm-6">
                            <label>Password</label>
                            @error('password')
                            <bR><small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input type="password" name="password" id="password" placeholder="Password" class="form-control" value="{{ old('password') ? old('password') : '' }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    @error('email')
                    <bR><small class="text-danger">{{ $message }}</small>
                    @enderror
                    <input type="text" name="email" id="email" placeholder="Email" class="form-control" value="{{ old('email') ? old('email') : @$data->email }}">
                </div>
                <div class="form-group">
                    <label>No Handphone</label>
                    @error('no_hp')
                    <bR><small class="text-danger">{{ $message }}</small>
                    @enderror
                    <input type="number" name="no_hp" id="no_hp" placeholder="No Handphone" class="form-control" value="{{ old('no_hp') ? old('no_hp') : @$data->no_hp }}">
                </div>
                <div class="form-group">
                    <label>Foto Profil</label>     
                    @error('foto_profil')
                    <bR><small class="text-danger">{{ $message }}</small>
                    @enderror
                    <div class="alert alert-success text-center col-sm-4">
                        <img id="blah_foto_profil" src="{{ @$data->foto_profil ? asset('assets/img/foto_profil/'.$data->foto_profil) : asset('assets/img/logo/logo.png') }}" style="width:200px;" onerror="imgError(this)" alt="..." loading="lazy">
                    </div>
                    <input class="form-control" name="cover" style="display:none;" id="foto_profil" type="file" onchange="readURL(this, 'foto_profil');">
                    <button class="btn btn-outline-success btn-sm" type="button" onclick="$('#foto_profil').click();">Upload Foto</button>
                </div>
                <div class="form-group text-end">
                    <a type="button" href="{{ route('master_data.user') }}" class="btn btn-warning">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>
      </div>
    </div>
  </div>
@endSection 