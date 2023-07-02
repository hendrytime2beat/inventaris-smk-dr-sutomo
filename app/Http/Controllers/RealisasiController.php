<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class RealisasiController extends Controller
{

    public function index()
    {
        return view('realisasi.index', [
            'title' => 'Realisasi',
            'pages' => ['Realisasi', 'List Realisasi'],
            'kategori' => GeneralModel::getRes('m_kategori', '*', 'WHERE deleted_at IS NULL ORDER BY nama_kategori')
        ]);
    }

    public function list(Request $request)
    {
        $where[] = ['deleted_at', '',  '', 'NULL'];   
        if($request->post('mulai') && $request->post('selesai')){
            $where[] = ['created_at', '>=', $request->post('mulai'), 'DATE'];
            $where[] = ['created_at', '<=', $request->post('selesai'), 'DATE'];
        }
        if($request->post('jenis')){
            $where[] = ['jenis', '=', $request->post('jenis')];
        }
        if($request->post('id_kategori')){
            $where[] = ['id_kategori', '=', $request->post('id_kategori')];
        }
        if($request->post('id_kategori')){
            $where[] = ['id_kategori', '=', $request->post('id_kategori')];
        }
        if($request->post('status_realisasi')){
            $where[] = ['status_realisasi', '=', $request->post('status_realisasi')];
        }
        $column_order   = ['id', 'nama_item', 'harga', 'jenis', 'nama_kategori', 'nama_user_create', 'status_realisasi', 'keterangan'];
        $column_search  = ['nama_item', 'harga', 'jenis', 'nama_kategori', 'nama_user_create', 'status_realisasi', 'keterangan'];
        $order = ['id' => 'DESC'];
        $list = GeneralModel::getDatatable('tb_realisasi', $column_order, $column_search, $order, $where);
        $data = array();
        $no = $request->post('start');
        foreach ($list as $key) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $key->nama_item;
            $row[] = \Helper::uang($key->harga);
            $row[] = $key->jenis;
            $row[] = $key->nama_kategori;
            $row[] = $key->nama_user_create;
            $row[] = $key->status_realisasi;
            $row[] = $key->keterangan;
            $row[] = '
                <a class="btn btn-success btn-xxs mr-2" data-json=\''.json_encode($key).'\' data-action="Diterima" data-url="'.route('realisasi.finish', $key->id).'" onclick="main.accept(this)"><li class="fa fa-check" aria-hidden="true"></li> Diterima</a>
                &nbsp;
                <a class="btn btn-danger btn-xxs mr-2" data-json=\''.json_encode($key).'\'data-action="Reject" data-url="'.route('realisasi.reject', $key->id).'" onclick="main.reject(this)"><li class="fa fa-close" aria-hidden="true"></li> Reject</a>
                &nbsp;
                <a class="btn btn-primary btn-xxs mr-2" href="' . route('realisasi.detail', $key->id) . '"><li class="fa fa-info" aria-hidden="true"></li> Detail</a>
                &nbsp;
                <a class="btn btn-danger btn-xxs" onclick="hapus(' . $key->id . ')"><li class="fa fa-trash" aria-hidden="true"></li> Hapus</a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => GeneralModel::countAll('tb_realisasi', $where),
            "recordsFiltered" => GeneralModel::countFiltered('tb_realisasi', $column_order, $column_search, $order, $where),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function detail(Request $request, $id)
    {
        return view('realisasi.detail', [
            'title' => 'Detail Realisasi',
            'pages' => [
                'Realisasi',
                'Detail'
            ],
            'data' => GeneralModel::getRow('tb_realisasi', '*', 'WHERE id="'.$id.'"')
        ]);
    }

    public function delete(Request $request, $id)
    {
        GeneralModel::setUpdate('tb_realisasi', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
        $request->session()->flash('message', 'Sukses!, anda berhasil menghapus Realisasi');
        return redirect()->route('realisasi');
    }

    public function reject(Request $request, $id)
    {
        $realisasi = GeneralModel::getRow('tb_realisasi', '*', 'WHERE id="'.$request->post('id').'"');
        GeneralModel::setUpdate('tb_realisasi', [
            'status_realisasi' => 'reject',
            'id_user_reject' => $request->session()->get('id_user'),
            'tgl_reject' => date('Y-m-d H:i:s'),
            'catatan_reject' => $request->post('catatan')
        ], [
            'id' => $request->post('id')
        ]);
        $request->session()->flash('message', 'Sukses!, anda berhasil mereject Realisasi');
        return redirect()->route('realisasi');
    }

    public function batal(Request $request, $id){
        $realisasi = GeneralModel::getRow('tb_realisasi', '*', 'WHERE id="'.$id.'"');
        GeneralModel::setUpdate('tb_realisasi', [
            'id_user_batal' => $request->session()->get('id_user'),
            'tgl_batal' => date('Y-m-d H:i:s'),
            'status_realisasi' => 'batal'
        ], [
            'id' => $id
        ]);
        $request->session()->flash('message', 'Sukses!, anda berhasil membatalkan Realisasi');
        return redirect()->route('realisasi');
    }

    public function finish(Request $request){
        $id = $request->post('id');
        $realisasi = GeneralModel::getRow('tb_realisasi', '*', 'WHERE id="'.$request->post('id').'"');
        $anggaran = GeneralModel::getRow('conf_anggaran', '*', 'WHERE id="'.$realisasi->id_anggaran.'"');
        $anggaran_sebelum = $anggaran->anggaran_sisa;
        $anggaran_sesudah = $anggaran_sebelum-$realisasi->harga;
        $data_realisasi = [
            'id_user_approve' => $request->session()->get('id_user'),
            'tgl_approve' => date('Y-m-d H:i:s'),
            'catatan_approve' => $request->post('catatan'),
            'status_realisasi' => 'finish',
            'nama_vendor' => $request->post('nama_vendor'),
            'eta' => $request->post('eta'),
            'sisa_anggaran_sebelum' => $anggaran_sebelum,
            'sisa_anggaran_sesudah' => $anggaran_sesudah,
        ];
        if ($request->hasFile('foto_1')) {
            $name_file = $request->file('foto_1')->getClientOriginalName();
            $path = public_path('\assets\img\realisasi');
            $request->file('foto_1')->move($path, $name_file);
            $data_realisasi['foto_1'] = $name_file;
        }
        if ($request->hasFile('foto_2')) {
            $name_file = $request->file('foto_2')->getClientOriginalName();
            $path = public_path('\assets\img\realisasi');
            $request->file('foto_2')->move($path, $name_file);
            $data_realisasi['foto_2'] = $name_file;
        }
        if ($request->hasFile('foto_3')) {
            $name_file = $request->file('foto_3')->getClientOriginalName();
            $path = public_path('\assets\img\realisasi');
            $request->file('foto_3')->move($path, $name_file);
            $data_realisasi['foto_3'] = $name_file;
        }
        GeneralModel::setUpdate('tb_realisasi', $data_realisasi, [
            'id' => $request->post('id')
        ]);
        GeneralModel::setUpdate('conf_anggaran', [
            'anggaran_sisa' => $anggaran_sesudah
        ], [
            'id' => $id
        ]);
        GeneralModel::setInsert('tb_riwayat_anggaran', [
            'id_anggaran' => $realisasi->id_anggaran,
            'id_realisasi' => $id,
            'tgl_transaksi' => date('Y-m-d H:i:s'),
            'awal' => $anggaran->anggaran_awal,
            'keluar' => $realisasi->harga,
            'masuk' => 0,
            'sisa' => $anggaran_sesudah,
        ]);
        $realisasi_after = GeneralModel::getRow('tb_realisasi', '*', 'WHERE id="'.$request->post('id').'"');
        $data_penerima = [
            'id_perencanaan' => $realisasi_after->id_perencanaan,
            'id_pengajuan' => $realisasi_after->id_pengajuan,
            'id_realisasi' => $request->post('id'),
            'id_kategori' => $realisasi_after->id_kategori,
            'id_user_create' => $request->session()->get('id_user'),
            'id_unit_kerja' => $realisasi_after->id_unit_kerja,
            'id_anggaran' => $realisasi_after->id_anggaran,
            'nama_item' => $realisasi_after->nama_item,
            'anggaran' => $realisasi_after->anggaran,
            'jenis' => $realisasi_after->jenis,
            'nama_kategori' => $realisasi_after->nama_kategori,
            'harga' => $realisasi_after->harga,
            'keterangan' => $realisasi_after->keterangan,
            'nama_user_create' => $request->session()->get('nama'),
            'status_penerima' => 'request',
            'created_at' => date('Y-m-d H:i:s')
        ];
        // print_r($data_realisasi);die;
        GeneralModel::setInsert('tb_penerima', $data_penerima);
        $id_penerima = GeneralModel::getId();
        $request->session()->flash('message', 'Sukses!, anda berhasil menerima realisasi');
        return redirect()->route('realisasi');
    }

}
