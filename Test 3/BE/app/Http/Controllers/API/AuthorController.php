<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Author;

use DB;
use App;

class AuthorController extends MasterController
{
    public function model(){
        return Author::class;
    }

    public function rules() : array{
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
        ];
    }

    public function searchable() : array{
        $modelTable = App::make($this->model())->getTable();
        return [
            $modelTable.'.name',
            $modelTable.'.email',
        ];
    }
}
