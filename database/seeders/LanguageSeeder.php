<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['name' => 'English', 'code' => 'en'],
            ['name' => 'Arabic', 'code' => 'ar'],
            ['name' => 'French', 'code' => 'fr'],
            ['name' => 'Spanish', 'code' => 'es'],
            ['name' => 'German', 'code' => 'de'],
            ['name' => 'Italian', 'code' => 'it'],
            ['name' => 'Portuguese', 'code' => 'pt'],
            ['name' => 'Russian', 'code' => 'ru'],
            ['name' => 'Chinese (Simplified)', 'code' => 'zh'],
            ['name' => 'Japanese', 'code' => 'ja'],
            ['name' => 'Korean', 'code' => 'ko'],
            ['name' => 'Hindi', 'code' => 'hi'],
            ['name' => 'Turkish', 'code' => 'tr'],
            ['name' => 'Dutch', 'code' => 'nl'],
            ['name' => 'Greek', 'code' => 'el'],
            ['name' => 'Swedish', 'code' => 'sv'],
            ['name' => 'Norwegian', 'code' => 'no'],
            ['name' => 'Danish', 'code' => 'da'],
            ['name' => 'Polish', 'code' => 'pl'],
            ['name' => 'Urdu', 'code' => 'ur'],
        ];

        DB::table('languages')->insert($languages);
    }
}
