<?php

namespace App\Controllers;

// load models
use App\Models\M_Admin;
use App\Models\M_Anggota;
use App\Models\M_Buku;
use App\Models\M_Peminjaman;

class Admin extends BaseController
{
    // ── DATA TRANSAKSI PEMINJAMAN ─────────────────────────────
    public function data_transaksi_peminjaman()
    {
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        $modelPeminjaman = new M_Peminjaman();
        $data['data_peminjaman'] = $modelPeminjaman->getDataPeminjamanJoin()->getResultArray();

        $uri           = service('uri');
        $data['page']  = $uri->getSegment(2);
        $data['title'] = 'Data Transaksi Peminjaman';

        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/Transaksi/data-transaksi-peminjaman', $data);
        echo view('Backend/Template/footer', $data);
    }

    // ── STEP 1: INPUT ID ANGGOTA ──────────────────────────────
    public function peminjaman_step1()
    {
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        $uri           = service('uri');
        $data['page']  = $uri->getSegment(2);
        $data['title'] = 'Transaksi Peminjaman';

        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/Transaksi/peminjaman-step-1', $data);
        echo view('Backend/Template/footer', $data);
    }

    // ── STEP 2: PILIH BUKU ────────────────────────────────────
    public function peminjaman_step2()
    {
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        $modelAnggota    = new M_Anggota();
        $modelBuku       = new M_Buku();
        $modelPeminjaman = new M_Peminjaman();

        $uri   = service('uri');
        $page  = $uri->getSegment(2);

        // Ambil id_anggota dari POST (step 1) atau dari session
        if ($this->request->getPost('id_anggota')) {
            $idAnggota = $this->request->getPost('id_anggota');
            session()->set(['idAgt' => $idAnggota]);
        } else {
            $idAnggota = session()->get('idAgt');
        }

        // Cek apakah anggota masih punya peminjaman berjalan
        $cekPeminjaman = $modelPeminjaman->getDataPeminjaman([
            'id_anggota'       => $idAnggota,
            'status_transaksi' => 'Berjalan'
        ])->getNumRows();

        if ($cekPeminjaman > 0) {
            session()->setFlashdata('error', 'Transaksi Tidak Dapat Dilakukan, Masih Ada Transaksi Peminjaman yang Belum Diselesaikan!!');
            ?>
            <script>history.go(-1);</script>
            <?php
            return;
        } else {
            $dataAnggota = $modelAnggota->getDataAnggota(['id_anggota' => $idAnggota])->getRowArray();
            $dataBuku    = $modelBuku->getDataBuku(['b.is_delete_buku' => '0'])->getResultArray();

            $jumlahTemp  = $modelPeminjaman->getDataTemp(['id_anggota' => $idAnggota])->getNumRows();
            $dataTemp    = $modelPeminjaman->getDataTempJoin(['tbl_temp_peminjaman.id_anggota' => $idAnggota])->getResultArray();

            $data['page']         = $page;
            $data['title']        = 'Transaksi Peminjaman';
            $data['dataAnggota']  = $dataAnggota;
            $data['dataBuku']     = $dataBuku;
            $data['jumlahTemp']   = $jumlahTemp;
            $data['dataTemp']     = $dataTemp;

            echo view('Backend/Template/header', $data);
            echo view('Backend/Template/sidebar', $data);
            echo view('Backend/Transaksi/peminjaman-step-2', $data);
            echo view('Backend/Template/footer', $data);
        }
    }

    // ── SIMPAN KE TABEL TEMP ──────────────────────────────────
    public function simpan_temp_pinjam($idBuku = null)
    {
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        $modelPeminjaman = new M_Peminjaman();
        $modelBuku       = new M_Buku();

        $uri    = service('uri');
        $idBuku = $uri->getSegment(3);

        // Ambil data buku berdasarkan sha1
        $dataBuku = $modelBuku->getDataBuku(['sha1(b.id_buku)' => $idBuku])->getRowArray();

        // Cek apakah buku sudah ada di keranjang anggota ini
        $adaTemp = $modelPeminjaman->getDataTemp([
            'sha1(id_buku)'  => $idBuku,
            'id_anggota'     => session()->get('idAgt')
        ])->getNumRows();

        // Cek apakah anggota masih punya peminjaman berjalan
        $adaBerjalan = $modelPeminjaman->getDataPeminjaman([
            'id_anggota'       => session()->get('idAgt'),
            'status_transaksi' => 'Berjalan'
        ])->getNumRows();

        if ($adaTemp) {
            session()->setFlashdata('error', 'Satu Anggota Hanya Bisa Meminjam 1 Buku dengan Judul yang Sama!');
            ?>
            <script>history.go(-1);</script>
            <?php
        } elseif ($adaBerjalan) {
            session()->setFlashdata('error', 'Masih ada transaksi peminjaman yang belum diselesaikan, silakan selesaikan peminjaman sebelumnya terlebih dahulu!');
            ?>
            <script>history.go(-1);</script>
            <?php
        } else {
            // Simpan ke tabel temp
            $dataSimpanTemp = [
                'id_anggota'  => session()->get('idAgt'),
                'id_buku'     => $dataBuku['id_buku'],
                'jumlah_temp' => '1'
            ];
            $modelPeminjaman->saveDataTemp($dataSimpanTemp);

            // Kurangi stok buku
            $stok       = $dataBuku['jumlah_eksemplar'] - 1;
            $dataUpdate = ['jumlah_eksemplar' => $stok];
            $modelBuku->updateDataBuku($dataUpdate, ['sha1(id_buku)' => $idBuku]);
            ?>
            <script>
                document.location = "<?= base_url('admin/peminjaman-step-2'); ?>";
            </script>
            <?php
        }
    }

    // ── HAPUS DARI TABEL TEMP ─────────────────────────────────
    public function hapus_peminjaman($idBuku = null)
    {
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        $modelPeminjaman = new M_Peminjaman();
        $modelBuku       = new M_Buku();

        $uri    = service('uri');
        $idBuku = $uri->getSegment(3);

        $dataBuku = $modelBuku->getDataBuku(['sha1(b.id_buku)' => $idBuku])->getRowArray();

        // Hapus dari temp
        $modelPeminjaman->hapusDataTemp([
            'sha1(id_buku)' => $idBuku,
            'id_anggota'    => session()->get('idAgt')
        ]);

        // Kembalikan stok buku
        $stok       = $dataBuku['jumlah_eksemplar'] + 1;
        $dataUpdate = ['jumlah_eksemplar' => $stok];
        $modelBuku->updateDataBuku($dataUpdate, ['sha1(id_buku)' => $idBuku]);
        ?>
        <script>
            document.location = "<?= base_url('admin/peminjaman-step-2'); ?>";
        </script>
        <?php
    }

    // ── SIMPAN TRANSAKSI PEMINJAMAN ───────────────────────────
    public function simpan_transaksi_peminjaman()
    {
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        $modelPeminjaman = new M_Peminjaman();

        // Generate no_peminjaman: format ymdHis
        $idPeminjaman  = date('ymdHis');
        $timeSekarang  = time();
        $kembali       = date('Y-m-d', strtotime('+7 days', $timeSekarang));
        $jumlahPinjam  = $modelPeminjaman->getDataTemp(['id_anggota' => session()->get('idAgt')])->getNumRows();

        // Simpan header peminjaman
        $dataSimpan = [
            'no_peminjaman'     => $idPeminjaman,
            'id_anggota'        => session()->get('idAgt'),
            'tgl_pinjam'        => date('Y-m-d'),
            'total_pinjam'      => $jumlahPinjam,
            'id_admin'          => session()->get('ses_id'),
            'status_transaksi'  => 'Berjalan',
            'status_ambil_buku' => 'Belum Diambil',
            'qr_code'           => '',
        ];
        $modelPeminjaman->saveDataPeminjaman($dataSimpan);

        // Simpan detail dari tabel temp
        $dataTemp = $modelPeminjaman->getDataTemp(['id_anggota' => session()->get('idAgt')])->getResultArray();
        foreach ($dataTemp as $sementara) {
            $simpanDetail = [
                'no_peminjaman' => $idPeminjaman,
                'id_buku'       => $sementara['id_buku'],
                'status_pinjam' => 'Sedang Dipinjam',
                'perpanjangan'  => '2',
                'tgl_kembali'   => $kembali,
            ];
            $modelPeminjaman->saveDataDetail($simpanDetail);
        }

        // Kosongkan tabel temp & hapus session anggota
        $modelPeminjaman->hapusDataTemp(['id_anggota' => session()->get('idAgt')]);
        session()->remove('idAgt');
        session()->setFlashdata('success', 'Data Peminjaman Buku Berhasil Disimpan!');
        ?>
        <script>
            document.location = "<?= base_url('admin/data-transaksi-peminjaman'); ?>";
        </script>
        <?php
    }

    // ── DETAIL PEMINJAMAN ─────────────────────────────────────
    public function detail_peminjaman($noPeminjaman = null)
    {
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        $modelPeminjaman = new M_Peminjaman();

        $data['header'] = $modelPeminjaman->getDataPeminjamanJoin([
            'tbl_peminjaman.no_peminjaman' => $noPeminjaman
        ])->getRowArray();

        $data['detail'] = $modelPeminjaman->getDataDetailJoin([
            'tbl_detail_peminjaman.no_peminjaman' => $noPeminjaman
        ])->getResultArray();

        $data['title'] = 'Detail Peminjaman';

        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/Transaksi/detail-peminjaman', $data);
        echo view('Backend/Template/footer', $data);
    }

    // ── DASHBOARD UTAMA ───────────────────────────────────────
    public function dashboard()
    {
        if (session()->get('ses_id') == "" || 
            session()->get('ses_user') == "" || 
            session()->get('ses_level') == "") {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        // Ambil segmen URI untuk status aktif sidebar
        $uri           = service('uri');
        $data['page']  = $uri->getSegment(2);
        $data['title'] = 'Dashboard';

        // Hitung total data dari masing-masing tabel database
        $db = \Config\Database::connect();
        $data['total_anggota']    = $db->table('tbl_anggota')->countAllResults();
        $data['total_rak']        = $db->table('tbl_rak')->countAllResults();
        $data['total_buku']       = $db->table('tbl_buku')->countAllResults();
        $data['total_peminjaman'] = $db->table('tbl_peminjaman')->countAllResults();

        // Render view dengan layout terstruktur backend Anda
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/Login/dashboardAdmin', $data); // Mengarah ke template dashboard milik Anda
        echo view('Backend/Template/footer', $data);
    }
    
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
            'nama'      => $dataUser['nama_admin'],
            'logged_in' => true
        ]);

        session()->setFlashdata('success', 'Login Berhasil!');
        return redirect()->to(base_url('admin/dashboard'));
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
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        $modelAdmin = new M_Admin();
        $nama       = $this->request->getPost('nama');
        $username   = $this->request->getPost('username');
        $level      = $this->request->getPost('level');
        $password   = $this->request->getPost('password');

        // Validasi field kosong
        if (empty($nama) || empty($username) || empty($level) || empty($password)) {
            session()->setFlashdata('error', 'Semua field wajib diisi!');
            return redirect()->to(base_url('admin/input-data-admin'));
        }

        // Cek username duplikat
        $cek = $modelAdmin->getDataAdmin([
            'username_admin'  => $username,
            'is_delete_admin' => '0'
        ])->getNumRows();
        if ($cek > 0) {
            session()->setFlashdata('error', 'Username sudah digunakan!');
            return redirect()->to(base_url('admin/input-data-admin'));
        }

        // Generate ID
        $hasil = $modelAdmin->autoNumber()->getRowArray();
        $id    = !$hasil ? 'ADM001' : 'ADM' . sprintf('%03d', (int)substr($hasil['id_admin'], -3) + 1);

        $modelAdmin->saveDataAdmin([
            'id_admin'        => $id,
            'nama_admin'      => $nama,
            'username_admin'  => $username,
            'password_admin'  => password_hash($password, PASSWORD_DEFAULT),
            'akses_level'     => $level,
            'is_delete_admin' => '0',
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('success', 'Data Admin Berhasil Ditambahkan!');
        return redirect()->to(base_url('admin/master-data-admin'));
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
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        $modelAdmin  = new M_Admin();
        $idUpdate    = $this->request->getPost('id_admin');
        $nama        = $this->request->getPost('nama');
        $level       = $this->request->getPost('level');
        $passwordBaru = $this->request->getPost('password');

        $dataUpdate = [
            'nama_admin'  => $nama,
            'akses_level' => $level,
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        // Hanya update password jika diisi
        if (!empty($passwordBaru)) {
            $dataUpdate['password_admin'] = password_hash($passwordBaru, PASSWORD_DEFAULT);
        }

        $modelAdmin->updateDataAdmin($dataUpdate, ['id_admin' => $idUpdate]);
        session()->setFlashdata('success', 'Data Admin Berhasil Diperbarui!');
        return redirect()->to(base_url('admin/master-data-admin'));
    }

    // Fungsi untuk menghapus data admin (DELETE - soft delete)
    public function hapus_data_admin($idHapus = null)
    {
        $modelAdmin = new M_Admin();

        $uri      = service('uri');
        $idHapus  = $uri->getSegment(3);
        $modelAdmin->where('sha1(id_admin)', $idHapus)->delete();
        
        session()->setFlashdata('success', 'Data Admin Berhasil Dihapus Permanen!');
        ?>
        <script>document.location = "<?= base_url('admin/master-data-admin'); ?>";</script>
        <?php
    }

    // Data anggota
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

        public function update_status_ambil($noPeminjaman = null)
    {
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        $modelPeminjaman = new M_Peminjaman();
        $modelPeminjaman->updateDataPeminjaman(
            ['status_ambil_buku' => 'Sudah Diambil'],
            ['no_peminjaman'     => $noPeminjaman]
        );

        session()->setFlashdata('success', 'Status Ambil Buku Berhasil Diperbarui!');
        return redirect()->to(base_url('admin/detail-peminjaman/' . $noPeminjaman));
    }
}