<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ad_id')
                ->constrained('ads')
                ->cascadeOnDelete();

            $table->foreignId('sender_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('receiver_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('content');

            $table->timestamps();

            $table->index('ad_id');
            $table->index('sender_id');
            $table->index('receiver_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};