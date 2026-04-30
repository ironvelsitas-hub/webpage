<?php
// database/migrations/2026_04_17_000001_create_branches_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama cabang
            $table->string('slug')->unique();
            $table->text('address'); // Alamat lengkap
            $table->string('phone'); // Nomor telepon cabang
            $table->string('email')->nullable();
            $table->string('latitude')->nullable(); // Koordinat latitude untuk Google Maps
            $table->string('longitude')->nullable(); // Koordinat longitude
            $table->string('image')->nullable(); // Foto cabang
            $table->text('description')->nullable(); // Deskripsi cabang
            $table->string('open_time')->default('08:00'); // Jam buka
            $table->string('close_time')->default('23:00'); // Jam tutup
            $table->boolean('is_active')->default(true);
            $table->integer('order_position')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('branches');
    }
};