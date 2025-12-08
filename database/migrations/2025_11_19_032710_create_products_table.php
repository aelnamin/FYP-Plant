<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // product id
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('category_id');
            
            $table->string('product_name', 100);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(0);

            // plant info
            $table->string('sunlight_requirement')->nullable();
            $table->string('watering_frequency')->nullable();
            $table->string('difficulty_level')->nullable();
            $table->string('growth_stage')->nullable();

            // admin verification
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('approval_status', 20)->default('Pending');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};