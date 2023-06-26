@extends('template')
@section('content')

<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Saldo Anda</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{-- {{ \Helper::uang($data['saldo']) }} --}}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-collection text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    {{-- <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6>Transaksi Terbaru</h6>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tgl</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jenis Transaksi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pemasukan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pengeluaran</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($data['transactions'])
                                @foreach($data['transactions'] as $key)
                                    <tr>
                                        <td class="text-xxs text-center">{{ \Helper::tanggalwoah($key->transaction_date) }}</td>
                                        <td class="text-xxs text-center">{{ $key->transaction_type == 'income' ? 'Pemasukan' : 'Pengeluaran'}}</td>
                                        <td class="text-xxs text-center">{{ \Helper::uang($key->income) }}</td>
                                        <td class="text-xxs text-center">{{ \Helper::uang($key->expenditure) }}</td>
                                        <td class="text-xxs text-center">{{ \Helper::uang($key->saldo) }}</td>
                                    </tr>
                                @endforeach
                            @else 
                                <tr>
                                    <td class="text-xxs text-center text-black" colspan="4">Kosong</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}
</div>

@endSection