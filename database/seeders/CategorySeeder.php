<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Fiction',
            'Non-Fiction',
            'Science Fiction',
            'Fantasy',
            'Mystery',
            'Thriller',
            'Romance',
            'Historical Fiction',
            'Young Adult',
            'Children’s Books',
            'Biography',
            'Autobiography',
            'Self-Help',
            'Health & Wellness',
            'Cookbooks',
            'Art & Photography',
            'Poetry',
            'Comics & Graphic Novels',
            'Horror',
            'Religion & Spirituality',
            'Philosophy',
            'Psychology',
            'Education',
            'Science',
            'Technology',
            'Business & Economics',
            'Politics',
            'Travel',
            'True Crime',
            'Sports & Outdoors',
            'Parenting & Family',
            'Crafts & Hobbies',
            'Music',
            'Drama',
            'Law',
            'Medical',
            'Engineering',
            'Computer & IT',
            'History',
            'Language Learning',
            'Classic Literature',
            'Adventure',
            'Humor',
            'Essays',
            'War & Military',
            'Cultural Studies',
            'Environmental Studies',
            'Mythology',
            'Anthology',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate([
                'slug' => Str::slug($category),
            ], [
                'category' => $category,
            ]);
        }
    }
}
