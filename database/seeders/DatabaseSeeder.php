<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Language;
use App\Models\Publisher;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create some categories
        $categories = [
            ['category' => 'Fiction', 'slug' => 'fiction'],
            ['category' => 'Non-Fiction', 'slug' => 'non-fiction'],
            ['category' => 'Science', 'slug' => 'science'],
            ['category' => 'Fantasy', 'slug' => 'fantasy'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Create supporting data (authors, publishers, language)
        $author    = Author::firstOrCreate(['name' => 'John Doe']);
        $publisher = Publisher::firstOrCreate(['name' => 'OpenAI Publishing']);
        $language  = Language::firstOrCreate(['name' => 'English', 'code' => 'en']);

        // Create some books
        $books = [
            [
                'title' => 'The Future of AI',
                'meta_title' => 'AI and the Future',
                'description' => 'A deep dive into the future of artificial intelligence.',
                'author_id' => $author->id,
                'publisher_id' => $publisher->id,
                'cover' => 'covers/ai_future.jpg',
                'file' => 'books/ai_future.pdf',
                'file_type' => 'pdf',
                'download_count' => 120,
                'read_count' => 300,
                'language_id' => $language->id,
                'pages_number' => 250,
                'slug' => Str::slug('The Future of AI'),
                'status' => Book::STATUS_ACCEPTED,
            ],
            [
                'title' => 'Journey to the Stars',
                'meta_title' => 'A Sci-Fi Adventure',
                'description' => 'A thrilling science fiction adventure into space.',
                'author_id' => $author->id,
                'publisher_id' => $publisher->id,
                'cover' => 'covers/journey_stars.jpg',
                'file' => 'books/journey_stars.epub',
                'file_type' => 'epub',
                'download_count' => 50,
                'read_count' => 100,
                'language_id' => $language->id,
                'pages_number' => 320,
                'slug' => Str::slug('Journey to the Stars'),
                'status' => Book::STATUS_PENDING,
            ],
        ];

        foreach ($books as $bookData) {
            $book = Book::firstOrCreate(['slug' => $bookData['slug']], $bookData);

            // Attach categories (randomly for demo)
            $book->categories()->syncWithoutDetaching(
                Category::inRandomOrder()->take(2)->pluck('id')->toArray()
            );
        }
    }
}
