<?php

namespace App\Models;

use CodeIgniter\Model;

class ConditionsModel extends Model
{
    public function getCondition($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('conditions');

        return $builder->select('condition_name')->where(['id' => $id])->get()->getRowObject();
    }
}
