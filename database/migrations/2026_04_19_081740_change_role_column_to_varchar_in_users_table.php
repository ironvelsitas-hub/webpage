<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Ubah kolom role dari ENUM menjadi VARCHAR(50)
        Schema::table('users', function (Blueprint $table) {
            // Hapus dulu kolom role (karena ENUM tidak bisa langsung diubah ke VARCHAR)
            // Atau gunakan query raw
            DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'customer'");
        });
    }

    public function down()
    {
        // Kembalikan ke ENUM (opsional)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'admin') NOT NULL DEFAULT 'customer'");
    }
};