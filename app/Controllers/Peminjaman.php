<?php
namespace App\Controllers;

use App\Models\M_Peminjaman;
use App\Models\M_Anggota;
use App\Models\M_Buku;

class Peminjaman extends BaseController
{
    // ── HELPER: CEK SESI ─────────────────────────────────────
    private function cekSesi()
    {
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }
        return null;
    }

    // ── HELPER: JSON RESPONSE + CSRF ─────────────────────────
    private function jsonResponse($data)
    {
        $data['csrf_hash'] = csrf_hash();
        return $this->response
                    ->setHeader('X-CSRF-TOKEN', csrf_hash())
                    ->setJSON($data);
    }

    // ── HALAMAN UTAMA ─────────────────────────────────────────
    public function index()
    {
        if ($redir = $this->cekSesi()) return $redir;
        $data['title'] = 'Transaksi Peminjaman';
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/Transaksi/form-peminjaman', $data);
        echo view('Backend/Template/footer', $data);
    }

    // ── HALAMAN DATA TRANSAKSI ────────────────────────────────
    public function data_transaksi()
    {
        if ($redir = $this->cekSesi()) return $redir;
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
        if ($redir = $this->cekSesi()) return $redir;
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

    // ── AJAX: CARI ANGGOTA ────────────────────────────────────
    public function ajax_cari_anggota()
    {
        $keyword = $this->request->getPost('q') ?? '';

        $builder = $this->db->table('tbl_anggota');
        $builder->select('id_anggota, nama_anggota');
        $builder->where('is_delete_anggota', '0');
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('id_anggota', $keyword)
                    ->orLike('nama_anggota', $keyword)
                    ->groupEnd();
        }
        $builder->limit(10);
        $result = $builder->get()->getResultArray();

        $output = [];
        foreach ($result as $row) {
            $output[] = [
                'id'   => $row['id_anggota'],
                'text' => $row['id_anggota'] . ' - ' . $row['nama_anggota'],
            ];
        }

        return $this->jsonResponse(['results' => $output]);
    }

    // ── AJAX: CARI BUKU ───────────────────────────────────────
    public function ajax_cari_buku()
    {
        $keyword = $this->request->getPost('q') ?? '';

        $builder = $this->db->table('tbl_buku b');
        $builder->select('b.id_buku, b.judul_buku, b.pengarang, b.jumlah_eksemplar, k.nama_kategori');
        $builder->join('tbl_kategori k', 'k.id_kategori = b.id_kategori', 'left');
        $builder->where('b.is_delete_buku', '0');
        $builder->where('b.jumlah_eksemplar >', 0);
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('b.judul_buku', $keyword)
                    ->orLike('b.pengarang', $keyword)
                    ->orLike('b.id_buku', $keyword)
                    ->groupEnd();
        }
        $builder->limit(10);
        $result = $builder->get()->getResultArray();

        $output = [];
        foreach ($result as $row) {
            $output[] = [
                'id'   => $row['id_buku'],
                'text' => $row['judul_buku'] . ' — ' . $row['pengarang']
                        . ' (Stok: ' . $row['jumlah_eksemplar'] . ')',
                'stok' => $row['jumlah_eksemplar'],
            ];
        }

        return $this->jsonResponse(['results' => $output]);
    }

    // ── AJAX: TAMBAH KE KERANJANG ─────────────────────────────
    public function ajax_tambah_keranjang()
    {
        $idAnggota = $this->request->getPost('id_anggota');
        $idBuku    = $this->request->getPost('id_buku');

        if (empty($idAnggota) || empty($idBuku)) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Data tidak lengkap!']);
        }

        $mPeminjaman = new M_Peminjaman();

        // Cek stok buku
        $buku = $this->db->table('tbl_buku')->where('id_buku', $idBuku)->get()->getRowArray();
        if (!$buku || $buku['jumlah_eksemplar'] <= 0) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Stok buku habis!']);
        }

        // Cek buku sudah di keranjang
        $cekTemp = $this->db->table('tbl_temp_peminjaman')
                            ->where(['id_anggota' => $idAnggota, 'id_buku' => $idBuku])
                            ->get()->getNumRows();
        if ($cekTemp > 0) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Buku sudah ada di keranjang!']);
        }

        // Cek peminjaman berjalan
        $cekBerjalan = $this->db->table('tbl_peminjaman')
                                ->where(['id_anggota' => $idAnggota, 'status_transaksi' => 'Berjalan'])
                                ->get()->getNumRows();
        if ($cekBerjalan > 0) {
            return $this->jsonResponse([
                'status'  => 'error',
                'message' => 'Anggota masih memiliki peminjaman yang belum selesai!'
            ]);
        }

        $mPeminjaman->saveDataTemp([
            'id_anggota'  => $idAnggota,
            'id_buku'     => $idBuku,
            'jumlah_temp' => 1,
        ]);

        $keranjang = $mPeminjaman->getDataTemp(['t.id_anggota' => $idAnggota])->getResultArray();

        return $this->jsonResponse([
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
            return $this->jsonResponse(['status' => 'error', 'message' => 'Data tidak lengkap!']);
        }

        $mPeminjaman = new M_Peminjaman();
        $mPeminjaman->hapusDataTemp(['id_anggota' => $idAnggota, 'id_buku' => $idBuku]);

        $keranjang = $mPeminjaman->getDataTemp(['t.id_anggota' => $idAnggota])->getResultArray();

        return $this->jsonResponse([
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
            return $this->jsonResponse(['keranjang' => [], 'total' => 0]);
        }

        $mPeminjaman = new M_Peminjaman();
        $keranjang   = $mPeminjaman->getDataTemp(['t.id_anggota' => $idAnggota])->getResultArray();

        return $this->jsonResponse([
            'keranjang' => $keranjang,
            'total'     => count($keranjang),
        ]);
    }

    // ── AJAX: SIMPAN TRANSAKSI ────────────────────────────────
    public function ajax_simpan_transaksi()
    {
        $idAnggota = $this->request->getPost('id_anggota');

        if (empty($idAnggota)) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Pilih anggota terlebih dahulu!']);
        }

        $mPeminjaman = new M_Peminjaman();
        $keranjang   = $mPeminjaman->getDataTemp(['t.id_anggota' => $idAnggota])->getResultArray();

        if (empty($keranjang)) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Keranjang masih kosong!']);
        }

        // Generate no_peminjaman
        $tglHari      = date('Ymd');
        $prefix       = 'PJM-' . $tglHari . '-';
        $last         = $mPeminjaman->getLastNoPeminjaman($prefix);
        $noPeminjaman = !$last
            ? $prefix . '001'
            : $prefix . sprintf('%03d', (int) substr($last['no_peminjaman'], -3) + 1);

        $tglPinjam  = date('Y-m-d');
        $tglKembali = date('Y-m-d', strtotime('+7 days'));
        $idAdmin    = session()->get('ses_id');
        $total      = count($keranjang);

        $this->db->transStart();

        $mPeminjaman->saveDataPeminjaman([
            'no_peminjaman'     => $noPeminjaman,
            'id_anggota'        => $idAnggota,
            'tgl_pinjam'        => $tglPinjam,
            'total_pinjam'      => $total,
            'id_admin'          => $idAdmin,
            'status_transaksi'  => 'Berjalan',
            'status_ambil_buku' => 'Belum Diambil',
            'qr_code'           => '',
        ]);

        foreach ($keranjang as $item) {
            $mPeminjaman->saveDataDetail([
                'no_peminjaman' => $noPeminjaman,
                'id_buku'       => $item['id_buku'],
                'status_pinjam' => 'Sedang Dipinjam',
                'perpanjangan'  => 0,
                'tgl_kembali'   => $tglKembali,
            ]);

            $stokBaru = (int) $item['jumlah_eksemplar'] - 1;
            $this->db->table('tbl_buku')
                     ->where('id_buku', $item['id_buku'])
                     ->update(['jumlah_eksemplar' => $stokBaru]);
        }

        $mPeminjaman->hapusSemuaTemp($idAnggota);
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Transaksi gagal, coba lagi!']);
        }

        return $this->jsonResponse([
            'status'        => 'success',
            'message'       => 'Transaksi berhasil disimpan!',
            'no_peminjaman' => $noPeminjaman,
        ]);
    }
}