<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shipping_costs', function (Blueprint $table) {
            $table->id();
            $table->string('courier'); // jne, tiki, pos, sicepat
            $table->string('courier_name'); // JNE, TIKI, POS, SiCepat
            $table->foreignId('destination_id'); // ID desa/kecamatan
            $table->decimal('cost', 15, 2);
            $table->string('service')->nullable(); // REG, OKE, YES
            $table->integer('estimated_days')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipping_costs');
    }
};