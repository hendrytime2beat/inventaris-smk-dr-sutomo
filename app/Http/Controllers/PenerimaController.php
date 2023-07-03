<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class PenerimaController extends Controller
{

    public function index()
    {
        return view('penerima.index', [
            'title' => 'Penerima',
            'pages' => ['Penerima', 'List Penerima'],
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
        if($request->post('status_penerima')){
            $where[] = ['status_penerima', '=', $request->post('status_penerima')];
        }
        $column_order   = ['id', 'nama_item','jumlah', 'harga', 'jenis', 'nama_kategori', 'nama_user_create', 'status_penerima', 'keterangan'];
        $column_search  = ['nama_item','jumlah', 'harga', 'jenis', 'nama_kategori', 'nama_user_create', 'status_penerima', 'keterangan'];
        $order = ['id' => 'DESC'];
        $list = GeneralModel::getDatatable('tb_penerima', $column_order, $column_search, $order, $where);
        $data = array();
        $no = $request->post('start');
        foreach ($list as $key) {
            $action = '';
            $finish = '&nbsp;<a class="btn btn-success btn-xxs mr-2" data-json=\''.json_encode($key).'\' data-action="Diterima" data-url="'.route('penerima.finish', $key->id).'" onclick="main.accept(this)"><li class="fa fa-check" aria-hidden="true"></li> Diterima</a>';
            $reject = '&nbsp;<a class="btn btn-danger btn-xxs mr-2" data-json=\''.json_encode($key).'\'data-action="Reject" data-url="'.route('penerima.reject', $key->id).'" onclick="main.reject(this)"><li class="fa fa-close" aria-hidden="true"></li> Reject</a>';
            $detail = '&nbsp;<a class="btn btn-primary btn-xxs mr-2" href="' . route('penerima.detail', $key->id) . '"><li class="fa fa-info" aria-hidden="true"></li> Detail</a>';
            $hapus = '&nbsp;<a class="btn btn-danger btn-xxs hidden" onclick="hapus(' . $key->id . ')"><li class="fa fa-trash" aria-hidden="true"></li> Hapus</a>';
            if(session('id_user_grup') == 1 || session('id_user_grup') == 5){
                $action = $finish.$reject.$detail.$hapus;
            } else {
                $action = $detail;
            }

            if($key->status_penerima == 'finish'){
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
            $row[] = $key->status_penerima;
            $row[] = $key->keterangan;
            $row[] = $action;
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => GeneralModel::countAll('tb_penerima', $where),
            "recordsFiltered" => GeneralModel::countFiltered('tb_penerima', $column_order, $column_search, $order, $where),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function detail(Request $request, $id)
    {
        return view('penerima.detail', [
            'title' => 'Detail Penerima',
            'pages' => [
                'Penerima',
                'Detail'
            ],
            'data' => GeneralModel::getRow('tb_penerima', '*', 'WHERE id="'.$id.'"')
        ]);
    }

    public function delete(Request $request, $id)
    {
        GeneralModel::setUpdate('tb_penerima', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
        $request->session()->flash('message', 'Sukses!, anda berhasil menghapus penerima');
        return redirect()->route('penerima');
    }

    public function reject(Request $request, $id)
    {
        $penerima = GeneralModel::getRow('tb_penerima', '*', 'WHERE id="'.$request->post('id').'"');
        GeneralModel::setUpdate('tb_penerima', [
            'status_penerima' => 'reject',
            'id_user_reject' => $request->session()->get('id_user'),
            'tanggal_tolak' => date('Y-m-d H:i:s'),
            'catatan_tolak' => $request->post('catatan_tolak')
        ], [
            'id' => $request->post('id')
        ]);
        $request->session()->flash('message', 'Sukses!, anda berhasil mereject penerima');
        return redirect()->route('penerima');
    }

    public function batal(Request $request, $id){
        $penerima = GeneralModel::getRow('tb_penerima', '*', 'WHERE id="'.$id.'"');
        GeneralModel::setUpdate('tb_penerima', [
            'id_user_batal' => $request->session()->get('id_user'),
            'tgl_batal' => date('Y-m-d H:i:s'),
            'status_penerima' => 'batal'
        ], [
            'id' => $id
        ]);
        $request->session()->flash('message', 'Sukses!, anda berhasil membatalkan penerima');
        return redirect()->route('penerima');
    }

    public function finish(Request $request){
        $id = $request->post('id');
        $penerima = GeneralModel::getRow('tb_penerima', '*', 'WHERE id="'.$request->post('id').'"');
        $data_penerima = [
            'nama_penerima' => $request->post('nama_penerima'),
            'status_penerima' => 'finish',
            'tanggal_terima' => $request->post('tanggal_terima'),
            'catatan_terima' => $request->post('catatan_terima'),
        ];
        if ($request->hasFile('berkas_1')) {
            $name_file = $request->file('berkas_1')->getClientOriginalName();
            $path = public_path('\assets\img\penerima');
            $request->file('berkas_1')->move($path, $name_file);
            $data_penerima['berkas_1'] = $name_file;
        }
        if ($request->hasFile('berkas_2')) {
            $name_file = $request->file('berkas_2')->getClientOriginalName();
            $path = public_path('\assets\img\penerima');
            $request->file('berkas_2')->move($path, $name_file);
            $data_penerima['berkas_2'] = $name_file;
        }
        if ($request->hasFile('berkas_3')) {
            $name_file = $request->file('berkas_3')->getClientOriginalName();
            $path = public_path('\assets\img\penerima');
            $request->file('berkas_3')->move($path, $name_file);
            $data_penerima['berkas_3'] = $name_file;
        }
        GeneralModel::setUpdate('tb_penerima', $data_penerima, [
            'id' => $request->post('id')
        ]);
        $request->session()->flash('message', 'Sukses!, anda berhasil menerima penerima');
        return redirect()->route('penerima');
    }

}
