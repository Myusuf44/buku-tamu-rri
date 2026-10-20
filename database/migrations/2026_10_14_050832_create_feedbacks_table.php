<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->nullable()->constrained('visitors')->onDelete('cascade');
            $table->string('nama_pengunjung');
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->unsignedTinyInteger('rating'); // 1 sampai 5 bintang
            $table->text('ulasan');
            $table->enum('sumber', ['beranda', 'whatsapp', 'email'])->default('beranda');
            $table->enum('status_pengisian', ['terkirim_diisi', 'terkirim_belum_diisi', 'langsung_beranda'])->default('langsung_beranda');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
