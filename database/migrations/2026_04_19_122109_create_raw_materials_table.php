<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama bahan baku
            $table->string('category')->nullable(); // Kategori (Kopi, Susu, Gula, dll)
            $table->string('unit')->default('kg'); // Satuan (kg, liter, pcs)
            $table->integer('stock')->default(0); // Stok saat ini
            $table->integer('min_stock')->default(10); // Stok minimal
            $table->decimal('unit_price', 15, 2)->default(0); // Harga per unit
            $table->string('supplier')->nullable(); // Supplier
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('raw_materials');
    }
};