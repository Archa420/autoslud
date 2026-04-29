<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ad_id')
                ->unique()
                ->constrained('ads')
                ->cascadeOnDelete();

            $table->decimal('starting_bid', 10, 2);
            $table->decimal('current_bid', 10, 2)->nullable();
            $table->decimal('minimum_bid_step', 10, 2)->default(1.00);

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at');

            $table->enum('status', ['active', 'finished', 'cancelled'])->default('active');

            $table->foreignId('winner_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('status');
            $table->index('ends_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};