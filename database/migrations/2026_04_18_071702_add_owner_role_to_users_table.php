<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Ubah kolom role untuk menerima value 'owner'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'admin', 'owner') DEFAULT 'customer'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'admin') DEFAULT 'customer'");
    }
};