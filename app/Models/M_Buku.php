<?php
namespace App\Models;
use CodeIgniter\Model;

class M_Buku extends Model
{
    protected $table = 'tbl_buku';

    public function getDataBuku($where = false)
    {
        $builder = $this->db->table($this->table . ' b');
        $builder->select('b.*, k.nama_kategori, r.nama_rak');
        $builder->join('tbl_kategori k', 'k.id_kategori = b.id_kategori', 'left');
        $builder->join('tbl_rak r', 'r.id_rak = b.id_rak', 'left');
        $builder->orderBy('b.id_buku', 'ASC');
        if ($where) $builder->where($where);
        return $builder->get();
    }

    public function saveDataBuku($data)
    {
        return $this->db->table($this->table)->insert($data);
    }

    public function updateDataBuku($data, $where)
    {
        $builder = $this->db->table($this->table);
        $builder->where($where);
        return $builder->update($data);
    }

    public function autoNumber()
    {
        $builder = $this->db->table($this->table);
        $builder->select('id_buku');
        $builder->orderBy('id_buku', 'DESC');
        $builder->limit(1);
        return $builder->get();
    }
}