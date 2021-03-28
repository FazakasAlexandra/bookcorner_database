<?php

namespace App\Models;

use CodeIgniter\Model;

class BooksTradeModel extends Model
{
    public function getBooksTrade($bookId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('books_trade');

        return $builder->where('offered_book_id', $bookId)->select('id, title, author, language')->get()->getResultArray();
    }
}
