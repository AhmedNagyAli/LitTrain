<?php

use App\Models\Author;
use App\Models\Language;
use App\Models\Publisher;
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
        Schema::create('books', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('meta_title')->nullable();
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->foreignIdFor(Author::class)->cascadeOnDelete();
    $table->foreignIdFor(Publisher::class)->cascadeOnDelete();
    $table->string('cover')->nullable();
    $table->string('file')->nullable();
    $table->string('file_type')->nullable();
    $table->integer('download_count')->default(0);
    $table->integer('read_count')->default(0);
    $table->foreignIdFor(Language::class)->cascadeOnDelete();
    $table->integer('pages_number')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
