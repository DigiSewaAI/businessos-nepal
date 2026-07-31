<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            // Add missing columns
            $table->integer('trial_days')->default(0)->after('price_yearly');
            $table->integer('max_products')->nullable()->after('trial_days');
            $table->integer('max_branches')->nullable()->after('max_products');
            $table->integer('max_users')->nullable()->after('max_branches');
            $table->integer('max_invoices_monthly')->nullable()->after('max_users');
            $table->integer('max_storage_mb')->nullable()->after('max_invoices_monthly');
            $table->integer('max_ai_requests')->nullable()->after('max_storage_mb');
            $table->boolean('has_purchase')->default(false)->after('max_ai_requests');
            $table->boolean('has_finance')->default(false)->after('has_purchase');
            $table->boolean('has_api')->default(false)->after('has_finance');
            $table->boolean('has_white_label')->default(false)->after('has_api');
            $table->string('backup_frequency')->default('weekly')->after('has_white_label');
            $table->boolean('is_popular')->default(false)->after('is_active');
            $table->json('perfect_for')->nullable()->after('is_popular');
        });
    }

    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'trial_days', 'max_products', 'max_branches', 'max_users',
                'max_invoices_monthly', 'max_storage_mb', 'max_ai_requests',
                'has_purchase', 'has_finance', 'has_api', 'has_white_label',
                'backup_frequency', 'is_popular', 'perfect_for'
            ]);
        });
    }
};