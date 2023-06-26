@extends('template')
@section('content')
<div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <h5 class="mb-0 col-8">{{ $title }}</h5>
                <div class="col-4 text-end">
                    <a onclick="main.add()" class="btn btn-success btn-xxs pull-right">
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
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tahun</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Anggaran Awal</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Anggaran Sisa</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody class=" text-xxs font-weight-bolder opacity-7 ps-2 sorting"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="modal-main" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form method="post" action="{{ route('config.anggaran.act') }}" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Kategori Form</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Tahun</label>
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="id" id="id" value="{{ old('id') ? old('id') : 0 }}">  
          @error('tahun')
            <div class="sama">
              <br><small class="text-danger"><i>{{ $message }}</i></small>
            </div>
          @enderror
          <input type="number" name="tahun" id="tahun" placeholder="Tahun" class="form-control" value="{{ old('tahun') }}" required>
        </div>
        <div class="form-group">
          <label>Anggaran</label>
          @error('anggaran')
            <div class="sama">
              <br><small class="text-danger"><i>{{ $message }}</i></small>
            </div>
          @enderror
          <input type="number" name="anggaran" id="anggaran" placeholder="Anggaran" class="form-control" value="{{ old('anggaran') }}" required>
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

@error('nama_grup')
{!! "<script>
$( document ).ready(function() {
  $('#modal-main').modal('show');
});
</script>" !!}
@enderror


<script>
    $('#table-data').DataTable({
      "bProcessing": true,
      "bServerSide": true,
      "ajax": {
        "url": "{{ route('config.anggaran.list') }}",
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

    const main = {
      add: function(){
        $('#id').val(0);
        $('#anggaran').val('');
        $('.sama').html('');
        $('#modal-main').modal('show');
      },
      edit: function(that){
        var data = JSON.stringify($(that).data('data'));
        data = JSON.parse(data);
        $('#id').val(data.id);
        $('#anggaran').val(data.anggaran_awal);
        $('.sama').html('');
        $('#modal-main').modal('show');
      }
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
          $.get('{{ url("config/anggaran/delete") }}/' + id, function(res) {
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