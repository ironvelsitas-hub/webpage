<?php
// database/migrations/2026_04_18_000001_create_discounts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kode diskon (contoh: WELCOME10)
            $table->string('name'); // Nama diskon (contoh: Diskon Welcome 10%)
            $table->enum('type', ['percentage', 'fixed']); // percentage = persen, fixed = nominal tetap
            $table->decimal('value', 10, 2); // Nilai diskon (10 = 10%, atau 10000 = Rp10.000)
            $table->decimal('min_purchase', 10, 2)->default(0); // Minimal belanja
            $table->decimal('max_discount', 10, 2)->nullable(); // Maksimal diskon (untuk persen)
            $table->integer('usage_limit')->nullable(); // Batas penggunaan
            $table->integer('used_count')->default(0); // Jumlah sudah digunakan
            $table->integer('per_user_limit')->default(1); // Batas per user
            $table->dateTime('start_date'); // Tanggal mulai
            $table->dateTime('end_date'); // Tanggal berakhir
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discounts');
    }
};