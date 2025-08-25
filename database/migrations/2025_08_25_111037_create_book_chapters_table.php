<?php

use App\Models\Book;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book_chapters', function (Blueprint $table) {
    $table->id();
    $table->foreignIdFor(Book::class)->cascadeOnDelete();
    $table->string(column: 'title');
    //$table->string(column: 'slug');
    $table->string('meta_title')->nullable();
    $table->text('description')->nullable();
    $table->integer('pages_count')->nullable();
    $table->longText('chapter_text')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_chapters');
    }
};
