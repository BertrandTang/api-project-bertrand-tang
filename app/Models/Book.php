<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    //
    protected $fillable = [
        'title',
        'author',
        'summary',
        'isbn',
    ];

    /**
     * Règles de validation pour Book
     */
    public static $rules = [
        'title' => 'required|string|min:3|max:255',
        'author' => 'required|string|min:3|max:100',
        'summary' => 'required|string|min:10|max:500',
        'isbn' => 'required|string|size:13|unique:books,isbn',
    ];
}
