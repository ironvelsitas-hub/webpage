<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasColumn('reports', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('status');
            }
            if (!Schema::hasColumn('reports', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('is_read');
            }
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['is_read', 'read_at']);
        });
    }
};