<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['sales', 'financial', 'product', 'employee', 'inventory']);
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('total_revenue', 15, 2)->default(0);
            $table->decimal('total_expense', 15, 2)->default(0);
            $table->decimal('net_profit', 15, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->json('data')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->enum('status', ['draft', 'submitted', 'approved'])->default('draft');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};