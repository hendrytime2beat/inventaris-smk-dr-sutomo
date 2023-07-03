<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralModel;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'title' => 'Dashboard',
            'pages' => ['Dashboard'],
            'count' => [
                'perencanaan' => GeneralModel::getRow('tb_perencanaan', 'COUNT(1) AS jml', 'WHERE status_perencanaan="finish"')->jml,
                'pengajuan' => GeneralModel::getRow('tb_pengajuan', 'COUNT(1) AS jml', 'WHERE status_pengajuan="finish"')->jml,
                'realisasi' => GeneralModel::getRow('tb_realisasi', 'COUNT(1) AS jml', 'WHERE status_realisasi="finish"')->jml,
                'penerima' => GeneralModel::getRow('tb_penerima', 'COUNT(1) AS jml', 'WHERE status_penerima="finish"')->jml,
            ]
        ]);
    }
}
