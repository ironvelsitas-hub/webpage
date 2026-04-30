<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'discount_id')) {
                $table->foreignId('discount_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'discount_code')) {
                $table->string('discount_code')->nullable();
            }
            if (!Schema::hasColumn('orders', 'discount_amount')) {
                $table->decimal('discount_amount', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 15, 2)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['discount_id']);
            $table->dropColumn(['discount_id', 'discount_code', 'discount_amount', 'subtotal']);
        });
    }
};