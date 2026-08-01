<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // 1. user_id
            if (!Schema::hasColumn('audit_logs', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }

            // 2. organization_id
            if (!Schema::hasColumn('audit_logs', 'organization_id')) {
                $table->unsignedBigInteger('organization_id')->nullable();
            }

            // 3. action
            if (!Schema::hasColumn('audit_logs', 'action')) {
                $table->string('action')->nullable();
            }

            // 4. model_type (polymorphic)
            if (!Schema::hasColumn('audit_logs', 'model_type')) {
                $table->string('model_type')->nullable();
            }

            // 5. model_id
            if (!Schema::hasColumn('audit_logs', 'model_id')) {
                $table->unsignedBigInteger('model_id')->nullable();
            }

            // 6. changes (JSON)
            if (!Schema::hasColumn('audit_logs', 'changes')) {
                $table->json('changes')->nullable();
            }

            // 7. ip_address
            if (!Schema::hasColumn('audit_logs', 'ip_address')) {
                $table->string('ip_address', 45)->nullable();
            }

            // 8. user_agent
            if (!Schema::hasColumn('audit_logs', 'user_agent')) {
                $table->text('user_agent')->nullable();
            }

            // Foreign keys (if they don't exist)
            // user_id foreign
            if (!Schema::hasColumn('audit_logs', 'user_id')) {
                // already added, but we can add foreign later
            }
            // we can add foreign keys after ensuring columns exist
            // But we need to add them only if not already added
            // We'll use raw SQL to check, or just add them conditionally
        });

        // Add foreign keys separately after columns are created
        // We'll use a second Schema::table to ensure they are added.
        Schema::table('audit_logs', function (Blueprint $table) {
            // user_id foreign
            if (Schema::hasColumn('audit_logs', 'user_id')) {
                try {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                } catch (\Exception $e) {
                    // foreign already exists or other error
                }
            }
            if (Schema::hasColumn('audit_logs', 'organization_id')) {
                try {
                    $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
                } catch (\Exception $e) {
                    // already exists
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['organization_id']);
            $table->dropColumn([
                'user_id',
                'organization_id',
                'action',
                'model_type',
                'model_id',
                'changes',
                'ip_address',
                'user_agent'
            ]);
        });
    }
};