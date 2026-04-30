<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dine_in_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name')->nullable();
            $table->integer('table_number');
            $table->json('items');
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending');
            $table->string('payment_status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('order_number');
            $table->index('status');
            $table->index('table_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dine_in_orders');
    }
};