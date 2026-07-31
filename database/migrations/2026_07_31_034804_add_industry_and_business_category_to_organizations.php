<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            // ✅ Add new columns with safe defaults
            if (!Schema::hasColumn('organizations', 'industry')) {
                $table->string('industry')->default('retail')->after('status');
            }
            if (!Schema::hasColumn('organizations', 'business_category')) {
                $table->string('business_category')->nullable()->after('industry');
            }
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['industry', 'business_category']);
        });
    }
};