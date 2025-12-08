<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id('complaint_id');

            // Who submitted the complaint
            $table->unsignedBigInteger('user_id');

            // Complaint about a seller (nullable)
            $table->unsignedBigInteger('seller_id')->nullable();

            // Complaint linked to an order (optional)
            $table->unsignedBigInteger('order_id')->nullable();

            // Admin who resolved it
            $table->unsignedBigInteger('handled_by')->nullable();

            // Complaint details
            $table->text('complaint_message');
            $table->text('admin_response')->nullable();

            // Status
            $table->string('status', 20)->default('Pending');

            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // sellers table uses "id"
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('set null');

            // orders table uses "order_id"
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');


            // admins table — check your PK name:
            // If your admins table uses admin_id, use that.
            $table->foreign('handled_by')->references('id')->on('admins')->onDelete('set null');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};