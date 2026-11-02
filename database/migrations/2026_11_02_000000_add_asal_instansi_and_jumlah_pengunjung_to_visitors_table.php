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
    Schema::table('visitors', function (Blueprint $table) { // <-- Pastikan namanya 'visitors'
        $table->enum('asal_instansi', ['Instansi Pemerintah', 'Swasta', 'Masyarakat Umum'])->default('Masyarakat Umum')->after('alamat');
        $table->integer('jumlah_pengunjung')->default(1)->after('asal_instansi');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn(['asal_instansi', 'jumlah_pengunjung']);
        });
    }
};
