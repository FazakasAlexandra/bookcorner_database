<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\BooksModel;

class Users extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $users = new UsersModel();

        $response = [
            'status'   => 200,
            'error'    => null,
            'data' => $users->getAllUsers()
        ];

        return $this->respond($response);
    }

    public function addUser(){
        $users = new UsersModel();
        $users->insertUser($this->request->getJSON(true));

        return $this->respondCreated([
            'status'   => 201,
            'error'    => null,
            'message' => "user was succesfully added !"
        ]);
    }

    public function getUserById($id){
        $usersModel = new UsersModel();
        $booksModel = new BooksModel();
        $user = $usersModel->getSingleUser(['id'=> $id]);
        $user->books = $booksModel->getUserBooks($user->id);
        
        return $this->respond([
            'status'   => 201,
            'error'    => null,
            'data' => $user
        ]);
    }

    public function getSingleUser($email, $password)
    {
        $users = new UsersModel();
        $booksModel = new BooksModel();

        if ($users->getUserByEmail($email)) {
            $user = $users->getSingleUser(['email' => $email, 'password' => $password]);

            if ($user) {
                $user->books = $booksModel->getUserBooks($user->id);
                return $this->respond([
                    'status'   => 200,
                    'error'    => null,
                    'data' => $user
                ]);

            } else {
                return $this->respond([
                    'status'   => 404,
                    'error'    => [
                        'message' => 'password is incorrect',
                        'type' => 'password'
                    ],
                    'data' => null
                ]);
            }

        } else {
            return $this->respond([
                'status'   => 404,
                'error'    => [
                    'message' => 'no user with this email found',
                    'type' => 'email'
                ],
                'data' => null
            ]);
        }
    }
}
