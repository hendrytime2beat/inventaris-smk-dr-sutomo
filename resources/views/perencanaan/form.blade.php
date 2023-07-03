@extends('template')
@section('content')
  <div class="row">
    <div class="col-md-12 mt-4">
      <div class="card">
        <div class="card-header pb-0 px-3">
          <h6 class="mb-0">{{ $title }}</h6>
        </div>
        <div class="card-body pt-4 p-0">
            
            <form method="post" enctype="multipart/form-data" action="{{ @$data->id ? route('perencanaan.edit.act', $data->id) : route('perencanaan.add.act') }}" class="card-body p-3">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id" value="{{ @$data->id ? @$data->id : 0 }}">
                @if(session('message'))
                <div class="alert alert-success">
                    <div class="small text-white">{{ session('message') }}</div>
                </div>
                @endif
                <div class="form-group">
                    <label>Nama Item</label>
                    @error('nama_item')
                    <bR><small class="text-danger">{{ $message }}</small>
                    @enderror
                    <input type="text" name="nama_item" id="nama_item" placeholder="Nama Item" class="form-control" value="{{ old('nama_item') ? old('nama_item') : @$data->nama_item }}">
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Kategori (KBKI)</label>
                            @error('id_kategori')
                            <bR><small class="text-danger">{{ $message }}</small>
                            @enderror            
                            <select class="form-control" name="id_kategori" id="id_kategori">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $key)
                                    <option value="{{ $key->id }}" {{ @$data->id_kategori == $key->id ? 'selected' : '' }}>{{ $key->nama_kategori }}</option>   
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label>Unit Kerja</label>
                            @error('id_unit_kerja')
                            <bR><small class="text-danger">{{ $message }}</small>
                            @enderror            
                            <select class="form-control" name="id_unit_kerja" id="id_unit_kerja">
                                <option value="">Pilih Unit Kerja</option>
                                @foreach ($unit_kerja as $key)
                                    <option value="{{ $key->id }}" {{ @$data->id_unit_kerja == $key->id ? 'selected' : '' }}>{{ $key->nama_unit_kerja }}</option>   
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Anggaran</label>
                            @error('id_anggaran')
                            <bR><small class="text-danger">{{ $message }}</small>
                            @enderror            
                            <select class="form-control" name="id_anggaran" id="id_anggaran">
                                <option value="">Pilih Anggaran</option>
                                @foreach ($anggaran as $key)
                                    <option value="{{ $key->id }}" {{ @$data->id_anggaran == $key->id ? 'selected' : '' }}>{{ $key->tahun }} ({{\Helper::uang($key->anggaran_awal)}})</option>   
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label>Jenis</label>
                            @error('jenis')
                            <bR><small class="text-danger">{{ $message }}</small>
                            @enderror            
                            <select class="form-control" name="jenis" id="jenis">
                                <option value="">Pilih Jenis</option>
                                <option value="bahan" {{ @$data->jenis == 'bahan' ? 'selected' : '' }}>Bahan</option>   
                                <option value="aset" {{ @$data->jenis == 'aset' ? 'selected' : '' }}>Aset</option>   
                                <option value="jasa" {{ @$data->jenis == 'jasa' ? 'selected' : '' }}>Jasa</option>   
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Jumlah</label>
                    @error('jumlah')
                    <bR><small class="text-danger">{{ $message }}</small>
                    @enderror
                    <input type="number" name="jumlah" id="jumlah" placeholder="Jumlah" class="form-control" value="{{ old('jumlah') ? old('jumlah') : @$data->jumlah }}">
                </div>
                <div class="form-group">
                    <label>Harga</label>
                    @error('harga')
                    <bR><small class="text-danger">{{ $message }}</small>
                    @enderror
                    <input type="number" name="harga" id="harga" placeholder="Harga" class="form-control" value="{{ old('harga') ? old('harga') : @$data->harga }}">
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    @error('keterangan')
                    <bR><small class="text-danger">{{ $message }}</small>
                    @enderror
                    <textarea name="keterangan" id="keterangan" style="height:25vh;" placeholder="Keterangan" class="form-control">{{ old('keterangan') ? old('keterangan') : @$data->keterangan }}</textarea>
                </div>
                <div class="form-group text-end">
                    <a type="button" href="{{ route('perencanaan') }}" class="btn btn-warning">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>

        </div>
      </div>
    </div>
  </div>
@endSection 