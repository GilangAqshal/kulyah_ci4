<?php
namespace App\Controllers;

use App\Models\M_Peminjaman;
use App\Models\M_Anggota;
use App\Models\M_Buku;

class Peminjaman extends BaseController
{
    private function cekSesi()
    {
        if (!session()->get('ses_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Session expired']);
        }
        return null;
    }

    // ── HALAMAN UTAMA ────────────────────────────────────────
    public function index()
    {
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        $data['title'] = 'Transaksi Peminjaman';
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/Transaksi/form-peminjaman', $data);
        echo view('Backend/Template/footer', $data);
    }

    // ── HALAMAN DATA TRANSAKSI ────────────────────────────────
    public function data_transaksi()
    {
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        $model = new M_Peminjaman();
        $data['data_peminjaman'] = $model->getDataPeminjaman()->getResultArray();
        $data['title'] = 'Data Transaksi Peminjaman';

        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/Transaksi/data-transaksi', $data);
        echo view('Backend/Template/footer', $data);
    }

    // ── DETAIL PEMINJAMAN ─────────────────────────────────────
    public function detail_peminjaman($noPeminjaman)
    {
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }

        $model  = new M_Peminjaman();
        $header = $model->getDataPeminjaman(['p.no_peminjaman' => $noPeminjaman])->getRowArray();
        $detail = $model->getDataDetail(['d.no_peminjaman' => $noPeminjaman])->getResultArray();

        $data['header'] = $header;
        $data['detail'] = $detail;
        $data['title']  = 'Detail Peminjaman';

        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/Transaksi/detail-peminjaman', $data);
        echo view('Backend/Template/footer', $data);
    }

    // ── AJAX: CARI ANGGOTA (untuk Select2) ───────────────────
    public function ajax_cari_anggota()
    {
        $keyword = $this->request->getPost('q') ?? '';
        $model   = new M_Anggota();

        $builder = $this->db->table('tbl_anggota');
        $builder->select('id_anggota, nama_anggota');
        $builder->where('is_delete_anggota', '0');
        $builder->groupStart()
                ->like('id_anggota', $keyword)
                ->orLike('nama_anggota', $keyword)
                ->groupEnd();
        $builder->limit(10);
        $result = $builder->get()->getResultArray();

        $output = [];
        foreach ($result as $row) {
            $output[] = [
                'id'   => $row['id_anggota'],
                'text' => $row['id_anggota'] . ' - ' . $row['nama_anggota'],
            ];
        }

        return $this->response->setJSON(['results' => $output]);
    }

    // ── AJAX: CARI BUKU (untuk Select2) ──────────────────────
    public function ajax_cari_buku()
    {
        $keyword = $this->request->getPost('q') ?? '';

        $builder = $this->db->table('tbl_buku b');
        $builder->select('b.id_buku, b.judul_buku, b.pengarang, b.jumlah_eksemplar, k.nama_kategori');
        $builder->join('tbl_kategori k', 'k.id_kategori = b.id_kategori', 'left');
        $builder->where('b.is_delete_buku', '0');
        $builder->where('b.jumlah_eksemplar >', 0);
        $builder->groupStart()
                ->like('b.judul_buku', $keyword)
                ->orLike('b.pengarang', $keyword)
                ->orLike('b.id_buku', $keyword)
                ->groupEnd();
        $builder->limit(10);
        $result = $builder->get()->getResultArray();

        $output = [];
        foreach ($result as $row) {
            $output[] = [
                'id'   => $row['id_buku'],
                'text' => $row['judul_buku'] . ' - ' . $row['pengarang'] . ' (Stok: ' . $row['jumlah_eksemplar'] . ')',
                'stok' => $row['jumlah_eksemplar'],
            ];
        }

        return $this->response->setJSON(['results' => $output]);
    }

    // ── AJAX: TAMBAH KE KERANJANG ─────────────────────────────
    public function ajax_tambah_keranjang()
    {
        $idAnggota = $this->request->getPost('id_anggota');
        $idBuku    = $this->request->getPost('id_buku');

        if (empty($idAnggota) || empty($idBuku)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak lengkap!'
            ]);
        }

        $mPeminjaman = new M_Peminjaman();
        $mBuku       = new M_Anggota();

        // Cek stok buku
        $buku = $this->db->table('tbl_buku')
                         ->where('id_buku', $idBuku)
                         ->get()->getRowArray();

        if (!$buku || $buku['jumlah_eksemplar'] <= 0) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Stok buku habis!'
            ]);
        }

        // Cek buku sudah ada di keranjang anggota ini
        $cekTemp = $this->db->table('tbl_temp_peminjaman')
                            ->where(['id_anggota' => $idAnggota, 'id_buku' => $idBuku])
                            ->get()->getNumRows();

        if ($cekTemp > 0) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Buku ini sudah ada di keranjang!'
            ]);
        }

        // Cek anggota masih punya peminjaman berjalan
        $cekBerjalan = $this->db->table('tbl_peminjaman')
                                ->where(['id_anggota' => $idAnggota, 'status_transaksi' => 'Berjalan'])
                                ->get()->getNumRows();

        if ($cekBerjalan > 0) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Anggota masih memiliki peminjaman yang belum selesai!'
            ]);
        }

        // Simpan ke temp
        $mPeminjaman->saveDataTemp([
            'id_anggota'  => $idAnggota,
            'id_buku'     => $idBuku,
            'jumlah_temp' => 1,
        ]);

        // Ambil isi keranjang terbaru
        $keranjang = $mPeminjaman->getDataTemp(['t.id_anggota' => $idAnggota])->getResultArray();

        return $this->response->setJSON([
            'status'    => 'success',
            'message'   => 'Buku berhasil ditambahkan ke keranjang!',
            'keranjang' => $keranjang,
            'total'     => count($keranjang),
        ]);
    }

    // ── AJAX: HAPUS DARI KERANJANG ────────────────────────────
    public function ajax_hapus_keranjang()
    {
        $idAnggota = $this->request->getPost('id_anggota');
        $idBuku    = $this->request->getPost('id_buku');

        if (empty($idAnggota) || empty($idBuku)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak lengkap!'
            ]);
        }

        $mPeminjaman = new M_Peminjaman();
        $mPeminjaman->hapusDataTemp([
            'id_anggota' => $idAnggota,
            'id_buku'    => $idBuku,
        ]);

        // Ambil keranjang terbaru
        $keranjang = $mPeminjaman->getDataTemp(['t.id_anggota' => $idAnggota])->getResultArray();

        return $this->response->setJSON([
            'status'    => 'success',
            'message'   => 'Buku dihapus dari keranjang!',
            'keranjang' => $keranjang,
            'total'     => count($keranjang),
        ]);
    }

    // ── AJAX: GET KERANJANG ───────────────────────────────────
    public function ajax_get_keranjang()
    {
        $idAnggota = $this->request->getGet('id_anggota');
        if (empty($idAnggota)) {
            return $this->response->setJSON(['keranjang' => [], 'total' => 0]);
        }

        $mPeminjaman = new M_Peminjaman();
        $keranjang   = $mPeminjaman->getDataTemp(['t.id_anggota' => $idAnggota])->getResultArray();

        return $this->response->setJSON([
            'keranjang' => $keranjang,
            'total'     => count($keranjang),
        ]);
    }

    // ── AJAX: SIMPAN TRANSAKSI ────────────────────────────────
    public function ajax_simpan_transaksi()
    {
        $idAnggota = $this->request->getPost('id_anggota');

        if (empty($idAnggota)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Pilih anggota terlebih dahulu!'
            ]);
        }

        $mPeminjaman = new M_Peminjaman();

        // Ambil keranjang
        $keranjang = $mPeminjaman->getDataTemp(['t.id_anggota' => $idAnggota])->getResultArray();

        if (empty($keranjang)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Keranjang masih kosong!'
            ]);
        }

        // Generate no_peminjaman: PJM-YYYYMMDD-001
        $tglHari  = date('Ymd');
        $prefix   = 'PJM-' . $tglHari . '-';
        $last     = $mPeminjaman->getLastNoPeminjaman($prefix);
        if (!$last) {
            $noPeminjaman = $prefix . '001';
        } else {
            $urut         = (int) substr($last['no_peminjaman'], -3) + 1;
            $noPeminjaman = $prefix . sprintf('%03d', $urut);
        }

        $tglPinjam  = date('Y-m-d');
        $tglKembali = date('Y-m-d', strtotime('+7 days'));
        $idAdmin    = session()->get('ses_id');
        $total      = count($keranjang);

        // ── Mulai transaksi DB ───────────────────────────────
        $this->db->transStart();

        // 1. Simpan header peminjaman
        $mPeminjaman->saveDataPeminjaman([
            'no_peminjaman'    => $noPeminjaman,
            'id_anggota'       => $idAnggota,
            'tgl_pinjam'       => $tglPinjam,
            'total_pinjam'     => $total,
            'id_admin'         => $idAdmin,
            'status_transaksi' => 'Berjalan',
            'status_ambil_buku'=> 'Belum Diambil',
            'qr_code'          => '',
        ]);

        // 2. Simpan detail + kurangi stok
        foreach ($keranjang as $item) {
            $mPeminjaman->saveDataDetail([
                'no_peminjaman' => $noPeminjaman,
                'id_buku'       => $item['id_buku'],
                'status_pinjam' => 'Sedang Dipinjam',
                'perpanjangan'  => 0,
                'tgl_kembali'   => $tglKembali,
            ]);

            // Kurangi stok buku
            $stokSkrg = (int) $item['jumlah_eksemplar'] - 1;
            $this->db->table('tbl_buku')
                     ->where('id_buku', $item['id_buku'])
                     ->update(['jumlah_eksemplar' => $stokSkrg]);
        }

        // 3. Kosongkan keranjang anggota ini
        $mPeminjaman->hapusSemuaTemp($idAnggota);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Transaksi gagal, silakan coba lagi!'
            ]);
        }

        return $this->response->setJSON([
            'status'        => 'success',
            'message'       => 'Transaksi peminjaman berhasil disimpan!',
            'no_peminjaman' => $noPeminjaman,
        ]);
    }
}