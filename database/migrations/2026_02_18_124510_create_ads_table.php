<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->string('brand', 60)->nullable();
            $table->string('model', 80)->nullable();
            $table->unsignedSmallInteger('year')->nullable();

            $table->unsignedSmallInteger('engine_cc')->nullable();
            $table->unsignedInteger('mileage_km')->nullable();

            $table->string('color', 40)->nullable();
            $table->string('gearbox_type', 30)->nullable();
            $table->unsignedTinyInteger('gears_count')->nullable();
            $table->string('fuel_type', 30)->nullable();
            $table->unsignedTinyInteger('doors_count')->nullable();

            $table->string('body_type', 50)->nullable();
            $table->string('location', 255)->nullable();
            $table->string('contacts', 255)->nullable();

            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();

            $table->enum('status', ['active', 'sold', 'inactive'])->default('active');

            $table->timestamps();

            $table->index(['brand', 'model']);
            $table->index('price');
            $table->index('year');
            $table->index('location');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};