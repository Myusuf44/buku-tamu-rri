<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Carbon\Carbon;

class GuestController extends Controller
{
    /**
     * Menampilkan Halaman Utama Publik beserta Ulasan, Rating, dan Statistik Rekapitulasi
     */
    public function index()
    {
        // Mengambil maksimal 50 data ulasan terbaru agar performa beranda tetap ringan dan gesit
        $publicFeedbacks = Feedback::latest()->take(50)->get()->map(function ($fb) {
            // 1. Logika Penyensoran Nama untuk Keamanan Privasi Publik
            $namaLengkap = $fb->nama_pengunjung ?? '';
            $words = explode(' ', trim($namaLengkap));
            $censored = array_map(function($w) {
                $len = mb_strlen($w);
                if ($len <= 2) return $w;
                return mb_substr($w, 0, 3) . str_repeat('*', max(1, $len - 3));
            }, $words);
            
            $fb->nama_tampil = implode(' ', $censored);
            
            // 2. Inisial huruf pertama untuk Avatar
            $fb->inisial = strtoupper(mb_substr($namaLengkap, 0, 1));

            // 3. Format angka rating
            $fb->rating_format = number_format($fb->rating ?? 0, 1);

            // 4. Format waktu relatif (misal: beberapa menit lalu)
            $fb->waktu_lalu = Carbon::parse($fb->created_at)->diffForHumans();

            return $fb;
        });

        // Menghitung rata-rata rating keseluruhan dari seluruh data
        $avgRating = Feedback::avg('rating') ?? 0;
        $avgRatingFormat = number_format($avgRating, 1);
        
        // Menghitung total seluruh responden
        $totalResponden = Feedback::count();

        // --- PERHITUNGAN DINAMIS DISTRIBUSI BINTANG (PERSENTASE) ---
        $total = $totalResponden > 0 ? $totalResponden : 1; // Mencegah division by zero
        
        $allFeedbacks = Feedback::all();
        
        $count5 = $allFeedbacks->where('rating', 5)->count();
        $count4 = $allFeedbacks->where('rating', 4)->count();
        $count3 = $allFeedbacks->where('rating', 3)->count();
        $count2 = $allFeedbacks->where('rating', 2)->count();
        $count1 = $allFeedbacks->where('rating', 1)->count();

        $percent5 = round(($count5 / $total) * 100);
        $percent4 = round(($count4 / $total) * 100);
        $percent3 = round(($count3 / $total) * 100);
        $percent2 = round(($count2 / $total) * 100);
        $percent1 = round(($count1 / $total) * 100);

        return view('home', compact(
            'publicFeedbacks', 
            'avgRating', 
            'avgRatingFormat', 
            'totalResponden',
            'percent5', 
            'percent4', 
            'percent3', 
            'percent2', 
            'percent1'
        ));
    }

    /**
     * Menampilkan Halaman Formulir Pendaftaran / Buku Tamu
     */
    public function registrasi()
    {
        return view('registrasi');
    }
}