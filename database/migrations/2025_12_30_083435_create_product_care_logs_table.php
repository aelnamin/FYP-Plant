<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_care_logs', function (Blueprint $table) {
            $table->id();

            // Relationship
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade');

            // Care activity
            $table->enum('care_type', [
                'watering',
                'fertilizing',
                'pruning',
                'repotting',
                'pest_control'
            ]);

            $table->text('description')->nullable();

            // Who performed care (seller)
            $table->foreignId('seller_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_care_logs');
    }
};
