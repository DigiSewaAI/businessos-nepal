<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'plan_id')) {
                $table->foreignId('plan_id')->nullable()->default(1)->after('status')->constrained('plans')->onDelete('set null');
            }
            if (!Schema::hasColumn('organizations', 'currency')) {
                $table->string('currency')->default('NPR')->after('plan_id');
            }
        });
    }

    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['plan_id', 'currency']);
        });
    }
};