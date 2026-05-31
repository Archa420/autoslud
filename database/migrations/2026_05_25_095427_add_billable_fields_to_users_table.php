<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'stripe_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('stripe_id')->nullable()->index()->after('remember_token');
            });
        }

        if (! Schema::hasColumn('users', 'pm_type')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('pm_type')->nullable()->after('stripe_id');
            });
        }

        if (! Schema::hasColumn('users', 'pm_last_four')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('pm_last_four', 4)->nullable()->after('pm_type');
            });
        }

        if (! Schema::hasColumn('users', 'trial_ends_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('trial_ends_at')->nullable()->after('pm_last_four');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'stripe_id')) {
                $table->dropIndex(['stripe_id']);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $columns = [];

            foreach (['stripe_id', 'pm_type', 'pm_last_four', 'trial_ends_at'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $columns[] = $column;
                }
            }

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};