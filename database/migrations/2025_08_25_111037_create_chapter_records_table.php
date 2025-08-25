<?php

use App\Models\BookChapter;
use App\Models\Language;
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
        Schema::create('chapter_records', function (Blueprint $table) {
    $table->id();
    $table->foreignIdFor(BookChapter::class)->cascadeOnDelete();
    $table->foreignIdFor(User::class)->cascadeOnDelete();
    $table->string('record_file')->nullable();
    $table->string('duration')->nullable();
    $table->foreignIdFor(Language::class)->cascadeOnDelete();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapter_records');
    }
};
