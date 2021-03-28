<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;

class FileUpload extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $img = $this->request->getFile('file');
        $newName = $img->getRandomName();
        $img->move('public/assets/books_pictures', $newName);

        $response = [
            'status'   => 201,
            'error'    => null,
            'message' => "picture uploaded !",
            'size' => $img->getSize('mb'),
            'file_name' => $newName,
            'file_path' => 'public/assets/books_pictures/'.$newName
        ];

        return $this->respondCreated($response);
    }
}