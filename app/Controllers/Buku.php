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

        $model = new M_Buku();

        $hasil = $model->autoNumber()->getRowArray();
        $id    = !$hasil ? 'BKU001' : 'BKU' . sprintf('%03d', (int)substr($hasil['id_buku'], -3) + 1);

        // Upload cover
        $cover = $this->request->getFile('cover_buku');
        $namacover = 'no-image.jpg';
        if ($cover && $cover->isValid() && !$cover->hasMoved()) {
            $namacover = $cover->getRandomName();
            $cover->move(FCPATH . 'Assets/uploads/cover/', $namacover);
        }

        // Upload e-book
        $ebook = $this->request->getFile('e_book');
        $namaebook = '';
        if ($ebook && $ebook->isValid() && !$ebook->hasMoved()) {
            $namaebook = $ebook->getRandomName();
            $ebook->move(FCPATH . 'Assets/uploads/ebook/', $namaebook);
        }

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

        $model     = new M_Buku();
        $id        = $this->request->getPost('id_buku');
        $coverLama = $this->request->getPost('cover_lama');
        $ebookLama = $this->request->getPost('ebook_lama');

        // Proses cover baru
        $cover = $this->request->getFile('cover_buku');
        if ($cover && $cover->isValid() && !$cover->hasMoved()) {
            $namacover = $cover->getRandomName();
            $cover->move(FCPATH . 'Assets/uploads/cover/', $namacover);
        } else {
            $namacover = $coverLama;
        }

        // Proses e-book baru
        $ebook = $this->request->getFile('e_book');
        if ($ebook && $ebook->isValid() && !$ebook->hasMoved()) {
            $namaebook = $ebook->getRandomName();
            $ebook->move(FCPATH . 'Assets/uploads/ebook/', $namaebook);
        } else {
            $namaebook = $ebookLama;
        }

        $model->updateDataBuku(
            [
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
            ],
            ['id_buku' => $id]
        );

        session()->setFlashdata('success', 'Data Buku Berhasil Diperbarui!');
        return redirect()->to(base_url('admin/master-data-buku'));
    }

    public function hapus_data_buku($idHapus = null)
    {
        if ($redir = $this->cekSesi()) return $redir;

        $model = new M_Buku();
        $model->updateDataBuku(
            ['is_delete_buku' => '1', 'updated_at' => date('Y-m-d H:i:s')],
            ['sha1(id_buku)' => $idHapus]
        );

        session()->setFlashdata('success', 'Data Buku Berhasil Dihapus!');
        return redirect()->to(base_url('admin/master-data-buku'));
    }
}