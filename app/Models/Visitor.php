<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Visitor extends Model
{
    use HasFactory;

    /**
     * Field yang dapat diisi secara massal (Mass Assignment)
     */
    protected $fillable = [
        'kode_registrasi',
        'nama_lengkap',
        'alamat',
        'no_hp',
        'email',
        'keperluan',
        'divisi_tujuan',
        'tanggal_kunjungan',
        'jam_kunjungan', 
        'temu_janji',    
        'status',
        'alasan_batal', // Ditambahkan agar aman saat proses pembatalan kunjungan
    ];

    /**
     * Casting tipe data kolom
     */
    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    /**
     * Event model untuk membuat kode registrasi otomatis (jika kolom kode_registrasi belum diisi)
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($visitor) {
            if (empty($visitor->kode_registrasi)) {
                $visitor->kode_registrasi = 'REG-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            }
        });
    }
}