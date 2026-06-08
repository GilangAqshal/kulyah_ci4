<?php
namespace App\Models;
use CodeIgniter\Model;

class M_Anggota extends Model
{
    protected $table = 'tbl_anggota';

    public function getDataAnggota($where = false)
    {
        $builder = $this->db->table($this->table);
        $builder->orderBy('id_anggota', 'ASC');
        if ($where) $builder->where($where);
        return $builder->get();
    }

    public function saveDataAnggota($data)
    {
        return $this->db->table($this->table)->insert($data);
    }

    public function updateDataAnggota($data, $where)
    {
        $builder = $this->db->table($this->table);
        $builder->where($where);
        return $builder->update($data);
    }

    public function autoNumber()
    {
        $builder = $this->db->table($this->table);
        $builder->select('id_anggota');
        $builder->orderBy('id_anggota', 'DESC');
        $builder->limit(1);
        return $builder->get();
    }
}