<?php

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
        Schema::create('publishing_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignIdFor(User::class)->cascadeOnDelete();
    $table->text('description');
    $table->string('image')->nullable();
    $table->boolean('is_accepted')->default(false);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publishing_requests');
    }
};
