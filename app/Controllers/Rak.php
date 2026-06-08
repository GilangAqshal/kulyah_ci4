<?php
namespace App\Controllers;

use App\Models\M_Rak;

class Rak extends BaseController
{
    private function cekSesi()
    {
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }
        return null;
    }

    public function master_data_rak()
    {
        if ($redir = $this->cekSesi()) return $redir;
        $model = new M_Rak();
        $data['data_rak'] = $model->getDataRak(['is_delete_rak' => '0'])->getResultArray();
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterRak/master-data-rak', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function input_data_rak()
    {
        if ($redir = $this->cekSesi()) return $redir;
        echo view('Backend/Template/header');
        echo view('Backend/Template/sidebar');
        echo view('Backend/MasterRak/input-rak');
        echo view('Backend/Template/footer');
    }

    public function simpan_data_rak()
    {
        if ($redir = $this->cekSesi()) return $redir;
        $model = new M_Rak();
        $hasil = $model->autoNumber()->getRowArray();
        $id    = !$hasil ? 'RAK001' : 'RAK' . sprintf('%03d', (int)substr($hasil['id_rak'], -3) + 1);

        $model->saveDataRak([
            'id_rak'        => $id,
            'nama_rak'      => $this->request->getPost('nama_rak'),
            'is_delete_rak' => '0',
            'created_at'    => date('Y-m-d H:i:s'),
            'update_at'     => date('Y-m-d H:i:s'), // ← PERBAIKAN: Ganti ke update_at
        ]);
        session()->setFlashdata('success', 'Data Rak Berhasil Ditambahkan!');
        return redirect()->to(base_url('admin/master-data-rak'));
    }

    public function edit_data_rak($idEdit = null)
    {
        if ($redir = $this->cekSesi()) return $redir;
        $model = new M_Rak();
        $data['data_rak'] = $model->getDataRak(['sha1(id_rak)' => $idEdit])->getRowArray();
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterRak/edit-rak', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function update_data_rak()
    {
        if ($redir = $this->cekSesi()) return $redir;
        $model = new M_Rak();
        $model->updateDataRak(
            ['nama_rak'  => $this->request->getPost('nama_rak'), 'update_at' => date('Y-m-d H:i:s')], // ← PERBAIKAN: update_at
            ['id_rak'    => $this->request->getPost('id_rak')]
        );
        session()->setFlashdata('success', 'Data Rak Berhasil Diperbarui!');
        return redirect()->to(base_url('admin/master-data-rak'));
    }

    public function hapus_data_rak($idHapus = null)
    {
        if ($redir = $this->cekSesi()) return $redir;
        $model = new M_Rak();
        $model->updateDataRak(
            ['is_delete_rak' => '1', 'update_at' => date('Y-m-d H:i:s')], // ← PERBAIKAN: update_at
            ['sha1(id_rak)'  => $idHapus]
        );
        session()->setFlashdata('success', 'Data Rak Berhasil Dihapus!');
        return redirect()->to(base_url('admin/master-data-rak'));
    }
}