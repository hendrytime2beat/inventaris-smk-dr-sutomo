<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralModel;
use Illuminate\Support\Facades\Validator;

class AccessController extends Controller
{

    public function login()
    {
        return view('access.login', [
            'title' => 'Login'
        ]);
    }

    public function login_act(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->route('access.login')->withErrors($validator)->withInput();
        }
        $valUser = GeneralModel::getRow('m_user', '*', 'WHERE username="' . $request->post('username') . '" AND password="' . md5($request->post('password')) . '" AND deleted_at IS NULL');
        if (empty($valUser)) {
            return redirect()->route('access.login')->withErrors([
                'username' => 'Username / password not found'
            ]);
        }
        GeneralModel::setUpdate('m_user', ['last_login' => date('Y-m-d H:i:s')], ['id' => $valUser->id]);
        $request->session()->put([
            'id_user' => $valUser->id,
            'nama' => $valUser->nama,
            'username' => $valUser->username,
            'foto_profil' => $valUser->foto_profil
        ]);
        return redirect()->route('dashboard');
    }

    public function profile(Request $request)
    {
        return view('access.profile', [
            'title' => 'Profil',
            'pages' => ['Dashboard', 'Profil'],
            'data' => GeneralModel::getRow('m_user', '*', 'WHERE id=' . $request->session()->get('id_user'))
        ]);
    }

    public function profile_act(Request $request)
    {
        $data = [
            'username' => $request->post('username'),
        ];
        if (!empty($request->post('password'))) {
            $data['password'] = md5($request->post('password'));
        }
        if ($request->hasFile('profile_picture')) {
            $name_file = $request->file('profile_picture')->getClientOriginalName();
            $path = public_path('\assets\img\profile_picture');
            $request->file('profile_picture')->move($path, $name_file);
            $data['profile_picture'] = $name_file;
        }
        GeneralModel::setUpdate('m_user', $data, [
            'id' => $request->session()->get('id_user')
        ]);
        $request->session()->put($data);
        $request->session()->flash('message', 'Sukses!, anda berhasil memperbarui profil');
        return redirect()->route('access.profile');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('access.login');
    }
}
