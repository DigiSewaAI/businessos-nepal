<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique(); // e.g., 1010, 2010
            $table->string('name');
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->foreignId('parent_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->decimal('opening_balance', 15, 2)->default(0); // for initial balance
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organization_id', 'code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};