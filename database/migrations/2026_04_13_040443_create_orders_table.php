<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Cek apakah tabel sudah ada
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();
                $table->string('customer_name');
                $table->string('customer_email')->nullable();
                $table->string('customer_phone');
                $table->text('customer_address');
                $table->json('items');
                $table->decimal('total_amount', 10, 2);
                $table->string('payment_method')->nullable();
                $table->string('payment_status')->default('pending');
                $table->string('status')->default('pending');
                $table->string('delivery_status')->default('pending');
                $table->timestamp('confirmed_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                
                $table->index('order_number');
                $table->index('status');
                $table->index('payment_status');
                $table->index('created_at');
            });
        } else {
            // Jika tabel sudah ada, tambahkan kolom yang mungkin hilang
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'delivery_status')) {
                    $table->string('delivery_status')->default('pending');
                }
                if (!Schema::hasColumn('orders', 'confirmed_at')) {
                    $table->timestamp('confirmed_at')->nullable();
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};