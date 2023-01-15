<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Book extends Model
{
    use HasFactory, Userstamps, SoftDeletes;
    protected $guarded = [
        'id',
        'created_by', 'created_at',
        'updated_by', 'updated_at',
        'deleted_by', 'deleted_at',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
        'created_by', 'updated_by', 'deleted_by',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }
}
