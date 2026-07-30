<?php

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
        Schema::create('ai_faqs', function (Blueprint $table) {
    $table->id();
    $table->string('category')->default('general');
    $table->string('question');
    $table->text('answer');
    $table->json('keywords')->nullable(); // search optimization
    $table->integer('priority')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_faqs');
    }
};
