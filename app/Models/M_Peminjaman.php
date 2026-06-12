<?php
namespace App\Models;
use CodeIgniter\Model;

class M_Peminjaman extends Model
{
    protected $table       = 'tbl_peminjaman';
    protected $tableTmp    = 'tbl_temp_peminjaman';
    protected $tableDetail = 'tbl_detail_peminjaman';

    // ── PEMINJAMAN ──────────────────────────────────────────
    public function getDataPeminjaman($where = false)
    {
        $builder = $this->db->table($this->table . ' p');
        $builder->select('p.*, a.nama_anggota, ad.nama_admin');
        $builder->join('tbl_anggota a',  'a.id_anggota = p.id_anggota',  'left');
        $builder->join('tbl_admin ad',   'ad.id_admin  = p.id_admin',    'left');
        $builder->orderBy('p.no_peminjaman', 'DESC');
        if ($where) $builder->where($where);
        return $builder->get();
    }

    public function saveDataPeminjaman($data)
    {
        return $this->db->table($this->table)->insert($data);
    }

    public function updateDataPeminjaman($data, $where)
    {
        $builder = $this->db->table($this->table);
        $builder->where($where);
        return $builder->update($data);
    }

    // Ambil no_peminjaman terakhir hari ini untuk autonumber
    public function getLastNoPeminjaman($prefix)
    {
        $builder = $this->db->table($this->table);
        $builder->select('no_peminjaman');
        $builder->like('no_peminjaman', $prefix, 'after');
        $builder->orderBy('no_peminjaman', 'DESC');
        $builder->limit(1);
        return $builder->get()->getRowArray();
    }

    // ── DETAIL ──────────────────────────────────────────────
    public function getDataDetail($where = false)
    {
        $builder = $this->db->table($this->tableDetail . ' d');
        $builder->select('d.*, b.judul_buku, b.pengarang, b.penerbit, b.tahun');
        $builder->join('tbl_buku b', 'b.id_buku = d.id_buku', 'left');
        if ($where) $builder->where($where);
        return $builder->get();
    }

    public function saveDataDetail($data)
    {
        return $this->db->table($this->tableDetail)->insert($data);
    }

    public function updateDataDetail($data, $where)
    {
        $builder = $this->db->table($this->tableDetail);
        $builder->where($where);
        return $builder->update($data);
    }

    // ── TEMP / KERANJANG ────────────────────────────────────
    public function getDataTemp($where = false)
    {
        $builder = $this->db->table($this->tableTmp . ' t');
        $builder->select('t.*, b.judul_buku, b.pengarang, b.penerbit, b.tahun, b.jumlah_eksemplar');
        $builder->join('tbl_buku b', 'b.id_buku = t.id_buku', 'left');
        if ($where) $builder->where($where);
        return $builder->get();
    }

    public function saveDataTemp($data)
    {
        return $this->db->table($this->tableTmp)->insert($data);
    }

    public function hapusDataTemp($where)
    {
        return $this->db->table($this->tableTmp)->delete($where);
    }

    public function hapusSemuaTemp($idAnggota)
    {
        return $this->db->table($this->tableTmp)
                        ->delete(['id_anggota' => $idAnggota]);
    }
}