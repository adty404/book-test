<?php

namespace App\Http\Resources;

use App\Author;
use App\Book;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\General;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            // @TODO implement DONE:
            'id' => $this->id,
            'isbn' => $this->isbn,
            'title' => $this->title,
            'description' => $this->description,
            'published_year' => $this->published_year,
            'authors' => AuthorResource::collection($this->authors),
            'book_contents' => BookContentResource::collection($this->bookContents),
            'price' => $this->price,
            'price_rupiah' => usd_to_rupiah_format($this->price),
            'review' => [
                'avg' => (int)round($this->reviews->avg('review')),
                'count' => (int)$this->reviews->count(),
            ],
        ];
    }
}
