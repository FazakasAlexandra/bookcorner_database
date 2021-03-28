<?php

namespace App\Models;

use CodeIgniter\Model;

class PhotosModel extends Model
{
    public function getBookPhotos($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('photos');
        $query = $builder->getWhere(['book_fk' => $id]);
        $arr = array();

        foreach ($query->getResult() as $row) {
            array_push($arr, $row);
        }

        return $arr;
    }

    public function insertBookPhotos($photo)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('photos');

        $builder->insert($photo);
    }
}
