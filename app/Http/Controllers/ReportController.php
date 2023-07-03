<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{

    public function index()
    {
        return view('report.index', [
            'title' => 'Laporan',
            'pages' => ['Laporan'],
            'kategori' => GeneralModel::getRes('m_kategori', '*', 'WHERE deleted_at IS NULL ORDER BY nama_kategori'),
            'anggaran' => GeneralModel::getRes('conf_anggaran', '*', 'WHERE deleted_at IS NULL')
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
        $column_order   = ['id', 'nama_item', 'jumlah', 'harga', 'jenis', 'nama_kategori', 'nama_user_create', 'status_pengajuan', 'keterangan'];
        $column_search  = ['nama_item', 'jumlah', 'harga', 'jenis', 'nama_kategori', 'nama_user_create', 'status_pengajuan', 'keterangan'];
        $order = ['id' => 'DESC'];
        $list = GeneralModel::getDatatable('tb_pengajuan', $column_order, $column_search, $order, $where);
        $data = array();
        $no = $request->post('start');
        foreach ($list as $key) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $key->nama_item;
            $row[] = $key->jumlah;
            $row[] = \Helper::uang($key->harga);
            $row[] = $key->jenis;
            $row[] = $key->nama_kategori;
            $row[] = $key->nama_user_create;
            $row[] = $key->status_pengajuan;
            $row[] = $key->keterangan;
            $row[] = '<a class="btn btn-primary btn-xxs mr-2" href="' . route('report.detail', $key->id) . '"><li class="fa fa-info" aria-hidden="true"></li> Detail</a>';
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
        return view('report.detail', [
            'title' => 'Detail Laporan',
            'pages' => [
                'Laporan',
                'Detail'
            ],
            'data' => GeneralModel::getRow('tb_pengajuan', '*', 'WHERE id="'.$id.'"')
        ]);
    }


}
