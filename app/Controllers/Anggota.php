<?php
namespace App\Controllers;

use App\Models\M_Anggota; // ← INI WAJIB ADA

class Anggota extends BaseController
{
    private function cekSesi()
    {
        if (!session()->get('ses_id')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        }
        return null;
    }

    public function master_data_anggota()
    {
        if ($redir = $this->cekSesi()) return $redir;
        $model = new M_Anggota();
        $data['data_anggota'] = $model->getDataAnggota(['is_delete_anggota' => '0'])->getResultArray();
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterAnggota/master-data-anggota', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function input_data_anggota()
    {
        if ($redir = $this->cekSesi()) return $redir;
        echo view('Backend/Template/header');
        echo view('Backend/Template/sidebar');
        echo view('Backend/MasterAnggota/input-anggota');
        echo view('Backend/Template/footer');
    }

    public function simpan_data_anggota()
    {
        if ($redir = $this->cekSesi()) return $redir;
        $model  = new M_Anggota();
        $hasil  = $model->autoNumber()->getRowArray();
        $id     = !$hasil ? 'ANG001' : 'ANG' . sprintf('%03d', (int)substr($hasil['id_anggota'], -3) + 1);

        $model->saveDataAnggota([
            'id_anggota'        => $id,
            'nama_anggota'      => $this->request->getPost('nama'),
            'jenis_kelamin'     => $this->request->getPost('jenis_kelamin'),
            'noTelp'            => $this->request->getPost('noTelp'),
            'alamat'            => $this->request->getPost('alamat'),
            'is_delete_anggota' => '0',
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);
        session()->setFlashdata('success', 'Data Anggota Berhasil Ditambahkan!');
        return redirect()->to(base_url('admin/master-data-anggota'));
    }

    public function edit_data_anggota($idEdit = null)
    {
        if ($redir = $this->cekSesi()) return $redir;
        $model = new M_Anggota();
        $data['data_anggota'] = $model->getDataAnggota(['sha1(id_anggota)' => $idEdit])->getRowArray();
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterAnggota/edit-anggota', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function update_data_anggota()
    {
        if ($redir = $this->cekSesi()) return $redir;
        $model = new M_Anggota();
        $model->updateDataAnggota(
            [
                'nama_anggota'  => $this->request->getPost('nama'),
                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                'noTelp'        => $this->request->getPost('noTelp'),
                'alamat'        => $this->request->getPost('alamat'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            ['id_anggota' => $this->request->getPost('id_anggota')]
        );
        session()->setFlashdata('success', 'Data Anggota Berhasil Diperbarui!');
        return redirect()->to(base_url('admin/master-data-anggota'));
    }

    public function hapus_data_anggota($idHapus = null)
    {
        if ($redir = $this->cekSesi()) return $redir;
        $model = new M_Anggota();
        $model->updateDataAnggota(
            ['is_delete_anggota' => '1', 'updated_at' => date('Y-m-d H:i:s')],
            ['sha1(id_anggota)'  => $idHapus]
        );
        session()->setFlashdata('success', 'Data Anggota Berhasil Dihapus!');
        return redirect()->to(base_url('admin/master-data-anggota'));
    }
}