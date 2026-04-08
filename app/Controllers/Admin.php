<?php

namespace App\Controllers;

// load models
use App\Models\M_Admin;

class Admin extends BaseController
{
    public function login()
    {
        return view('Backend/Login/login');
    }

    public function autentikasi()
    {
        $modelAdmin = new M_Admin();
        $username   = $this->request->getPost('username');
        $password   = $this->request->getPost('password');

        $cekUsername = $modelAdmin->getDataAdmin([
            'username_admin'  => $username,
            'is_delete_admin' => '0'
        ])->getNumRows();

        if ($cekUsername == 0) {
            session()->setFlashdata('error', 'Username Tidak Ditemukan!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        $dataUser     = $modelAdmin->getDataAdmin([
            'username_admin'  => $username,
            'is_delete_admin' => '0'
        ])->getRowArray();

        if (!password_verify($password, $dataUser['password_admin'])) {
            session()->setFlashdata('error', 'Password Tidak Sesuai!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        session()->set([
            'ses_id'    => $dataUser['id_admin'],
            'ses_user'  => $dataUser['nama_admin'],
            'ses_level' => $dataUser['akses_level'],
            'nama'  => $dataUser['nama_admin'],
            'logged_in' => true
        ]);

        session()->setFlashdata('success', 'Login Berhasil!');
        return redirect()->to(base_url('admin/dashboardAdmin'));

    }

    
    public function dashboard()
    {
        if (session()->get('ses_id') == "" || 
            session()->get('ses_user') == "" || 
            session()->get('ses_level') == "") {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        echo view('Backend/Template/header');
        echo view('Backend/Template/sidebar');
        echo view('Backend/Login/dashboardAdmin');
        echo view('Backend/Template/footer');
    }

    public function logout()
    {
        session()->remove('ses_id');
        session()->remove('ses_user');
        session()->remove('ses_level');
        session()->setFlashdata('info', 'Anda telah keluar dari sistem!');
        ?>
        <script>
            document.location = "<?= base_url('admin/login-admin'); ?>";
        </script>
        <?php
    }

        public function input_data_admin()
    {
        echo view('Backend/Template/header');
        echo view('Backend/Template/sidebar');
        echo view('Backend/MasterAdmin/input-admin');
        echo view('Backend/Template/footer');
    }
}