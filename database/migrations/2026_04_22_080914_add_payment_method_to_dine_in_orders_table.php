<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dine_in_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('dine_in_orders', 'payment_method')) {
                $table->enum('payment_method', ['cash', 'qris', 'virtual_account', 'ewallet'])->default('cash');
            }
            if (!Schema::hasColumn('dine_in_orders', 'subtotal')) {
                $table->decimal('subtotal', 15, 2)->nullable();
            }
            if (!Schema::hasColumn('dine_in_orders', 'tax')) {
                $table->decimal('tax', 15, 2)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('dine_in_orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'subtotal', 'tax']);
        });
    }
};