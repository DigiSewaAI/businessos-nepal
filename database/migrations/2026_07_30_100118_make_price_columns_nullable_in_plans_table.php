<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('price_monthly')->nullable()->change();
            $table->integer('price_yearly')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('price_monthly')->nullable(false)->change();
            $table->integer('price_yearly')->nullable(false)->change();
        });
    }
};