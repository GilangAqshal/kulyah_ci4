<?php

namespace App\Models;

use CodeIgniter\Model; 

class M_Admin extends Model
{
    protected $table = 'tbl_admin';

    public function getDataAdmin($where = false)
    {
        if ($where === false) {
            $builder = $this->db->table($this->table);
            // $builder->orderBy('nama_admin', 'ASC');
            $builder->orderBy('id_admin', 'ASC');
            return $builder->get(); 
        } else {
            $builder = $this->db->table($this->table);
            $builder->select('*');
            $builder->where($where);
            // $builder->orderBy('nama_admin', 'ASC');
            $builder->orderBy('id_admin', 'ASC');
            return $builder->get();
        }
    }

    public function saveDataAdmin($data)
    {
        $builder = $this->db->table($this->table);
        return $builder->insert($data);
    }

    public function updateDataAdmin($data, $where)
    {
        $builder = $this->db->table($this->table);
        $builder->where($where);
        return $builder->update($data);
    }

    public function autoNumber()
    {
        $builder = $this->db->table($this->table); 
        $builder->select("id_admin");
        $builder->orderBy("id_admin", "DESC");
        $builder->limit(1);
        return $builder->get();
    }
}

