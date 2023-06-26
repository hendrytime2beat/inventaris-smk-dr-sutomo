<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class ExpenditureController extends Controller
{

    public function index()
    {
        return view('expenditure.index', [
            'title' => 'Pengeluaran',
            'pages' => ['Pengeluaran', 'List Pengeluaran']
        ]);
    }

    
    public function list(Request $request)
    {
        $where[] = ['deleted_at', '',  '', 'NULL'];
        $column_order   = ['id', 'transaction_date', 'amount', 'description', 'notes'];
        $column_search  = ['transaction_date', 'amount', 'description', 'notes'];
        $order = ['id' => 'DESC'];
        $list = GeneralModel::getDatatable('tb_expenditure', $column_order, $column_search, $order, $where);
        $data = array();
        $no = $request->post('start');
        foreach ($list as $key) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = \Helper::tanggalwoah($key->transaction_date.' '.$key->transaction_time);
            $row[] = \Helper::uang($key->amount);
            $row[] = $key->description;
            $row[] = $key->notes;
            // $row[] = '
            //     <a class="btn btn-primary btn-xxs mr-2" href="' . route('expenditure.detail', $key->id) . '"><li class="fa fa-info" aria-hidden="true"></li> Detail</a>
            //     &nbsp;
            //     <a class="btn btn-success btn-xxs mr-2" href="' . route('expenditure.edit', $key->id) . '"><li class="fa fa-edit" aria-hidden="true"></li> Edit</a>
            //     &nbsp;
            //     <a class="btn btn-danger btn-xxs" onclick="hapus(' . $key->id . ')"><li class="fa fa-trash" aria-hidden="true"></li> Hapus</a>';
            $row[] = '
                <a class="btn btn-primary btn-xxs mr-2" href="' . route('expenditure.detail', $key->id) . '"><li class="fa fa-info" aria-hidden="true"></li> Detail</a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => GeneralModel::countAll('tb_expenditure', $where),
            "recordsFiltered" => GeneralModel::countFiltered('tb_expenditure', $column_order, $column_search, $order, $where),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function form(Request $request, $id = '')
    {
        return view('expenditure.form', [
            'title' => 'Pengeluaran',
            'pages' => ['Pemasukkan', 'Tambah Pemasukkan'],
            'data' => $id ? GeneralModel::getRow('tb_expenditure', '*', 'WHERE id="'.$id.'"') : ''
        ]);
    }
    

    public function act(Request $request, $id='')
    {
        $validator = Validator::make($request->all(), [
            'transaction_date' => 'required',
            'transaction_time' => 'required',
            'amount' => 'required',
            'description' => 'required',
            'notes' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = [
            'amount' => $request->post('amount'),
            'transaction_date' => $request->post('transaction_date'),
            'transaction_time' => $request->post('transaction_time'),
            'description' => $request->post('description'),
            'notes' => $request->post('notes'),
            'id_user' => $request->session()->get('id_user')
        ];
        if(empty($id)){
            $data['created_at'] = date('Y-m-d H:i:s');
            GeneralModel::setInsert('tb_expenditure', $data);
            $request->session()->flash('message', 'Sukses!, anda berhasil menambahkan Pengeluaran');
            $id = GeneralModel::getid();
        } else {
            $data['updated_at'] = date('Y-m-d H:i:s');
            GeneralModel::setUpdate('tb_expenditure', $data, ['id' => $id]);
            $request->session()->flash('message', 'Sukses!, anda berhasil memperbarui Pengeluaran');
        }
        \Helper::insert_report($request->post('amount'), 'expenditure', $id);
        return redirect()->route('expenditure');
    }
    
    public function detail(Request $request, $id){
        return view('expenditure.detail', [
            'title' => 'Detail Pengeluaran',
            'pages' => [
                'Pengeluaran',
                'Detail'
            ],
            'data' => GeneralModel::getRow('tb_expenditure', '*', 'WHERE id="'.$id.'"')
        ]);
    }

    public function delete(Request $request, $id){
        GeneralModel::setUpdate('tb_expenditure', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
        $request->session()->flash('message', 'Sukses!, anda berhasil menghapus Pengeluaran');
        return redirect()->route('expenditure');
    }

}
