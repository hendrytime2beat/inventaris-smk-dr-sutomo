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
              <button class="btn btn-success" style="margin-top:28px;" type="button" id="btn-cari">Cari</button>
            </div>
          </div>
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
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal Transaksi</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipe Transaksi</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pemasukan</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pengeluaran</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Saldo</th>
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