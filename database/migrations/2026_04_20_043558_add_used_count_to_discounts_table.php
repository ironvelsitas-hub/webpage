<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            if (!Schema::hasColumn('discounts', 'used_count')) {
                $table->integer('used_count')->default(0)->after('usage_limit');
            }
        });
    }

    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            if (Schema::hasColumn('discounts', 'used_count')) {
                $table->dropColumn('used_count');
            }
        });
    }
};