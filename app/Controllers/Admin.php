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

    // Fungsi untuk menyimpan data admin baru (CREATE)
    public function simpan_data_admin()
    {
        if (session()->get('ses_id') == "" || 
            session()->get('ses_user') == "" || 
            session()->get('ses_level') == "") {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            ?>
            <script>document.location = "<?= base_url('admin/login-admin'); ?>";</script>
            <?php
            return;
        } else {
            $modelAdmin = new M_Admin();

            $nama     = $this->request->getPost('nama');
            $username = $this->request->getPost('username');
            $level    = $this->request->getPost('level');

            // Cek apakah username sudah digunakan
            $cekUname = $modelAdmin->getDataAdmin(['username_admin' => $username])->getNumRows();
            if ($cekUname > 0) {
                session()->setFlashdata('error', 'Username sudah digunakan!!');
                ?>
                <script>history.go(-1);</script>
                <?php
                return;
            }

            
            $hasil = $modelAdmin->autoNumber()->getRowArray();
            if (!$hasil) {
                $id = "ADM001";
            } else {
                $kode   = $hasil['id_admin'];
                $noUrut = (int) substr($kode, -3);
                $noUrut++;
                $id = "ADM" . sprintf("%03s", $noUrut);
            }

            $dataSimpan = [
                'id_admin'       => $id,
                'nama_admin'     => $nama,
                'username_admin' => $username,
                'password_admin' => password_hash('pass_admin', PASSWORD_DEFAULT),
                'akses_level'    => $level,
                'is_delete_admin'=> '0',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s')
            ];

            $modelAdmin->saveDataAdmin($dataSimpan);
            session()->setFlashdata('success', 'Data Admin Berhasil Ditambahkan!!');
            ?>
            <script>document.location = "<?= base_url('admin/master-data-admin'); ?>";</script>
            <?php
        }
    }
        // Fungsi untuk menampilkan daftar admin (READ)
    public function master_data_admin()
    {
        if (session()->get('ses_id') == "" || 
            session()->get('ses_user') == "" || 
            session()->get('ses_level') == "") {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            ?>
            <script>document.location = "<?= base_url('admin/login-admin'); ?>";</script>
            <?php
            return;
        } else {
            $modelAdmin = new M_Admin();

            $uri        = service('uri');
            $pages      = $uri->getSegment(2);
            $dataUser   = $modelAdmin->getDataAdmin([
                'is_delete_admin' => '0',
                'akses_level !='  => '0'
            ])->getResultArray();

            $data['pages']     = $pages;
            $data['data_user'] = $dataUser;

            echo view('Backend/Template/header', $data);
            echo view('Backend/Template/sidebar', $data);
            echo view('Backend/MasterAdmin/master-data-admin', $data);
            echo view('Backend/Template/footer', $data);
        }
    }

    // Fungsi untuk menampilkan form edit admin (UPDATE - bagian 1)
    public function edit_data_admin($idEdit = null)
    {
        if (session()->get('ses_id') == "" || 
            session()->get('ses_user') == "" || 
            session()->get('ses_level') == "") {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            ?>
            <script>document.location = "<?= base_url('admin/login-admin'); ?>";</script>
            <?php
            return;
        } else {
            $modelAdmin = new M_Admin();

            // Simpan id ke session untuk dipakai saat update
            $dataAdmin = $modelAdmin->getDataAdmin(['sha1(id_admin)' => $idEdit])->getRowArray();
            session()->set(['idUpdate' => $dataAdmin['id_admin']]);

            $uri   = service('uri');
            $page  = $uri->getSegment(2);

            $data['page']        = $page;
            $data['web_title']   = "Edit Data Admin";
            $data['data_admin']  = $dataAdmin;

            echo view('Backend/Template/header', $data);
            echo view('Backend/Template/sidebar', $data);
            echo view('Backend/MasterAdmin/edit-admin', $data);
            echo view('Backend/Template/footer', $data);
        }
    }

    // Fungsi untuk menyimpan perubahan data admin (UPDATE - bagian 2)
    public function update_data_admin()
    {
        $modelAdmin = new M_Admin();

        // Ambil ID dari input hidden yang kita buat tadi
        $idUpdate = $this->request->getPost('id_admin'); 
        $nama     = $this->request->getPost('nama');
        $level    = $this->request->getPost('level');

        if ($nama == "" || $level == "" || $idUpdate == "") {
            // ... logika error ...
        } else {
            $dataUpdate = [
                'nama_admin'  => $nama,
                'akses_level' => $level,
                'updated_at'  => date('Y-m-d H:i:s')
            ];
            $whereUpdate = ['id_admin' => $idUpdate];

            $modelAdmin->updateDataAdmin($dataUpdate, $whereUpdate);
            
            // Tidak perlu session()->remove('idUpdate') lagi
            session()->setFlashdata('success', 'Data Admin Berhasil Diperbaharui!');
            return redirect()->to(base_url('admin/master-data-admin'));
        }
    }

    // Fungsi untuk menghapus data admin (DELETE - soft delete)
    public function hapus_data_admin($idHapus = null)
    {
        $modelAdmin = new M_Admin();

        $uri      = service('uri');
        $idHapus  = $uri->getSegment(3);
        $modelAdmin->where('sha1(id_admin)', $idHapus)->delete();
        // $dataUpdate  = [
        //     'is_delete_admin' => '1',
        //     'updated_at'      => date('Y-m-d H:i:s')
        // ];
        // $whereUpdate = ['sha1(id_admin)' => $idHapus];
        session()->setFlashdata('success', 'Data Admin Berhasil Dihapus Permanen!');
                ?>
        <script>document.location = "<?= base_url('admin/master-data-admin'); ?>";</script>
        <?php
    }

    // Data anggota
            // Fungsi untuk menampilkan daftar admin (READ)
    public function master_data_anggota()
    {
        if (session()->get('ses_id') == "" || 
            session()->get('ses_user') == "" || 
            session()->get('ses_level') == "") {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            ?>
            <script>document.location = "<?= base_url('admin/login-admin'); ?>";</script>
            <?php
            return;
        } else {
            $modelAdmin = new M_Admin();

            $uri        = service('uri');
            $pages      = $uri->getSegment(2);
            $dataUser   = $modelAdmin->getDataAdmin([
                'is_delete_admin' => '0',
                'akses_level !='  => '0'
            ])->getResultArray();

            $data['pages']     = $pages;
            $data['data_user'] = $dataUser;

            echo view('Backend/Template/header', $data);
            echo view('Backend/Template/sidebar', $data);
            echo view('Backend/MasterAnggota/master-data-anggota', $data);
            echo view('Backend/Template/footer', $data);
        }
    }
}