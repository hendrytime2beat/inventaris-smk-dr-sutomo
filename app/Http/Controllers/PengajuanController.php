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
                <a class="btn btn-success btn-xxs mr-2" onclick="finish('.$key->id.')"><li class="fa fa-check" aria-hidden="true"></li> Ajukan</a>
                &nbsp;
                <a class="btn btn-danger btn-xxs mr-2" href="' . route('pengajuan.batal', $key->id) . '"><li class="fa fa-close" aria-hidden="true"></li> Batal</a>
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

    public function form(Request $request, $id = '')
    {
        return view('pengajuan.form', [
            'title' => 'pengajuan',
            'pages' => ['pengajuan', 'Form pengajuan'],
            'kategori' => GeneralModel::getRes('m_kategori', '*', 'WHERE deleted_at IS NULL ORDER BY nama_kategori'),
            'anggaran' => GeneralModel::getRes('conf_anggaran', '*', 'WHERE deleted_at IS NULL ORDER BY tahun'),
            'unit_kerja' => GeneralModel::getRes('conf_unit_kerja', '*', 'WHERE deleted_at IS NULL ORDER BY nama_unit_kerja'),
            'data' => $id ? GeneralModel::getRow('tb_pengajuan', '*', 'WHERE id="'.$id.'"') : ''
        ]);
    }
    

    public function act(Request $request, $id='')
    {
        $validator = Validator::make($request->all(), [
            'id_kategori' => 'required',
            'id_anggaran' => 'required',
            'nama_item' => 'required',
            'jenis' => 'required',
            'harga' => 'required',
            'keterangan' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = [
            'id_kategori' => $request->post('id_kategori'),
            'id_unit_kerja' => $request->post('id_unit_kerja'),
            'id_anggaran' => $request->post('id_anggaran'),
            'nama_item' => $request->post('nama_item'),
            'anggaran' => $request->post('id_anggaran') ? GeneralModel::getRow('conf_anggaran', 'anggaran_awal', 'WHERE id="'.$request->post('id_anggaran').'"')->anggaran_awal : '',
            'jenis' => $request->post('jenis'),
            'nama_kategori' => $request->post('id_kategori') ? GeneralModel::getRow('m_kategori', 'nama_kategori', 'WHERE id="'.$request->post('id_kategori').'"')->nama_kategori : '',
            'harga' => $request->post('harga'),
            'keterangan' => $request->post('keterangan')
        ];
        if(empty($id)){
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['id_user_create'] = $request->session()->get('id_user');
            $data['nama_user_create'] = $request->session()->get('nama');
            GeneralModel::setInsert('tb_pengajuan', $data);
            $request->session()->flash('message', 'Sukses!, anda berhasil menambahkan pengajuan');
        } else {
            $data['updated_at'] = date('Y-m-d H:i:s');
            GeneralModel::setUpdate('tb_pengajuan', $data, ['id' => $id]);
            $request->session()->flash('message', 'Sukses!, anda berhasil memperbarui Perenacanaan');
        }
        return redirect()->route('pengajuan');
    }
    
    public function detail(Request $request, $id)
    {
        return view('pengajuan.detail', [
            'title' => 'Detail pengajuan',
            'pages' => [
                'pengajuan',
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

    public function batal(Request $request, $id){
        $pengajuan = GeneralModel::getRow('tb_pengajuan', '*', 'WHERE id="'.$id.'"');
        GeneralModel::setUpdate('tb_pengajuan', [
            'id_user_batal' => $request->session()->get('id_user'),
            'tgl_batal' => date('Y-m-d H:i:s'),
            'status_pengajuan' => 'batal'
        ], [
            'id' => $id
        ]);
        $request->session()->flash('message', 'Sukses!, anda berhasil membatalkan pengajuan');
        return redirect()->route('pengajuan');
    }

    public function finish(Request $request){
        $pengajuan = GeneralModel::getRow('tb_pengajuan', '*', 'WHERE id="'.$request->post('id').'"');
        GeneralModel::setUpdate('tb_pengajuan', [
            'id_user_approve' => $request->session()->get('id_user'),
            'tgl_approve' => date('Y-m-d H:i:s'),
            'catatan_approve' => $request->post('catatan_approve')
        ], [
            'id' => $request->post('id')
        ]);
        GeneralModel::setInsert('tb_pengajuan', [
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
            'status_pengajuan' => 'request',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $request->session()->flash('message', 'Sukses!, anda berhasil mengajukan pengajuan');
        return redirect()->route('pengajuan');
    }

}
