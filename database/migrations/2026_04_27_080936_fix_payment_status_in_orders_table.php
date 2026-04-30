<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Perbaiki tabel orders
        Schema::table('orders', function (Blueprint $table) {
            // Ubah payment_status dari ENUM menjadi VARCHAR
            $table->string('payment_status', 50)->default('pending')->change();
            
            // Ubah status dari ENUM menjadi VARCHAR
            $table->string('status', 50)->default('pending')->change();
            
            // Tambah kolom delivery_status jika belum ada
            if (!Schema::hasColumn('orders', 'delivery_status')) {
                $table->string('delivery_status', 50)->default('pending');
            }
            
            // Tambah kolom confirmed_at jika belum ada
            if (!Schema::hasColumn('orders', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kembalikan ke ENUM (tidak disarankan)
            $table->dropColumn(['delivery_status', 'confirmed_at']);
        });
    }
};