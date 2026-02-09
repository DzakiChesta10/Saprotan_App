<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Tambahkan kolom yang hilang
            $table->integer('masuk')->default(0)->after('uom');
            $table->integer('keluar')->default(0)->after('masuk');
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['masuk', 'keluar']);
        });
    }
};
