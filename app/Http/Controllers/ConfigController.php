<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller
{
    public function anggaran()
    {
        return view('config.anggaran.index', [
            'title' => 'Anggaran',
            'pages' => ['Config', 'Anggaran', 'List']
        ]);
    }

    public function anggaran_list(Request $request)
    {
        $where[] = ['deleted_at', '',  '', 'NULL'];
        $column_order   = ['id', 'tahun', 'anggaran_awal', 'anggaran_sisa'];
        $column_search  = ['tahun', 'anggaran_awal', 'anggaran_sisa'];
        $order = ['id' => 'DESC'];
        $list = GeneralModel::getDatatable('conf_anggaran', $column_order, $column_search, $order, $where);
        $data = array();
        $no = $request->post('start');
        foreach ($list as $key) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $key->tahun;
            $row[] = \Helper::uang($key->anggaran_awal);
            $row[] = \Helper::uang($key->anggaran_sisa);
            $row[] = '
            <a class="btn btn-warning btn-xxs mr-2" onclick="main.edit(this)" data-data=\''.json_encode($key).'\'><li class="fa fa-edit" aria-hidden="true"></li> Edit</a>
            &nbsp;
            <a class="btn btn-danger btn-xxs" onclick="hapus(' . $key->id . ')"><li class="fa fa-trash" aria-hidden="true"></li> Hapus</a>
            ';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => GeneralModel::countAll('conf_anggaran', $where),
            "recordsFiltered" => GeneralModel::countFiltered('conf_anggaran', $column_order, $column_search, $order, $where),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function anggaran_act(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'anggaran' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = [
            'tahun' => $request->post('tahun'),
            'anggaran_awal' => $request->post('anggaran'),
        ];
        if(empty($request->post('id'))){
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['anggaran_sisa'] = $request->post('anggaran');
            GeneralModel::setInsert('conf_anggaran', $data);
            $request->session()->flash('message', 'Sukses!, anda berhasil menambahkan Anggaran');
            $id = GeneralModel::getid();
        } else {
            $data['updated_at'] = date('Y-m-d H:i:s');
            GeneralModel::setUpdate('conf_anggaran', $data, ['id' => $request->post('id')]);
            $request->session()->flash('message', 'Sukses!, anda berhasil memperbarui Anggaran');
        }
        return redirect()->route('config.anggaran');
    }

    public function anggaran_delete(Request $request, $id)
    {
        GeneralModel::setUpdate('conf_anggaran', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
        $request->session()->flash('message', 'Sukses!, anda berhasil menghapus Anggaran');
    }

    public function unit_kerja()
    {
        return view('config.unit_kerja.index', [
            'title' => 'Unit Kerja',
            'pages' => ['Config', 'Unit Kerja', 'List']
        ]);
    }

    public function unit_kerja_list(Request $request)
    {
        $where[] = ['deleted_at', '',  '', 'NULL'];
        $column_order   = ['id', 'nama_unit_kerja'];
        $column_search  = ['nama_unit_kerja'];
        $order = ['id' => 'DESC'];
        $list = GeneralModel::getDatatable('conf_unit_kerja', $column_order, $column_search, $order, $where);
        $data = array();
        $no = $request->post('start');
        foreach ($list as $key) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $key->nama_unit_kerja;
            $row[] = '
            <a class="btn btn-warning btn-xxs mr-2" onclick="main.edit(this)" data-data=\''.json_encode($key).'\'><li class="fa fa-edit" aria-hidden="true"></li> Edit</a>
            &nbsp;
            <a class="btn btn-danger btn-xxs" onclick="hapus(' . $key->id . ')"><li class="fa fa-trash" aria-hidden="true"></li> Hapus</a>
            ';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => GeneralModel::countAll('conf_unit_kerja', $where),
            "recordsFiltered" => GeneralModel::countFiltered('conf_unit_kerja', $column_order, $column_search, $order, $where),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function unit_kerja_act(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_unit_kerja' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = [
            'nama_unit_kerja' => $request->post('nama_unit_kerja'),
        ];
        if(empty($request->post('id'))){
            $data['created_at'] = date('Y-m-d H:i:s');
            GeneralModel::setInsert('conf_unit_kerja', $data);
            $request->session()->flash('message', 'Sukses!, anda berhasil menambahkan Unit Kerja');
            $id = GeneralModel::getid();
        } else {
            $data['updated_at'] = date('Y-m-d H:i:s');
            GeneralModel::setUpdate('conf_unit_kerja', $data, ['id' => $request->post('id')]);
            $request->session()->flash('message', 'Sukses!, anda berhasil memperbarui Unit Kerja');
        }
        return redirect()->route('config.unit_kerja');
    }

    public function unit_kerja_delete(Request $request, $id)
    {
        GeneralModel::setUpdate('conf_unit_kerja', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
        $request->session()->flash('message', 'Sukses!, anda berhasil menghapus Unit Kerja');
    }


}