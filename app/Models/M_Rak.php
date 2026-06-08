<?php
namespace App\Models;
use CodeIgniter\Model;

class M_Rak extends Model
{
    protected $table = 'tbl_rak';

    public function getDataRak($where = false)
    {
        $builder = $this->db->table($this->table);
        $builder->orderBy('id_rak', 'ASC');
        if ($where) $builder->where($where);
        return $builder->get();
    }

    public function saveDataRak($data)
    {
        return $this->db->table($this->table)->insert($data);
    }

    public function updateDataRak($data, $where)
    {
        $builder = $this->db->table($this->table);
        $builder->where($where);
        return $builder->update($data);
    }

    public function autoNumber()
    {
        $builder = $this->db->table($this->table);
        $builder->select('id_rak');
        $builder->orderBy('id_rak', 'DESC');
        $builder->limit(1);
        return $builder->get();
    }
}