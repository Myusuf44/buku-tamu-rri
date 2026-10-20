<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        return $this->dashboard($request);
    }

    public function dashboard(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        $totalKunjungan = Visitor::count();
        $totalDiterima = Visitor::whereIn('status', ['Diterima', 'Selesai'])->count();
        $bulanIni = Visitor::whereMonth('tanggal_kunjungan', $thisMonth)
                            ->whereYear('tanggal_kunjungan', $thisYear)
                            ->count();
        $menunggu = Visitor::where('status', 'Diproses')->count();

        $tamuHariIni = Visitor::whereDate('tanggal_kunjungan', $today)->count();
        
        $selesaiHariIni = Visitor::where('status', 'Selesai')
                               ->where(function($q) use ($today) {
                                   $q->whereDate('updated_at', $today)
                                     ->orWhereDate('tanggal_kunjungan', $today);
                               })
                               ->count();

        $jadwalMendatang = Visitor::where('status', 'Diterima')
                                ->whereDate('tanggal_kunjungan', '>', $today)
                                ->count();

        $dibatalkanHariIni = Visitor::where('status', 'Dibatalkan')
                                    ->where(function($q) use ($today) {
                                        $q->whereDate('updated_at', $today)
                                          ->orWhereDate('tanggal_kunjungan', $today);
                                    })
                                    ->count();

        $rataRating = Feedback::avg('rating') ?? 0;
        $totalResponden = Feedback::count();
        $feedbacks = Feedback::latest()->paginate(15);

        $query = Visitor::query();

        if ($request->filled('search') && trim($request->search) !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('keperluan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tanggal_exact') && trim($request->tanggal_exact) !== '') {
            $query->whereDate('tanggal_kunjungan', $request->tanggal_exact);
        }

        if ($request->filled('tanggal') && trim($request->tanggal) !== '') {
            $query->whereDate('tanggal_kunjungan', $request->tanggal);
        }

        if ($request->filled('bulan') && trim($request->bulan) !== '') {
            $query->whereMonth('tanggal_kunjungan', $request->bulan);
        }

        if ($request->filled('tahun') && trim($request->tahun) !== '') {
            $query->whereYear('tanggal_kunjungan', $request->tahun);
        }

        if ($request->filled('divisi') && trim($request->divisi) !== '') {
            $query->where('divisi_tujuan', $request->divisi);
        }

        if ($request->filled('status') && trim($request->status) !== '') {
            $query->where('status', $request->status);
        }

        $visitors = $query->orderByRaw("CASE WHEN tanggal_kunjungan >= ? THEN 0 ELSE 1 END ASC", [$today])
                          ->orderBy('tanggal_kunjungan', 'asc')
                          ->orderBy('jam_kunjungan', 'asc')
                          ->paginate(20);

        $user = auth()->user();
        $isSuperAdmin = $user ? $user->isSuperAdmin() : false;

        return view('admin.dashboard', compact(
            'visitors', 'totalKunjungan', 'totalDiterima', 'bulanIni', 'menunggu',
            'tamuHariIni', 'selesaiHariIni', 'jadwalMendatang', 'dibatalkanHariIni',
            'rataRating', 'totalResponden', 'feedbacks', 'isSuperAdmin'
        ));
    }

    public function manageFeedbacks(Request $request)
    {
        $query = Feedback::query();

        if ($request->filled('search') && trim($request->search) !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pengunjung', 'like', "%{$search}%")
                  ->orWhere('ulasan', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tanggal_exact') && trim($request->tanggal_exact) !== '') {
            $query->whereDate('created_at', $request->tanggal_exact);
        }

        if ($request->filled('bulan') && trim($request->bulan) !== '') {
            $query->whereMonth('created_at', $request->bulan);
        }

        if ($request->filled('sumber') && trim($request->sumber) !== '') {
            $query->where('sumber', $request->sumber);
        }

        if ($request->filled('rating') && trim($request->rating) !== '') {
            $query->where('rating', $request->rating);
        }

        $feedbacks = $query->latest()->paginate(15);

        $rataRating = Feedback::avg('rating') ?? 0;
        $totalResponden = Feedback::count();
        $totalBeranda = Feedback::where('sumber', 'beranda')->count();
        $totalPuas = Feedback::where('rating', '>=', 4)->count();

        return view('admin.feedbacks', compact(
            'feedbacks', 'rataRating', 'totalResponden', 'totalBeranda', 'totalPuas'
        ));
    }

    public function exportFeedbackPdf(Request $request)
    {
        $query = Feedback::query();

        if ($request->filled('sumber') && trim($request->sumber) !== '') {
            $query->where('sumber', $request->sumber);
        }

        if ($request->filled('tanggal') && trim($request->tanggal) !== '') {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('bulan') && trim($request->bulan) !== '') {
            $query->whereMonth('created_at', $request->bulan);
        }

        if ($request->filled('tahun') && trim($request->tahun) !== '') {
            $query->whereYear('created_at', $request->tahun);
        }

        $feedbacks = $query->latest()->get();

        if ($feedbacks->isEmpty()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Data ulasan tidak ditemukan'], 404);
            }
            return redirect()->back()->with('error', 'Data ulasan tidak ditemukan');
        }

        // Konversi waktu setiap feedback ke WIB secara presisi
        $feedbacks->each(function($fb) {
            $fb->waktu_wib = $fb->created_at ? $fb->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') . ' WIB' : '-';
        });

        $summary = [
            'total'      => $feedbacks->count(),
            'rataRating'  => $feedbacks->avg('rating') ?? 0,
            'bintang5'    => $feedbacks->where('rating', 5)->count(),
            'evaluasi'    => $feedbacks->where('rating', '<=', 3)->count(),
            'beranda'     => $feedbacks->where('sumber', 'beranda')->count(),
        ];

        $judulPeriode = 'Semua Periode';
        if ($request->filled('tanggal') && trim($request->tanggal) !== '') {
            $judulPeriode = Carbon::parse($request->tanggal)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY');
        } elseif ($request->filled('bulan') && trim($request->bulan) !== '') {
            $judulPeriode = 'Bulan ' . Carbon::create()->month($request->bulan)->locale('id')->isoFormat('MMMM');
            if ($request->filled('tahun') && trim($request->tahun) !== '') $judulPeriode .= ' ' . $request->tahun;
        } elseif ($request->filled('tahun') && trim($request->tahun) !== '') {
            $judulPeriode = 'Tahun ' . $request->tahun;
        }

        $waktuCetak = Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') . ' WIB';
        $adminPencetak = auth()->user();

        $pdf = Pdf::loadView('admin.export-feedback-pdf', compact(
            'feedbacks', 'summary', 'judulPeriode', 'waktuCetak', 'adminPencetak'
        ));

        return $pdf->stream('laporan-ulasan-pelayanan.pdf');
    }

    public function exportFeedbackExcel(Request $request)
    {
        $query = Feedback::query();

        if ($request->filled('sumber') && trim($request->sumber) !== '') {
            $query->where('sumber', $request->sumber);
        }

        if ($request->filled('tanggal') && trim($request->tanggal) !== '') {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('bulan') && trim($request->bulan) !== '') {
            $query->whereMonth('created_at', $request->bulan);
        }

        if ($request->filled('tahun') && trim($request->tahun) !== '') {
            $query->whereYear('created_at', $request->tahun);
        }

        $feedbacks = $query->latest()->get();

        if ($feedbacks->isEmpty()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Data ulasan tidak ditemukan'], 404);
            }
            return redirect()->back()->with('error', 'Data ulasan tidak ditemukan');
        }

        $filename = "laporan-ulasan-" . Carbon::now('Asia/Jakarta')->format('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, ['NO', 'WAKTU KIRIM', 'NAMA RESPONDEN', 'NO HP', 'EMAIL', 'RATING', 'ULASAN', 'SUMBER']);

        foreach ($feedbacks as $index => $fb) {
            $waktuKirimWib = $fb->created_at ? $fb->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') : '-';
            fputcsv($handle, [
                $index + 1,
                $waktuKirimWib,
                $fb->nama_pengunjung,
                $fb->no_hp,
                $fb->email ?? '-',
                $fb->rating,
                $fb->ulasan,
                $fb->sumber
            ]);
        }

        fclose($handle);
        exit;
    }

    public function indexUsers()
    {
        $admins = User::all();
        return view('admin.manage_users', compact('admins'));
    }
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'nip' => $request->nip ?? null,
        ]);

        return redirect()->back()->with('success', 'Akun admin berhasil ditambahkan.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'nip' => $request->nip ?? null,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Akun admin berhasil diperbarui.');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Akun admin berhasil dihapus.');
    }

    public function updateVisitorStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diproses,Diterima,Selesai,Dibatalkan',
        ]);

        $visitor = Visitor::findOrFail($id);
        $visitor->update([
            'status' => $request->status,
            'alasan_batal' => $request->input('alasan_batal', $visitor->alasan_batal)
        ]);

        return redirect()->back()->with('success', 'Status kunjungan berhasil diperbarui.');
    }

    // === METODE AKSI STATUS & KIRIM EMAIL OTOMATIS ===

    public function approveVisitor($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->update(['status' => 'Diterima']);

        if (!empty($visitor->email) && filter_var($visitor->email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::send('emails.visitor_approved', ['visit' => $visitor], function ($message) use ($visitor) {
                    $message->to($visitor->email)
                            ->subject('Konfirmasi Persetujuan Kunjungan Resmi - LPP RRI Bukittinggi');
                });
            } catch (\Exception $e) {
                \Log::error('Gagal kirim email disetujui: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Agenda kunjungan telah resmi disetujui. Email surat konfirmasi kedatangan berhasil dikirimkan ke tamu!');
    }

    public function completeVisitor($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->update(['status' => 'Selesai']);

        if (!empty($visitor->email) && filter_var($visitor->email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::send('emails.visitor_completed', ['visit' => $visitor], function ($message) use ($visitor) {
                    $message->to($visitor->email)
                            ->subject('Terima Kasih Atas Kunjungan Anda - LPP RRI Bukittinggi');
                });
            } catch (\Exception $e) {
                \Log::error('Gagal kirim email selesai: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Kunjungan tamu berhasil ditandai selesai. Email ucapan terima kasih dan formulir survei ulasan telah terkirim!');
    }

    public function cancelVisitor(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);
        $alasan = $request->input('alasan_batal', 'Penyesuaian agenda internal instansi.');
        
        $visitor->update([
            'status' => 'Dibatalkan',
            'alasan_batal' => $alasan
        ]);

        if (!empty($visitor->email) && filter_var($visitor->email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::send('emails.visitor_cancelled', ['visit' => $visitor, 'alasan' => $alasan], function ($message) use ($visitor) {
                    $message->to($visitor->email)
                            ->subject('Pemberitahuan Penyesuaian / Pembatalan Jadwal Kunjungan - LPP RRI Bukittinggi');
                });
            } catch (\Exception $e) {
                \Log::error('Gagal kirim email batal: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Status kunjungan berhasil dibatalkan. Email pemberitahuan penyesuaian jadwal telah dikirimkan ke tamu.');
    }
}