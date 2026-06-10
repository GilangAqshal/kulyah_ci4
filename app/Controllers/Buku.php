<?php
namespace App\Controllers;

use App\Models\M_Buku;
use App\Models\M_Kategori;
use App\Models\M_Rak;

class Buku extends BaseController
{
    private function cekSesi()
    {
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }
        return null;
    }

    private function cekAkses()
    {
        if (session()->get('ses_level') != '2') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses!');
            return redirect()->to(base_url('admin/dashboardAdmin'));
        }
        return null;
    }

    // Helper upload file — nama pendek, pasti muat di VARCHAR(100)
    private function uploadFile($inputName, $folder, $default = '')
    {
        $file = $this->request->getFile($inputName);
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Buat nama pendek: timestamp + 8 karakter random + ekstensi
            $ext      = $file->getExtension();
            $namaFile = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $file->move(FCPATH . 'Assets/uploads/' . $folder . '/', $namaFile);
            return $namaFile;
        }
        return $default;
    }

    public function master_data_buku()
    {
        if ($redir = $this->cekSesi()) return $redir;
        $model = new M_Buku();
        $data['data_buku'] = $model->getDataBuku(['b.is_delete_buku' => '0'])->getResultArray();
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterBuku/master-data-buku', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function input_data_buku()
    {
        if ($redir = $this->cekSesi()) return $redir;
        if ($redir = $this->cekAkses()) return $redir;
        $mKat = new M_Kategori();
        $mRak = new M_Rak();
        $data['data_kategori'] = $mKat->getDataKategori(['is_delete_kategori' => '0'])->getResultArray();
        $data['data_rak']      = $mRak->getDataRak(['is_delete_rak' => '0'])->getResultArray();
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterBuku/input-buku', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function simpan_data_buku()
    {
        if ($redir = $this->cekSesi()) return $redir;
        if ($redir = $this->cekAkses()) return $redir;

        $model = new M_Buku();
        $hasil = $model->autoNumber()->getRowArray();
        $id    = !$hasil ? 'BKU001' : 'BKU' . sprintf('%03d', (int)substr($hasil['id_buku'], 3) + 1);

        // Upload dengan nama pendek
        $namacover = $this->uploadFile('cover_buku', 'cover', 'no-image.jpg');
        $namaebook = $this->uploadFile('e_book', 'ebook', '');

        $model->saveDataBuku([
            'id_buku'          => $id,
            'judul_buku'       => $this->request->getPost('judul_buku'),
            'pengarang'        => $this->request->getPost('pengarang'),
            'penerbit'         => $this->request->getPost('penerbit'),
            'tahun'            => $this->request->getPost('tahun'),
            'jumlah_eksemplar' => $this->request->getPost('jumlah_eksemplar'),
            'id_kategori'      => $this->request->getPost('id_kategori'),
            'keterangan'       => $this->request->getPost('keterangan'),
            'id_rak'           => $this->request->getPost('id_rak'),
            'cover_buku'       => $namacover,
            'e_book'           => $namaebook,
            'is_delete_buku'   => '0',
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('success', 'Data Buku Berhasil Ditambahkan!');
        return redirect()->to(base_url('admin/master-data-buku'));
    }

    public function edit_data_buku($idEdit = null)
    {
        if ($redir = $this->cekSesi()) return $redir;
        if ($redir = $this->cekAkses()) return $redir;
        $mBuku = new M_Buku();
        $mKat  = new M_Kategori();
        $mRak  = new M_Rak();
        $data['data_buku']     = $mBuku->getDataBuku(['sha1(b.id_buku)' => $idEdit])->getRowArray();
        $data['data_kategori'] = $mKat->getDataKategori(['is_delete_kategori' => '0'])->getResultArray();
        $data['data_rak']      = $mRak->getDataRak(['is_delete_rak' => '0'])->getResultArray();
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterBuku/edit-buku', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function update_data_buku()
    {
        if ($redir = $this->cekSesi()) return $redir;
        if ($redir = $this->cekAkses()) return $redir;

        $model     = new M_Buku();
        $id        = $this->request->getPost('id_buku');
        $coverLama = $this->request->getPost('cover_lama');
        $ebookLama = $this->request->getPost('ebook_lama');

        // Pakai file lama jika tidak upload baru
        $namacover = $this->uploadFile('cover_buku', 'cover', $coverLama);
        $namaebook = $this->uploadFile('e_book', 'ebook', $ebookLama);

        $model->updateDataBuku([
            'judul_buku'       => $this->request->getPost('judul_buku'),
            'pengarang'        => $this->request->getPost('pengarang'),
            'penerbit'         => $this->request->getPost('penerbit'),
            'tahun'            => $this->request->getPost('tahun'),
            'jumlah_eksemplar' => $this->request->getPost('jumlah_eksemplar'),
            'id_kategori'      => $this->request->getPost('id_kategori'),
            'keterangan'       => $this->request->getPost('keterangan'),
            'id_rak'           => $this->request->getPost('id_rak'),
            'cover_buku'       => $namacover,
            'e_book'           => $namaebook,
            'updated_at'       => date('Y-m-d H:i:s'),
        ], ['id_buku' => $id]);

        session()->setFlashdata('success', 'Data Buku Berhasil Diperbarui!');
        return redirect()->to(base_url('admin/master-data-buku'));
    }

    public function hapus_data_buku($idHapus = null)
    {
        if ($redir = $this->cekSesi()) return $redir;
        if ($redir = $this->cekAkses()) return $redir;
        $model = new M_Buku();
        $model->updateDataBuku(
            ['is_delete_buku' => '1', 'updated_at' => date('Y-m-d H:i:s')],
            ['sha1(id_buku)'  => $idHapus]
        );
        session()->setFlashdata('success', 'Data Buku Berhasil Dihapus!');
        return redirect()->to(base_url('admin/master-data-buku'));
    }
}