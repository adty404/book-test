<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookReview;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookReviewResource;
use Illuminate\Http\Request;

class BooksReviewController extends Controller
{
    public function __construct()
    {
    }

    public function store(int $bookId, PostBookReviewRequest $request)
    {
        // @TODO implement DONE:
        $book = Book::findOrFail($bookId);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $bookReview = BookReview::create([
            'book_id' => $book->id,
            'user_id' => $request->user()->id,
            'review' => $request->validated()['review'],
            'comment' => $request->validated()['comment']
        ]);

        return response()->json([
            'data' => new BookReviewResource($bookReview)
        ], 201);
    }

    public function destroy(int $bookId, int $reviewId, Request $request)
    {
        // @TODO implement DONE:
        $bookReview = BookReview::where('book_id', $bookId)
            ->where('id', $reviewId)
            ->firstOrFail();

        $bookReview->delete();

        return response()->noContent();
    }
}
