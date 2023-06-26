@extends('template')
@section('content')
  <div class="row">
    <div class="col-md-12 mt-4">
      <div class="card">
        <div class="card-header pb-0 px-3">
          <h6 class="mb-0">{{ $title }}</h6>
        </div>
        <div class="card-body pt-4 p-0">
            
            <form method="post" enctype="multipart/form-data" action="{{ @$data->id ? route('expenditure.edit.act', $data->id) : route('expenditure.add.act') }}" class="card-body p-3">
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
                            <label>Tanggal Transaksi</label>
                            @error('title')
                            <bR><small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input type="date" name="transaction_date" id="transaction_date" placeholder="Tanggal Transaksi" class="form-control" value="{{ old('transaction_date') ? old('transaction_date') : @$data->transaction_date }}">
                        </div>
                        <div class="col-sm-6">
                            <label>Jam Transaksi</label>
                            @error('title')
                            <bR><small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input type="time" name="transaction_time" id="transaction_time" placeholder="Jam Transaksi" class="form-control" value="{{ old('transaction_time') ? old('transaction_time') : @$data->transaction_time }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Jumlah Transaksi</label>
                    @error('amount')
                    <bR><small class="text-danger">{{ $message }}</small>
                    @enderror
                    <input type="text" name="amount" id="amount" placeholder="Jumlah Transaksi" class="form-control" value="{{ old('amount') ? old('amount') : @$data->amount }}">
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    @error('description')
                    <bR><small class="text-danger">{{ $message }}</small>
                    @enderror
                    <textarea name="description" id="description" style="height:25vh;" placeholder="Deskripsi" class="form-control">{{ old('description') ? old('description') : @$data->description }}</textarea>
                </div>
                <div class="form-group">
                    <label>Catatan</label>
                    @error('notes')
                    <bR><small class="text-danger">{{ $message }}</small>
                    @enderror
                    <textarea name="notes" id="notes" style="height:25vh;" placeholder="Catatan" class="form-control">{{ old('notes') ? old('notes') : @$data->notes }}</textarea>
                </div>
                <div class="form-group text-end">
                    <a type="button" href="{{ route('expenditure') }}" class="btn btn-warning">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>
      </div>
    </div>
  </div>
@endSection 