<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class PerencanaanController extends Controller
{

    public function index()
    {
        return view('perencanaan.index', [
            'title' => 'Perencanaan',
            'pages' => ['Perencanaan', 'List Perencanaan'],
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
        if($request->post('status_perencanaan')){
            $where[] = ['status_perencanaan', '=', $request->post('status_perencanaan')];
        }
        if($request->session()->get('id_user_grup') != 1){
            $where[] = ['id_unit_kerja', '=', $request->session()->get('id_unit_kerja'), ''];
        }
        $column_order   = ['id', 'nama_item', 'jumlah', 'harga', 'jenis', 'nama_kategori', 'nama_user_create', 'status_perencanaan', 'keterangan'];
        $column_search  = ['nama_item', 'harga', 'jenis', 'nama_kategori', 'nama_user_create', 'status_perencanaan', 'keterangan'];
        $order = ['id' => 'DESC'];
        $list = GeneralModel::getDatatable('tb_perencanaan', $column_order, $column_search, $order, $where);
        $data = array();
        $no = $request->post('start');
        foreach ($list as $key) {
            $finish = '&nbsp;<a class="btn btn-success btn-xxs mr-2" onclick="finish('.$key->id.')"><li class="fa fa-check" aria-hidden="true"></li> Ajukan</a>';
            $batal = '&nbsp;<a class="btn btn-danger btn-xxs mr-2" href="' . route('perencanaan.batal', $key->id) . '"><li class="fa fa-close" aria-hidden="true"></li> Batal</a>';
            $detail = '&nbsp;<a class="btn btn-primary btn-xxs mr-2" href="' . route('perencanaan.detail', $key->id) . '"><li class="fa fa-info" aria-hidden="true"></li> Detail</a>';
            $edit = '&nbsp;<a class="btn btn-warning btn-xxs mr-2" href="' . route('perencanaan.edit', $key->id) . '"><li class="fa fa-edit" aria-hidden="true"></li> Edit</a>';
            $hapus = '&nbsp;<a class="btn btn-danger btn-xxs" onclick="hapus(' . $key->id . ')"><li class="fa fa-trash" aria-hidden="true"></li> Hapus</a>';
            if($key->status_perencanaan != 'request'){
                $finish = '';
                $batal = '';
                $hapus = '';
                $edit = '';
            }
            if($request->session()->get('id_user_grup') == 1){
                $action = $finish.$batal.$detail.$edit.$hapus;
            } else if($request->session()->get('id_user_grup') == 2) {
                $action = $detail.$edit.$hapus;
            } else if($request->session()->get('id_user_grup') == 3) {
                $action = $detail.$finish.$batal;
            } else {
                $action = $detail;
            }
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $key->nama_item;
            $row[] = $key->jumlah;
            $row[] = \Helper::uang($key->harga);
            $row[] = $key->jenis;
            $row[] = $key->nama_kategori;
            $row[] = $key->nama_user_create;
            $row[] = $key->status_perencanaan;
            $row[] = $key->keterangan;
            $row[] = $action;
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => GeneralModel::countAll('tb_perencanaan', $where),
            "recordsFiltered" => GeneralModel::countFiltered('tb_perencanaan', $column_order, $column_search, $order, $where),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function form(Request $request, $id = '')
    {
        return view('perencanaan.form', [
            'title' => 'Perencanaan',
            'pages' => ['Perencanaan', 'Form Perencanaan'],
            'kategori' => GeneralModel::getRes('m_kategori', '*', 'WHERE deleted_at IS NULL ORDER BY nama_kategori'),
            'anggaran' => GeneralModel::getRes('conf_anggaran', '*', 'WHERE deleted_at IS NULL ORDER BY tahun'),
            'unit_kerja' => $request->session()->get('id_user_grup') == 1 ? GeneralModel::getRes('conf_unit_kerja', '*', 'WHERE deleted_at IS NULL ORDER BY nama_unit_kerja') : GeneralModel::getRes('conf_unit_kerja', '*', 'WHERE deleted_at IS NULL AND id="'.$request->session()->get('id_unit_kerja').'" ORDER BY nama_unit_kerja'),
            'data' => $id ? GeneralModel::getRow('tb_perencanaan', '*', 'WHERE id="'.$id.'"') : ''
        ]);
    }
    

    public function act(Request $request, $id='')
    {
        $validator = Validator::make($request->all(), [
            'id_kategori' => 'required',
            'id_anggaran' => 'required',
            'nama_item' => 'required',
            'jenis' => 'required',
            'jumlah' => 'required',
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
            'jumlah' => $request->post('jumlah'),
            'harga' => $request->post('harga'),
            'total' => $request->post('harga')*$request->post('jumlah'),
            'keterangan' => $request->post('keterangan')
        ];
        if(empty($id)){
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['id_user_create'] = $request->session()->get('id_user');
            $data['nama_user_create'] = $request->session()->get('nama');
            GeneralModel::setInsert('tb_perencanaan', $data);
            $request->session()->flash('message', 'Sukses!, anda berhasil menambahkan Perencanaan');
        } else {
            $data['updated_at'] = date('Y-m-d H:i:s');
            GeneralModel::setUpdate('tb_perencanaan', $data, ['id' => $id]);
            $request->session()->flash('message', 'Sukses!, anda berhasil memperbarui Perenacanaan');
        }
        return redirect()->route('perencanaan');
    }
    
    public function detail(Request $request, $id)
    {
        return view('perencanaan.detail', [
            'title' => 'Detail Perencanaan',
            'pages' => [
                'Perencanaan',
                'Detail'
            ],
            'data' => GeneralModel::getRow('tb_perencanaan', '*', 'WHERE id="'.$id.'"')
        ]);
    }

    public function delete(Request $request, $id)
    {
        GeneralModel::setUpdate('tb_perencanaan', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
        $request->session()->flash('message', 'Sukses!, anda berhasil menghapus Perencanaan');
        return redirect()->route('perencanaan');
    }

    public function batal(Request $request, $id){
        $perencanaan = GeneralModel::getRow('tb_perencanaan', '*', 'WHERE id="'.$id.'"');
        GeneralModel::setUpdate('tb_perencanaan', [
            'id_user_batal' => $request->session()->get('id_user'),
            'tgl_batal' => date('Y-m-d H:i:s'),
            'status_perencanaan' => 'batal'
        ], [
            'id' => $id
        ]);
        $request->session()->flash('message', 'Sukses!, anda berhasil membatalkan Perencanaan');
        return redirect()->route('perencanaan');
    }

    public function finish(Request $request){
        $perencanaan = GeneralModel::getRow('tb_perencanaan', '*', 'WHERE id="'.$request->post('id').'"');
        GeneralModel::setUpdate('tb_perencanaan', [
            'status_perencanaan' => 'finish',
            'id_user_approve' => $request->session()->get('id_user'),
            'tgl_approve' => date('Y-m-d H:i:s'),
            'catatan_approve' => $request->post('catatan_approve')
        ], [
            'id' => $request->post('id')
        ]);
        GeneralModel::setInsert('tb_pengajuan', [
            'id_perencanaan' => $request->post('id'),
            'id_kategori' => $perencanaan->id_kategori,
            'id_user_create' => $request->session()->get('id_user'),
            'id_unit_kerja' => $perencanaan->id_unit_kerja,
            'id_anggaran' => $perencanaan->id_anggaran,
            'nama_item' => $perencanaan->nama_item,
            'anggaran' => $perencanaan->anggaran,
            'jenis' => $perencanaan->jenis,
            'nama_kategori' => $perencanaan->nama_kategori,
            'jumlah' => $perencanaan->jumlah,
            'harga' => $perencanaan->harga,
            'total' => $perencanaan->total,
            'keterangan' => $perencanaan->keterangan,
            'nama_user_create' => $request->session()->get('nama'),
            'status_pengajuan' => 'request',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $request->session()->flash('message', 'Sukses!, anda berhasil mengajukan Perencanaan');
        return redirect()->route('perencanaan');
    }

}
