<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id(); // seller id
            $table->unsignedBigInteger('user_id');
            $table->string('business_name')->nullable();
            $table->string('business_address')->nullable();
            $table->enum('verification_status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};