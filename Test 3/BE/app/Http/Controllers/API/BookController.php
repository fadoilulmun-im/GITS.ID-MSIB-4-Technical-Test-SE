<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Book;

use DB;
use App;

class BookController extends MasterController
{
    public function model(){
        return Book::class;
    }

    public function rules() : array{
        return [
            'name' => 'required|string',
            'author_id' => 'required|exists:App\Models\Author,id,deleted_at,NULL',
            'publisher_id' => 'required|exists:App\Models\Publisher,id,deleted_at,NULL',
        ];
    }

    public function searchable() : array{
        $modelTable = App::make($this->model())->getTable();
        return [
            $modelTable.'.name',
            'author_name',
            'publisher_name',
        ];
    }

    public function select(){
        return $this->model()::select([
            'books.*',
            'authors.name AS author_name',
            'publishers.name AS publisher_name',
        ])
        ->leftJoin('authors', 'authors.id', '=', 'books.author_id')
        ->leftJoin('publishers', 'publishers.id', '=', 'books.publisher_id')
        ;
    }
}
