<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        if (!Schema::hasColumn('orders', 'user_id')) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('set null');
        }
        if (!Schema::hasColumn('orders', 'payment_detail')) {
            $table->string('payment_detail')->nullable()->after('payment_method');
        }
        if (!Schema::hasColumn('orders', 'delivery_status')) {
            $table->enum('delivery_status', ['pending', 'shipped', 'delivered'])->default('pending')->after('status');
        }
        if (!Schema::hasColumn('orders', 'confirmed_at')) {
            $table->timestamp('confirmed_at')->nullable()->after('delivery_status');
        }
        if (!Schema::hasColumn('orders', 'is_viewed')) {
            $table->boolean('is_viewed')->default(false)->after('confirmed_at');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
