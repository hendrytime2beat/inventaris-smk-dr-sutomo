<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class PengajuanController extends Controller
{

    public function index()
    {
        return view('pengajuan.index', [
            'title' => 'Pengajuan',
            'pages' => ['Pengajuan', 'List pengajuan'],
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
        if($request->post('status_pengajuan')){
            $where[] = ['status_pengajuan', '=', $request->post('status_pengajuan')];
        }
        $column_order   = ['id', 'nama_item', 'harga', 'jenis', 'nama_kategori', 'nama_user_create', 'status_pengajuan', 'keterangan'];
        $column_search  = ['nama_item', 'harga', 'jenis', 'nama_kategori', 'nama_user_create', 'status_pengajuan', 'keterangan'];
        $order = ['id' => 'DESC'];
        $list = GeneralModel::getDatatable('tb_pengajuan', $column_order, $column_search, $order, $where);
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
            $row[] = $key->status_pengajuan;
            $row[] = $key->keterangan;
            $row[] = '
                <a class="btn btn-success btn-xxs mr-2" data-json=\''.json_encode($key).'\'  onclick="main.finish(this)"><li class="fa fa-check" aria-hidden="true"></li> Relisasi</a>
                &nbsp;
                <a class="btn btn-success btn-xxs mr-2" data-json=\''.json_encode($key).'\'  onclick="main.reject(this)"><li class="fa fa-check" aria-hidden="true"></li> Reject</a>
                &nbsp;
                <a class="btn btn-danger btn-xxs mr-2" data-json=\''.json_encode($key).'\'  onclick="main.batal(this)"><li class="fa fa-close" aria-hidden="true"></li> Batal</a>
                &nbsp;
                <a class="btn btn-primary btn-xxs mr-2" href="' . route('pengajuan.detail', $key->id) . '"><li class="fa fa-info" aria-hidden="true"></li> Detail</a>
                &nbsp;
                <a class="btn btn-danger btn-xxs" onclick="hapus(' . $key->id . ')"><li class="fa fa-trash" aria-hidden="true"></li> Hapus</a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => GeneralModel::countAll('tb_pengajuan', $where),
            "recordsFiltered" => GeneralModel::countFiltered('tb_pengajuan', $column_order, $column_search, $order, $where),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function detail(Request $request, $id)
    {
        return view('pengajuan.detail', [
            'title' => 'Detail Pengajuan',
            'pages' => [
                'Pengajuan',
                'Detail'
            ],
            'data' => GeneralModel::getRow('tb_pengajuan', '*', 'WHERE id="'.$id.'"')
        ]);
    }

    public function delete(Request $request, $id)
    {
        GeneralModel::setUpdate('tb_pengajuan', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
        $request->session()->flash('message', 'Sukses!, anda berhasil menghapus pengajuan');
        return redirect()->route('pengajuan');
    }

    public function reject(Request $request, $id)
    {
        $pengajuan = GeneralModel::getRow('tb_pengajuan', '*', 'WHERE id="'.$request->post('id').'"');
        GeneralModel::setUpdate('tb_pengajuan', [
            'status_pengajuan' => 'reject',
            'id_user_reject' => $request->session()->get('id_user'),
            'tgl_reject' => date('Y-m-d H:i:s'),
            'catatan_reject' => $request->post('catatan_reject')
        ], [
            'id' => $request->post('id')
        ]);
        $request->session()->flash('message', 'Sukses!, anda berhasil mereject Pengajuan');
        return redirect()->route('pengajuan');
    }

    public function batal(Request $request, $id){
        $pengajuan = GeneralModel::getRow('tb_pengajuan', '*', 'WHERE id="'.$id.'"');
        GeneralModel::setUpdate('tb_pengajuan', [
            'id_user_batal' => $request->session()->get('id_user'),
            'tgl_batal' => date('Y-m-d H:i:s'),
            'status_pengajuan' => 'batal'
        ], [
            'id' => $id
        ]);
        $request->session()->flash('message', 'Sukses!, anda berhasil membatalkan Pengajuan');
        return redirect()->route('pengajuan');
    }

    public function finish(Request $request){
        $pengajuan = GeneralModel::getRow('tb_pengajuan', '*', 'WHERE id="'.$request->post('id').'"');
        GeneralModel::setUpdate('tb_pengajuan', [
            'id_user_approve' => $request->session()->get('id_user'),
            'tgl_approve' => date('Y-m-d H:i:s'),
            'catatan_approve' => $request->post('catatan_approve'),
            'status_pengajuan' => 'finish'
        ], [
            'id' => $request->post('id')
        ]);
        $anggaran = GeneralModel::getRow('tb_anggaran', '*', 'WHERE id="'.$pengajuan->id_anggaran.'"');
        $anggaran_sebelum = $anggaran->anggaran_sisa;
        $anggaran_sesudah = $anggaran_sebelum-$pengajuan->harga;
        $data_realisasi = [
            'id_perencanaan' => $pengajuan->id_perencanaan,
            'id_pengajuan' => $request->post('id'),
            'id_kategori' => $pengajuan->id_kategori,
            'id_user_create' => $request->session()->get('id_user'),
            'id_unit_kerja' => $pengajuan->id_unit_kerja,
            'id_anggaran' => $pengajuan->id_anggaran,
            'nama_item' => $pengajuan->nama_item,
            'anggaran' => $pengajuan->anggaran,
            'jenis' => $pengajuan->jenis,
            'nama_kategori' => $pengajuan->nama_kategori,
            'harga' => $pengajuan->harga,
            'keterangan' => $pengajuan->keterangan,
            'nama_user_create' => $request->session()->get('nama'),
            'nama_vendor' => $request->post('nama_vendor'),
            'eta' => $request->post('eta'),
            'sisa_anggaran_sebelum' => $sisa_anggaran_sebelum,
            'sisa_anggaran_sesudah' => $sisa_anggaran_sesudah,
            'status_realisasi' => 'request',
            'created_at' => date('Y-m-d H:i:s')
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
        GeneralModel::setInsert('tb_realisasi', $data_realisasi);
        $id_realisasi = GeneralModel::getId();
        GeneralModel::setUpdate('conf_anggaran', [
            'anggaran_sisa' => $anggaran_sesudah
        ], [
            'id' => $pengajuan->id_anggaran
        ]);
        GeneralModel::setInsert('tb_riwayat_anggaran', [
            'id_anggaran' => $pengajuan->id_anggaran,
            'id_realisasi' => $id_realisasi,
            'tgl_transaksi' => date('Y-m-d H:i:s'),
            'awal' => $anggaran->anggaran_awal,
            'keluar' => $pengajuan->harga,
            'masuk' => 0,
            'sisa' => $anggaran_sisa,
        ]);
        $request->session()->flash('message', 'Sukses!, anda berhasil menrealisasi pengajuan');
        return redirect()->route('pengajuan');
    }

}
