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
        Schema::create('product_growth_logs', function (Blueprint $table) {
            $table->id();

            // Relationship
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade');

            // Growth monitoring data
            $table->string('growth_stage'); // Seedling, Vegetative, Mature
            $table->decimal('height_cm', 5, 2)->nullable();
            $table->text('notes')->nullable();

            // Who recorded it (seller)
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
        Schema::dropIfExists('product_growth_logs');
    }
};
