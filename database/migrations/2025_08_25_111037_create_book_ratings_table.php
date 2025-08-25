<?php

use App\Models\Book;
use App\Models\User;
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
        Schema::create('book_ratings', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignIdFor(User::class)->cascadeOnDelete();
            $table->foreignIdFor(Book::class)->cascadeOnDelete();
            // Rating (can be integer 1–5, or float if half-stars allowed)
            $table->unsignedTinyInteger('rating')->comment('1-5 rating');
            // Optional review
            $table->text('review')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_ratings');
    }
};
