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
            'pages' => ['Laporan', 'List Laporan']
        ]);
    }

    
    public function list(Request $request)
    {
        $where[] = ['deleted_at', '',  '', 'NULL'];
        
        if($request->post('mulai') && $request->post('selesai')){
            $where[] = ['transaction_date', '>=', $request->post('mulai'), 'DATE'];
            $where[] = ['transaction_date', '<=', $request->post('selesai'), 'DATE'];
        }
        $column_order   = ['id', 'transaction_date', 'amount', 'description', 'notes'];
        $column_search  = ['transaction_date', 'amount', 'description', 'notes'];
        $order = ['id' => 'DESC'];
        $list = GeneralModel::getDatatable('tb_report', $column_order, $column_search, $order, $where);
        // print_r(GeneralModel::lastQuery());die;
        $data = array();
        $no = $request->post('start');
        foreach ($list as $key) {
            $no++;
            $row = array();

            $row[] = $no;
            $row[] = \Helper::tanggalwoah($key->transaction_date);
            $row[] = $key->transaction_type == 'expenditure' ? 'Pengeluaran' : 'Pemasukan';
            $row[] = \Helper::uang($key->income);
            $row[] = \Helper::uang($key->expenditure);
            $row[] = \Helper::uang($key->saldo);
            // $row[] = '
            //     <a class="btn btn-primary btn-xxs mr-2" href="' . route('report.detail', $key->id) . '"><li class="fa fa-info" aria-hidden="true"></li> Detail</a>
            //     &nbsp;
            //     <a class="btn btn-success btn-xxs mr-2" href="' . route('report.edit', $key->id) . '"><li class="fa fa-edit" aria-hidden="true"></li> Edit</a>
            //     &nbsp;
            //     <a class="btn btn-danger btn-xxs" onclick="hapus(' . $key->id . ')"><li class="fa fa-trash" aria-hidden="true"></li> Hapus</a>';
            $row[] = '
                <a class="btn btn-primary btn-xxs mr-2" href="' . route('report.detail', $key->id) . '"><li class="fa fa-info" aria-hidden="true"></li> Detail</a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => GeneralModel::countAll('tb_report', $where),
            "recordsFiltered" => GeneralModel::countFiltered('tb_report', $column_order, $column_search, $order, $where),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function detail(Request $request, $id){
        $report = GeneralModel::getRow('tb_report', '*', 'WHERE id="'.$id.'"');
        if($report->transaction_type == 'income'){
            $transaction = GeneralModel::getRow('tb_income', '*', 'WHERE id="'.$report->id_transaction.'"');
        } else {
            $transaction = GeneralModel::getRow('tb_expenditure', '*', 'WHERE id="'.$report->id_transaction.'"');
        }
        return view('report.detail', [
            'title' => 'Detail Laporan',
            'pages' => [
                'Laporan',
                'Detail'
            ],
            'report' => $report,
            'data' => $transaction
        ]);
    }


}
