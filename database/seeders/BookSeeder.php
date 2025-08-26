<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $title = $faker->sentence(3);

            Book::create([
                'title'         => $title,
                'meta_title'    => $faker->sentence(6),
                'description'   => $faker->paragraph(4),
                'author_id'     => 1,
                'publisher_id'  => 1,
                'cover'         => $faker->imageUrl(300, 450, 'books', true, 'Book'),
                'file'          => 'books/sample.pdf',
                'file_type'     => 'pdf',
                'download_count'=> $faker->numberBetween(0, 500),
                'read_count'    => $faker->numberBetween(0, 1000),
                'language_id'   => 1, 
                'pages_number'  => $faker->numberBetween(100, 800),
                'slug'          => Str::slug($title) . '-' . Str::random(5),
                'status'        => $faker->randomElement([
                    Book::STATUS_PENDING,
                    Book::STATUS_ACCEPTED,
                    Book::STATUS_REJECTED,
                    Book::STATUS_ARCHIVED,
                ]),
            ]);
        }
    }
}
