<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Check & add organization_id (if missing)
            if (!Schema::hasColumn('payments', 'organization_id')) {
                $table->unsignedBigInteger('organization_id')->nullable();
                $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            }

            // Check & add subscription_id
            if (!Schema::hasColumn('payments', 'subscription_id')) {
                $table->unsignedBigInteger('subscription_id')->nullable()->after('organization_id');
                $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('set null');
            }

            // Check & add amount
            if (!Schema::hasColumn('payments', 'amount')) {
                $table->decimal('amount', 15, 2)->nullable()->after('subscription_id');
            }

            // Check & add payment_method
            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('amount');
            }

            // Check & add transaction_id
            if (!Schema::hasColumn('payments', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('payment_method');
            }

            // Check & add status
            if (!Schema::hasColumn('payments', 'status')) {
                $table->string('status')->default('pending')->after('transaction_id');
            }

            // Check & add payment_date
            if (!Schema::hasColumn('payments', 'payment_date')) {
                $table->timestamp('payment_date')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropForeign(['subscription_id']);
            $table->dropColumn([
                'organization_id',
                'subscription_id',
                'amount',
                'payment_method',
                'transaction_id',
                'status',
                'payment_date'
            ]);
        });
    }
};