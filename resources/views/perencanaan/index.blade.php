@extends('template')
@section('content')
<div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <h5 class="mb-0 col-8">{{ $title }}</h5>
                <div class="col-4 text-end">
                  <a href="{{ route('perencanaan.add') }}" class="btn btn-success btn-xxs pull-right">
                      <li class="fa fa-plus" aria-hidden="true"></li> Tambah {{ $title }}
                  </a>
              </div>
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
              <select class="form-control" name="status_perencanaan" id="status_perencanaan">
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

<div class="modal fade" id="modal-main" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form method="post" action="{{ route('perencanaan.finish') }}" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pengajuan</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Catatan</label>
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="id" id="id" value="0">
          <textarea type="text" name="catatan_approve" id="catatan_approve" placeholder="Catatan Approve" class="form-control" value="{{ old('catatan_approve') }}" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn bg-gradient-success">Simpan</button>
      </div>
    </form>
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
        "url": "{{ route('perencanaan.list') }}",
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
            "status_perencanaan": function(){
              return $('#status_perencanaan').val()
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

    function finish(id){
      $('#id').val(id);
      $('#modal-main').modal('show');
    }
  
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
          $.get('{{ url("perencanaan/delete") }}/' + id, function(res) {
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