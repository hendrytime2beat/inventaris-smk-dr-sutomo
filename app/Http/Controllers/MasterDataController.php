<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class MasterDataController extends Controller
{
    public function user_grup()
    {
        return view('master_data.user_grup.index', [
            'title' => 'User Grup',
            'pages' => ['Master Data', 'User Grup', 'List']
        ]);
    }

    public function user_grup_list(Request $request)
    {
        $where[] = ['deleted_at', '',  '', 'NULL'];
        $column_order   = ['id', 'nama_grup'];
        $column_search  = ['nama_grup'];
        $order = ['id' => 'DESC'];
        $list = GeneralModel::getDatatable('m_user_grup', $column_order, $column_search, $order, $where);
        $data = array();
        $no = $request->post('start');
        foreach ($list as $key) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $key->nama_grup;
            $row[] = '
            <a class="btn btn-warning btn-xxs mr-2" onclick="main.edit(this)" data-data=\''.json_encode($key).'\'><li class="fa fa-edit" aria-hidden="true"></li> Edit</a>
            &nbsp;
            <a class="btn btn-danger btn-xxs" onclick="hapus(' . $key->id . ')"><li class="fa fa-trash" aria-hidden="true"></li> Hapus</a>
            ';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => GeneralModel::countAll('m_user_grup', $where),
            "recordsFiltered" => GeneralModel::countFiltered('m_user_grup', $column_order, $column_search, $order, $where),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function user_grup_act(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_grup' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = [
            'nama_grup' => $request->post('nama_grup'),
        ];
        if(empty($request->post('id'))){
            $data['created_at'] = date('Y-m-d H:i:s');
            GeneralModel::setInsert('m_user_grup', $data);
            $request->session()->flash('message', 'Sukses!, anda berhasil menambahkan User Grup');
            $id = GeneralModel::getid();
        } else {
            $data['updated_at'] = date('Y-m-d H:i:s');
            GeneralModel::setUpdate('m_user_grup', $data, ['id' => $request->post('id')]);
            $request->session()->flash('message', 'Sukses!, anda berhasil memperbarui User Grup');
        }
        return redirect()->route('master_data.user_grup');
    }

    public function user_grup_delete(Request $request, $id)
    {
        GeneralModel::setUpdate('m_user_grup', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
        $request->session()->flash('message', 'Sukses!, anda berhasil menghapus User Grup');
    }

    public function user()
    {
        return view('master_data.user.index', [
            'title' => 'User',
            'pages' => ['Master Data', 'User', 'List']
        ]);
    }

    public function user_list(Request $request)
    {
        $where[] = ['deleted_at', '',  '', 'NULL'];
        $column_order   = ['id', 'nama', 'username', 'email', 'no_hp'];
        $column_search  = ['nama', 'username', 'email', 'no_hp'];
        $order = ['id' => 'DESC'];
        $list = GeneralModel::getDatatable('m_user', $column_order, $column_search, $order, $where);
        $data = array();
        $no = $request->post('start');
        foreach ($list as $key) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $key->nama;
            $row[] = $key->username;
            $row[] = $key->email;
            $row[] = $key->no_hp;
            $row[] = '
            <a class="btn btn-warning btn-xxs mr-2" href="'.route('master_data.user.edit', $key->id).'"><li class="fa fa-edit" aria-hidden="true"></li> Edit</a>
            &nbsp;
            <a class="btn btn-danger btn-xxs" onclick="hapus(' . $key->id . ')"><li class="fa fa-trash" aria-hidden="true"></li> Hapus</a>
            ';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => GeneralModel::countAll('m_user', $where),
            "recordsFiltered" => GeneralModel::countFiltered('m_user', $column_order, $column_search, $order, $where),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function user_form(Request $request, $id='')
    {
        return view('master_data.user.form', [
            'title' => 'User Form',
            'pages' => ['Master Data', 'User', 'Form'],
            'user_grup' => GeneralModel::getRes('m_user_grup', '*', 'WHERE deleted_at IS NULL ORDER BY nama_grup DESC'),
            'data' => $id ? GeneralModel::getRow('m_user', '*', 'WHERE id="'.$id.'"') : ''
        ]);
    }

    public function user_act(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user_grup' => 'required',
            'username' => 'required',
            'nama' => 'required',
            'email' => 'required',
            'no_hp' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = [
            'id_user_grup' => $request->post('id_user_grup'),
            'username' => $request->post('username'),
            'nama' => $request->post('nama'),
            'email' => $request->post('email'),
            'no_hp' => $request->post('no_hp')
        ];
        if($request->post('password')){
            $data['password'] = md5($request->post('password'));
        }
        if ($request->hasFile('foto_profil')) {
            $name_file = $request->file('foto_profil')->getClientOriginalName();
            $path = public_path('\assets\img\foto_profil');
            $request->file('foto_profil')->move($path, $name_file);
            $data['foto_profil'] = $name_file;
        }
        if($request->post('id')){
            $data['updated_at'] = date('Y-m-d H:i:s');
            GeneralModel::setUpdate('m_user', $data, [
                'id' => $request->post('id')
            ]);
            $request->session()->flash('message', 'Sukses!, anda berhasil memperbarui User');
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            GeneralModel::setInsert('m_user', $data);
            $request->session()->flash('message', 'Sukses!, anda berhasil menambahkan User');
        }
        return redirect()->route('master_data.user');
    }
    
    public function user_delete(Request $request, $id)
    {
        GeneralModel::setUpdate('m_user', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
        $request->session()->flash('message', 'Sukses!, anda berhasil menghapus User');
    }

    public function kategori()
    {
        return view('master_data.kategori.index', [
            'title' => 'Kategori',
            'pages' => ['Master Data', 'Kategori', 'List']
        ]);
    }

    public function kategori_list(Request $request)
    {
        $where[] = ['deleted_at', '',  '', 'NULL'];
        $column_order   = ['id', 'nama_kategori'];
        $column_search  = ['nama_kategori'];
        $order = ['id' => 'DESC'];
        $list = GeneralModel::getDatatable('m_kategori', $column_order, $column_search, $order, $where);
        $data = array();
        $no = $request->post('start');
        foreach ($list as $key) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $key->nama_kategori;
            $row[] = '
            <a class="btn btn-warning btn-xxs mr-2" onclick="main.edit(this)" data-data=\''.json_encode($key).'\'><li class="fa fa-edit" aria-hidden="true"></li> Edit</a>
            &nbsp;
            <a class="btn btn-danger btn-xxs" onclick="hapus(' . $key->id . ')"><li class="fa fa-trash" aria-hidden="true"></li> Hapus</a>
            ';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => GeneralModel::countAll('m_kategori', $where),
            "recordsFiltered" => GeneralModel::countFiltered('m_kategori', $column_order, $column_search, $order, $where),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function kategori_act(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = [
            'nama_kategori' => $request->post('nama_kategori'),
        ];
        if(empty($request->post('id'))){
            $data['created_at'] = date('Y-m-d H:i:s');
            GeneralModel::setInsert('m_kategori', $data);
            $request->session()->flash('message', 'Sukses!, anda berhasil menambahkan Kategori');
            $id = GeneralModel::getid();
        } else {
            $data['updated_at'] = date('Y-m-d H:i:s');
            GeneralModel::setUpdate('m_kategori', $data, ['id' => $request->post('id')]);
            $request->session()->flash('message', 'Sukses!, anda berhasil memperbarui Kategori');
        }
        return redirect()->route('master_data.kategori');
    }

    public function kategori_delete(Request $request, $id)
    {
        GeneralModel::setUpdate('m_kategori', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
        $request->session()->flash('message', 'Sukses!, anda berhasil menghapus Kategori');
    }


}
