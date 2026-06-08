<?php
namespace App\Models;
use CodeIgniter\Model;

class M_Kategori extends Model
{
    protected $table = 'tbl_kategori';

    public function getDataKategori($where = false)
    {
        $builder = $this->db->table($this->table);
        $builder->orderBy('id_kategori', 'ASC');
        if ($where) $builder->where($where);
        return $builder->get();
    }

    public function saveDataKategori($data)
    {
        return $this->db->table($this->table)->insert($data);
    }

    public function updateDataKategori($data, $where)
    {
        $builder = $this->db->table($this->table);
        $builder->where($where);
        return $builder->update($data);
    }

    public function autoNumber()
    {
        $builder = $this->db->table($this->table);
        $builder->select('id_kategori');
        $builder->orderBy('id_kategori', 'DESC');
        $builder->limit(1);
        return $builder->get();
    }
}