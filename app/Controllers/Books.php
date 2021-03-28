<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\BooksModel;
use App\Models\PhotosModel;

class Books extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $booksModel = new BooksModel();

        $response = [
            'status'   => 200,
            'error'    => null,
            'records' => $booksModel->countBooks(),
            'data' => $booksModel->getAllBooks()
        ];

        return $this->respond($response);
    }

    public function getWithOffset($offset){
        $booksModel = new BooksModel();

        $response = [
            'status'   => 200,
            'error'    => null,
            'records' => $booksModel->countBooks(),
            'data' => $booksModel->getAllBooks((int)$offset)
        ];

        return $this->respond($response);
    }
    
    public function single($bookId)
    {
        $booksModel = new BooksModel();

        $response = [
            'status'   => 200,
            'error'    => null,
            'records' => $booksModel->countBooks(),
            'data' => $booksModel->getSingle((int)$bookId)
        ];

        return $this->respond($response);
    }

    public function search($searchType = null, $searchValue = null)
    {

        $booksModel = new BooksModel();
        $searchResult = $booksModel->selectLike($searchType, $searchValue);

        $response = [
            'status'   => 200,
            'error'    => null,
            'records' => $searchResult['records'],
            'data' => $searchResult['data']
        ];

        return $this->respond($response);
    }

    public function addBook()
    {
        $response = $this->request->getJSON(true);

        $booksModel = new BooksModel();
        $bookId = $booksModel->insertBook($response); 

        $response = [
            'status'   => 201,
            'error'    => null,
            'message' => "the book was succesfully created !",
            'book_id' => $bookId
        ];

        return $this->respondCreated($response);
    }

    public function addBookCover($bookId){
        $photosModel = new PhotosModel();
        $photosModel->insertBookPhotos($this->request->getJSON(true), $bookId);

        $response = [
            'status'   => 204,
            'error'    => null,
            'message' => "book cover succesfully added !"
        ];

        return $this->respondUpdated($response);
    }

    public function updateBook()
    {
        $booksModel = new BooksModel();
        $booksModel->updateBook($this->request->getJSON(true));

        $response = [
            'status'   => 204,
            'error'    => null,
            'message' => "the book was succesfully updated !"
        ];

        return $this->respondUpdated($response);
    }

    public function deleteBook($bookId)
    {
        $booksModel = new BooksModel();
        $booksModel->deleteBook($bookId);

        $response = [
            'status'   => 204,
            'error'    => null,
            'message' => "the book was succesfully deleted !"
        ];

        return $this->respondDeleted($response);
    }

    // SETS a book as trade proposal for another book
    public function setPropose(){
        $req = $this->request->getJSON(true);

        $booksModel = new BooksModel();
        $booksModel->setProposedForBook($req['bookId'], $req['proposedForBookId']);

        $response = [
            'status'   => 204,
            'error'    => null,
            'message' => "book with id " .$req['bookId']. " was successfully proposed as trade for book with id " . $req['proposedForBookId']
        ];

        return $this->respond($response);
    }

    // GETS all the books that were proposed for target book( to wich book it belongs to)
    public function proposed($proposedForBookId)
    {
        $booksModel = new BooksModel();

        $response = [
            'status'   => 200,
            'error'    => null,
            'data' => $booksModel->getProposedForBook($proposedForBookId)
        ];

        return $this->respond($response);
    }
}
