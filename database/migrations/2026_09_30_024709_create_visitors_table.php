<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->text('alamat');
            $table->string('no_hp');
            $table->string('email')->nullable();
            $table->text('keperluan');
            $table->string('divisi_tujuan');
            $table->date('tanggal_kunjungan');
            $table->time('jam_kunjungan');
            $table->enum('temu_janji', ['Sudah Janji', 'Belum Janji'])->default('Belum Janji');
            
            // Opsi status diperbarui: Diproses, Diterima, Selesai, Dibatalkan
            $table->enum('status', ['Diproses', 'Diterima', 'Selesai', 'Dibatalkan'])->default('Diproses');
            
            // Tambahan kolom alasan pembatalan agar tidak error
            $table->text('alasan_batal')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};