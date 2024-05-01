<?php

namespace App\Jobs;

use App\Book;
use App\BookContent;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RetreiveBookContentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public Book $book
    ) {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // @TODO implement DONE:
        $response = Http::get("https://rak-buku-api.vercel.app/api/books/{$this->book->isbn}");

        if ($response->successful()) {
            $contents = $response->json()['data']['details']['table_of_contents'] ?? [];

            foreach ($contents as $content) {
                BookContent::create([
                    'book_id' => $this->book->id,
                    'label' => $content['label'],
                    'title' => $content['title'],
                    'page_number' => $content['pagenum']
                ]);
            }
        } else if ($response->status() == 404) {
            BookContent::create([
                'book_id' => $this->book->id,
                'label' => null,
                'title' => 'Cover',
                'page_number' => 1
            ]);
        }
    }
}
