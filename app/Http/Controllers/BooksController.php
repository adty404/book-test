<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\PostBookRequest;
use App\Http\Resources\BookResource;
use App\Jobs\RetreiveBookContentsJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BooksController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $query = Book::query();

        // Filtering by title
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        // Filtering by author's id
        if ($request->has('authors')) {
            $authorIDs = explode(',', $request->input('authors'));
            $query->whereHas('authors', function ($q) use ($authorIDs) {
                $q->whereIn('id', $authorIDs);
            });
        }

        // Sorting
        if ($request->has('sortColumn')) {
            $sortColumn = $request->input('sortColumn');
            $sortDirection = $request->input('sortDirection', 'ASC');

            switch ($sortColumn) {
                case 'published_year':
                case 'title':
                    $query->orderBy($sortColumn, $sortDirection);
                    break;
                case 'avg_review':
                    $query->withCount(['reviews as avg_review' => function ($query) {
                        $query->select(DB::raw('coalesce(avg(review), 0)'));
                    }]);
                    $query->orderBy('avg_review', $sortDirection);
                    break;
                default:
                    $query->orderBy('title', 'ASC');
                    break;
            }
        }

        // Pagination
        return BookResource::collection($query->paginate(null, '*', 'page', $request->get('page', 1)));
    }

    public function store(PostBookRequest $request)
    {
        // @TODO implement DONE:
        $book = Book::create($request->validated());
        $book->authors()->attach($request->authors);

        dispatch(new RetreiveBookContentsJob($book));

        return response()->json([
            'data' => new BookResource($book)
        ], 201);
    }
}
