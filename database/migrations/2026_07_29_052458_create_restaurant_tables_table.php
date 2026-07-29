<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('number'); // Table 1, Table 2, A1, etc.
            $table->integer('capacity')->default(2);
            $table->enum('status', ['available', 'occupied', 'reserved', 'unavailable'])->default('available');
            $table->string('qr_code')->nullable(); // QR code image path or token
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['organization_id', 'branch_id', 'number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurant_tables');
    }
};