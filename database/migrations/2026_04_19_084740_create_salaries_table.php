<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('month'); // Bulan (1-12)
            $table->integer('year'); // Tahun
            $table->decimal('base_salary', 15, 2); // Gaji pokok
            $table->decimal('allowance', 15, 2)->default(0); // Tunjangan
            $table->decimal('bonus', 15, 2)->default(0); // Bonus
            $table->decimal('deduction', 15, 2)->default(0); // Potongan
            $table->decimal('total_salary', 15, 2); // Total gaji
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->date('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Unique constraint untuk mencegah duplikasi per bulan
            $table->unique(['user_id', 'month', 'year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('salaries');
    }
};