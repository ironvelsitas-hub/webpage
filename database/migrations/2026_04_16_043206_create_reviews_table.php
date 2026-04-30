<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Hapus tabel jika sudah ada
        Schema::dropIfExists('reviews');
        
        // Buat tabel baru
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('rating')->default(5);
            $table->text('comment');
            $table->json('images')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_verified_purchase')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
            
            $table->index(['product_id', 'status']);
            $table->index('user_id');
            $table->index('order_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};