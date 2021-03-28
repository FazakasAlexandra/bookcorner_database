<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';

    public function getAllUsers()
    {
        return $this->findAll();
    }

    public function getSingleUser($userData){
        $db = \Config\Database::connect();
        $builder = $db->table('users');

        return $builder->where($userData)->get()->getRowObject();
    }

    public function getUserByEmail($email){
        $db = \Config\Database::connect();
        $builder = $db->table('users');

        return $builder->where('email', $email)->get()->getRowObject();
    }

    public function insertUser($user){
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->insert($user);
    }
}
