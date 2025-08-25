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
    // $table->foreignIdFor(Author::class)->cascadeOnDelete();
    // $table->foreignIdFor(Publisher::class)->cascadeOnDelete();
     public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('meta_title')->nullable();
            $table->text('description')->nullable();

            $table->foreignIdFor(Author::class)->cascadeOnDelete();
            $table->foreignIdFor(Publisher::class)->cascadeOnDelete();
            $table->foreignIdFor(Language::class)->cascadeOnDelete();

            $table->string('cover')->nullable();       // image path
            $table->string('file')->nullable();        // book file path
            $table->string('file_type', 50)->nullable();

            $table->unsignedBigInteger('download_count')->default(0);
            $table->unsignedBigInteger('read_count')->default(0);

            $table->integer('pages_number')->nullable();
            $table->string('slug')->unique();
            $table->tinyInteger('status')->default(0);
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
