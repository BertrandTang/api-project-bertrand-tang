<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    /**
     * lister : Afficher la liste des livres.
     */
    public function index()
    {
        $books = Book::all();

        return BookResource::collection($books);
    }

    /**
     * créer : Enregistrer un nouveau livre.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(Book::$rules);

        $book = Book::create($validated);

        return new BookResource($book);
    }

    /**
     * afficher : Afficher un livre spécifique.
     */
    public function show(Book $book)
    {
        return new BookResource($book);
    }

    /**
     * modifier : Mettre à jour un livre spécifique.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            // 'sometimes' = ne valide QUE si le champ est présent dans le JSON
            'title' => ['sometimes', 'string', 'min:3', 'max:255'],
            'author' => ['sometimes', 'string', 'min:3', 'max:100'],
            'summary' => ['sometimes', 'string', 'min:10', 'max:500'],
            'isbn' => [
                'sometimes',
                'string',
                'size:13',
                Rule::unique('books', 'isbn')->ignore($book->id),
            ],
        ]);

        $book->update($validated);

        return new BookResource($book);
    }

    /**
     * supprimer : Supprimer un livre spécifique.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->noContent();
    }
}
