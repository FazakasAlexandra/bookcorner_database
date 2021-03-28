<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\PhotosModel;
use App\Models\UsersModel;
use App\Models\BooksTradeModel;

class BooksModel extends Model
{
    protected $table = 'books';

    public function getAllBooks($offset = 0)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('books');

        $booksData = $builder->get(9, $offset)->getResultObject();

        foreach ($booksData as $book) {
            $this->modifyBookData($book);
        }

        return $booksData;
    }

    public function getSingle($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('books');

        $book = $builder->where('id', $id)->get()->getRowObject();
        $this->modifyBookData($book);
        
        return $book;
    }

    public function countBooks()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('books');

        return $builder->countAll();
    }

    public function selectLike($searchType, $searchValue)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('books');
        $builder->like($searchType, $searchValue);
        $booksData = $builder->get()->getResultObject();

        foreach ($booksData as $book) {
            $this->modifyBookData($book);
        }

        $searchResults = ['data' => $booksData, 'records' => count($booksData)];
        return $searchResults;
    }

    public function insertBook($book)
    {
        unset($book['cover']);

        $db = \Config\Database::connect();
        $builder = $db->table('books');
        $builder->insert($book);
        return $db->insertID();
    }


    public function updateBook($book)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('books');
        $builder->set($book)->where('id', $book['id'])->update();
    }

    public function deleteBook($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('books');
        $builder->delete(['id' => $id]);
    }

    public function getUserBooks($userId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('books');

        $userBooks = $builder->where('owner_id', $userId)->get()->getResultObject();
        
        foreach ($userBooks as $book) {
            $this->modifyBookData($book);
            unset($book->owner);
        }

        return $userBooks;
    }

    // gets the book for which a given book is proposed as trade.
    public function getProposedForBook($proposedForBookId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('books');
        $proposedForBook = $builder->where('id', $proposedForBookId)->get()->getRowObject();

        if ($proposedForBook) {
            $this->modifyBookData($proposedForBook);
        }

        return $proposedForBook;
    }

    // sets the book for which a given book is proposed as trade
    public function setProposedForBook($bookId, $proposedForBookId){
        $db = \Config\Database::connect();
        $builder = $db->table('books');
        $builder->set('proposed_for_book_id', $proposedForBookId)->where('id', $bookId)->update();
    }

    function getBookCover($book){
        foreach($book->photos as $photo){
            if($photo->is_cover_photo){
                return 'http://localhost/bookcorner/public/assets/books_pictures/' . $photo->url;
            }
        }
    }

    function modifyBookData($book)
    {
        $photosModel = new PhotosModel();
        $book->photos = $photosModel->getBookPhotos($book->id);

        $conditionsModel = new ConditionsModel();
        $book->condition = $conditionsModel->getCondition($book->condition_fk)->condition_name;

        $booksTradeModel = new BooksTradeModel();
        $book->trade = $booksTradeModel->getBooksTrade($book->id);

        $usersModel = new UsersModel();
        $book->owner = $usersModel->getSingleUser(['id'=>$book->owner_id]);

        $book->proposed_for = $this->getProposedForBook($book->proposed_for_book_id);

        $book->cover = $this->getBookCover($book);

        unset($book->proposed_for_book_id);
        unset($book->owner_id);
    }
}
