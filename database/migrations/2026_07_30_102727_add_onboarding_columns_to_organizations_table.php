<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            // Only add missing columns
            if (!Schema::hasColumn('organizations', 'currency')) {
                $table->string('currency')->default('NPR')->after('address');
            }
            if (!Schema::hasColumn('organizations', 'language')) {
                $table->string('language')->default('en')->after('currency');
            }
        });
    }

    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['currency', 'language']);
        });
    }
};