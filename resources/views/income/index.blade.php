@extends('template')
@section('content')
<div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <h5 class="mb-0 col-8">{{ $title }}</h5>
                <div class="col-4 text-end">
                  <a href="{{ route('income.add') }}" class="btn btn-primary btn-xxs pull-right">
                      <li class="fa fa-plus" aria-hidden="true"></li> Tambah {{ $title }}
                  </a>
              </div>
            </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
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
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah Transaksi</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deskripsi</th>
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
    $('#table-data').DataTable({
      "bProcessing": true,
      "bServerSide": true,
      "ajax": {
        "url": "{{ route('income.list') }}",
        "type": "POST",
        "data": {
            "_token": '{{ csrf_token() }}',
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
  
  
    function hapus(id) {
      swal({
        title: 'Anda yakin ingin menghapus ?',
        text: "Data yang dihapus tidak bisa dikembalikan",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        cancelButtonText: "Batal",
        padding: '2em'
      }).then(function(result) {
        if (result.value) {
          $.get('{{ url("produk/delete") }}/' + id, function(res) {
            swal(
              'Sukses!',
              'Data berhasil dihapus',
              'success'
            )
            location.reload();
          })
        }
      })
    }
  </script>
@endSection