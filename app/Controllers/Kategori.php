<?php
namespace App\Controllers;

use App\Models\M_Kategori;

class Kategori extends BaseController
{
    private function cekSesi()
    {
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }
        return null;
    }

    public function master_data_kategori()
    {
        if ($redir = $this->cekSesi()) return $redir;

        $model = new M_Kategori();
        $data['data_kategori'] = $model->getDataKategori(['is_delete_kategori' => '0'])->getResultArray();

        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterKategori/master-data-kategori', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function input_data_kategori()
    {
        if ($redir = $this->cekSesi()) return $redir;
        echo view('Backend/Template/header');
        echo view('Backend/Template/sidebar');
        echo view('Backend/MasterKategori/input-kategori');
        echo view('Backend/Template/footer');
    }

    public function simpan_data_kategori()
    {
        if ($redir = $this->cekSesi()) return $redir;

        $model = new M_Kategori();
        $nama  = $this->request->getPost('nama_kategori');

        $hasil  = $model->autoNumber()->getRowArray();
        $id     = !$hasil ? 'KAT001' : 'KAT' . sprintf('%03d', (int)substr($hasil['id_kategori'], -3) + 1);

        $model->saveDataKategori([
            'id_kategori'       => $id,
            'nama_kategori'     => $nama,
            'is_delete_kategori'=> '0',
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('success', 'Data Kategori Berhasil Ditambahkan!');
        return redirect()->to(base_url('admin/master-data-kategori'));
    }

    public function edit_data_kategori($idEdit = null)
    {
        if ($redir = $this->cekSesi()) return $redir;

        $model = new M_Kategori();
        $data['data_kategori'] = $model->getDataKategori(['sha1(id_kategori)' => $idEdit])->getRowArray();

        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterKategori/edit-kategori', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function update_data_kategori()
    {
        if ($redir = $this->cekSesi()) return $redir;

        $model = new M_Kategori();
        $id    = $this->request->getPost('id_kategori');
        $nama  = $this->request->getPost('nama_kategori');

        $model->updateDataKategori(
            ['nama_kategori' => $nama, 'updated_at' => date('Y-m-d H:i:s')],
            ['id_kategori' => $id]
        );

        session()->setFlashdata('success', 'Data Kategori Berhasil Diperbarui!');
        return redirect()->to(base_url('admin/master-data-kategori'));
    }

    public function hapus_data_kategori($idHapus = null)
    {
        if ($redir = $this->cekSesi()) return $redir;

        $model = new M_Kategori();
        $model->updateDataKategori(
            ['is_delete_kategori' => '1', 'updated_at' => date('Y-m-d H:i:s')],
            ['sha1(id_kategori)' => $idHapus]
        );

        session()->setFlashdata('success', 'Data Kategori Berhasil Dihapus!');
        return redirect()->to(base_url('admin/master-data-kategori'));
    }
}