@extends('template')
@section('content')
<div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header">
            <div class="row">
              <h5 class="mb-0 col-8">{{ $title }}</h5>
            </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          
          <div class="col-sm-12 p-4 row">
            <div class="col-sm-3">
              <label>Mulai</label>
              <input type="date" class="form-control" name="mulai" id="mulai" placeholder="Mulai" value="{{ date('Y-m-d', strtotime('-1 months', strtotime(date('Y-m-d')))) }}">
            </div>
            <div class="col-sm-3">
              <label>Selesai</label>
              <input type="date" class="form-control" name="selesai" id="selesai" placeholder="Selesai" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-sm-3">
              <label>Jenis</label>
              <select class="form-control" name="jenis" id="jenis">
                <option value="">Semua Jenis</option>
                <option value="barang">Barang</option>
                <option value="aset">Aset</option>
                <option value="jasa">Jasa</option>
              </select>
            </div>
            <div class="col-sm-3">
              <label>Kategori</label>
              <select class="form-control" name="id_kategori" id="id_kategori">
                <option value="">Semua Kategori</option>
                @foreach($kategori as $key)
                  <option value="{{ $key->id }}">{{ $key->nama_kategori }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-sm-3 mt-2">
              <label>Status</label>
              <select class="form-control" name="status_pengajuan" id="status_pengajuan">
                <option value="">Semua Status</option>
                <option value="request">Request</option>
                <option value="finish">Diajukan</option>
                <option value="batal">Batal</option>
              </select>
            </div>
            <div class="col-sm-3 mt-2">
              <button class="btn btn-success" style="margin-top:28px;" type="button" id="btn-cari"><li class="fa fa-search"></li> Cari</button>
            </div>
          </div>
          <br>
          <div class="col-sm-12 p-4 row">
            @foreach($anggaran as $key)
              <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ $key->tahun }} - {{ \Helper::uang($key->anggaran_awal)}}</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ \Helper::uang($key->anggaran_sisa) }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="ni ni-collection text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            @endforeach
          </div>
          <br>
          @if(session('message'))
          <div class="alert alert-success">
              <div class="small text-white">{{ session('message') }}</div>
          </div>
          @endif
          <div class="table-responsive p-4">
            <table class="table align-items-center mb-0" id="table-data">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Item</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Harga</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kategori</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User Input</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Catatan</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody class="text-black text-xxs ps-2 sorting text-center"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>

<script src="{{ asset('assets/import/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/import/dataTables.bootstrap5.min.js') }}"></script>

<script src="{{ asset('assets/import/sweetalert2.min.js') }}"></script>
<script>
    var table_data = $('#table-data').DataTable({
      "bProcessing": true,
      "bServerSide": true,
      "ajax": {
        "url": "{{ route('report.list') }}",
        "type": "POST",
        "data": {
            "_token": '{{ csrf_token() }}',
            "mulai": function(){
              return $('#mulai').val()
            },
            "selesai": function(){
              return $('#selesai').val()
            },
            "jenis": function(){
              return $('#jenis').val()
            },
            "id_kategori": function(){
              return $('#id_kategori').val()
            },
            "status_pengajuan": function(){
              return $('#status_pengajuan').val()
            },
        }
      },
      "stripeClasses": [],
      "stripeClasses": [],
      "lengthMenu": [
        [10, 20, 50, -1],
        [10, 20, 50, 'All']
      ],
      "order": [
        [0, 'desc']
      ],
      "pageLength": 20,
      drawCallback: function() {
        $('.page-link', this.api().table().container())
          .on('click', function() {
            var page_pagination = window.location;
            var pon = $(this).attr('data-dt-idx');
            var table = $('#table-data').DataTable();
            var info = table.page.info();
            var lengthMenuSetting = info.length;
            if (isNaN($(this).html()) == true) {
              if (pon == 0) {
                var page_length = table.context[0]._iDisplayStart - 20;
              } else {
                var page_length = parseInt(table.context[0]._iDisplayStart) + parseInt(20);
              }
            } else {
              var posisi = $(this).html();
              console.log(posisi);
              var page_length = posisi * lengthMenuSetting - parseInt(lengthMenuSetting);
            }
            localStorage.setItem(page_pagination, page_length);
          });
      },
      "displayStart": (localStorage.getItem(window.location) == null || localStorage.getItem(window.location) < 0) ? 0 : localStorage.getItem(window.location),
      "language": {
        "paginate": {
          "previous": "<",
          "next": ">"
        }
      }
    });
    
    $('#btn-cari').click(function(){
      table_data.ajax.reload();
    })
  
  </script>
@endSection