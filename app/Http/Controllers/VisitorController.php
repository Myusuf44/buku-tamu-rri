<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

// Library PHPSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class VisitorController extends Controller
{
    /**
     * Menampilkan Halaman Utama Publik beserta Ulasan & Rating Real-time
     */
    public function index(Request $request)
    {
        $publicFeedbacks = Feedback::latest()->take(6)->get();
        $avgRating = Feedback::avg('rating') ?? 0;

        return view('home', compact('publicFeedbacks', 'avgRating'));
    }

    /**
     * Mengarahkan tautan dari WhatsApp/Email ke beranda & modal/section penilaian
     */
    public function redirectUlasan($tipe)
    {
        $sumber = 'beranda';
        if ($tipe == '1' || strtolower($tipe) === 'whatsapp' || strtolower($tipe) === 'wa') {
            $sumber = 'whatsapp';
        } elseif ($tipe == '2' || strtolower($tipe) === 'email') {
            $sumber = 'email';
        }

        return redirect()->to(route('home', ['sumber' => $sumber]) . '#penilaian-publik');
    }

    /**
     * Menyimpan data kunjungan tamu baru dari formulir publik & mengirim Email Pending (Registrasi)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap'      => 'required|string|max:255',
            'no_hp'             => 'required|string|max:20',
            'email'             => 'nullable|email|max:255',
            'alamat'            => 'required|string',
            'divisi_tujuan'     => 'required|string',
            'tanggal_kunjungan' => 'required|date',
            'jam_kunjungan'     => 'required',
            'temu_janji'        => 'required|string',
            'keperluan'         => 'required|string',
        ]);

        $validated['status'] = 'Diproses';

        $visitor = Visitor::create($validated);

        // Kirim Email Konfirmasi Pendaftaran (Status: Diproses / Pending)
        if (!empty($visitor->email)) {
            try {
                Mail::send('emails.visitor_registered', ['visit' => $visitor], function ($message) use ($visitor) {
                    $message->to($visitor->email, $visitor->nama_lengkap)
                            ->from('onboarding@resend.dev', 'Layanan Tamu RRI Bukittinggi')
                            ->subject('Registrasi Kunjungan Sedang Diproses - RRI Bukittinggi');
                });
            } catch (\Exception $e) {
                Log::error("Gagal mengirim email pendaftaran: " . $e->getMessage());
            }
        }

        $pesanSukses = 'Terima kasih! Data pendaftaran kunjungan Anda telah berhasil dicatat. Silakan cek email Anda untuk melihat status verifikasi.';
        return redirect()->back()->with('success', $pesanSukses);
    }

    /**
     * ADMIN: Mengubah Status Kunjungan Menjadi 'Diterima' (Approved) & Kirim Email
     */
    public function approveVisitor($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->update(['status' => 'Diterima']);

        if (!empty($visitor->email)) {
            try {
                Mail::send('emails.visitor_approved', ['visit' => $visitor], function ($message) use ($visitor) {
                    $message->to($visitor->email, $visitor->nama_lengkap)
                            ->from('onboarding@resend.dev', 'Layanan Tamu RRI Bukittinggi')
                            ->subject('Kunjungan Anda Telah Disetujui - RRI Bukittinggi');
                });
            } catch (\Exception $e) {
                Log::error("Gagal mengirim email persetujuan: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Status kunjungan berhasil diubah menjadi Diterima dan email konfirmasi telah dikirim.');
    }

    /**
     * ADMIN: Mengubah Status Kunjungan Menjadi 'Selesai' (Completed) & Kirim Email Ulasan (Sumber Email)
     */
    public function completeVisitor($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->update(['status' => 'Selesai']);

        if (!empty($visitor->email)) {
            try {
                Mail::send('emails.visitor_completed', ['visit' => $visitor], function ($message) use ($visitor) {
                    $message->to($visitor->email, $visitor->nama_lengkap)
                            ->from('onboarding@resend.dev', 'Layanan Tamu RRI Bukittinggi')
                            ->subject('Terima Kasih Atas Kunjungan Anda - RRI Bukittinggi');
                });
            } catch (\Exception $e) {
                Log::error("Gagal mengirim email selesai: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Kunjungan ditandai Selesai dan tautan ulasan via email telah dikirimkan ke tamu.');
    }

    /**
     * ADMIN/SISTEM: Mengubah Status Kunjungan Menjadi 'Dibatalkan' (Cancelled) & Kirim Email Pembatalan
     */
    public function cancelVisitor(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);
        
        $visitor->update([
            'status'       => 'Dibatalkan',
            'alasan_batal' => $request->input('alasan_batal', 'Dibatalkan oleh instansi / penyesuaian jadwal.')
        ]);

        if (!empty($visitor->email)) {
            try {
                Mail::send('emails.visitor_cancelled', ['visit' => $visitor], function ($message) use ($visitor) {
                    $message->to($visitor->email, $visitor->nama_lengkap)
                            ->from('onboarding@resend.dev', 'Layanan Tamu RRI Bukittinggi')
                            ->subject('Pemberitahuan Pembatalan Kunjungan - RRI Bukittinggi');
                });
            } catch (\Exception $e) {
                Log::error("Gagal mengirim email pembatalan: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Status kunjungan berhasil dibatalkan dan pemberitahuan terkirim via email.');
    }

    /**
     * Menyimpan data Ulasan / Feedback dari pengunjung (Mendukung sumber: beranda, whatsapp, email)
     */
    public function storeFeedback(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_hp'        => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:255',
            'rating'       => 'required|integer|min:1|max:5',
            'ulasan'       => 'required|string',
            'sumber'       => 'nullable|string',
        ]);

        $rawSumber = strtolower(trim($request->input('sumber', 'beranda')));
        
        if ($rawSumber === 'whatsapp' || $rawSumber === 'wa') {
            $sumberFinal = 'whatsapp';
        } elseif ($rawSumber === 'email') {
            $sumberFinal = 'email';
        } else {
            $sumberFinal = 'beranda';
        }

        Feedback::create([
            'nama_pengunjung'  => $validated['nama_lengkap'],
            'no_hp'            => $validated['no_hp'] ?? '-',
            'email'            => $validated['email'] ?? null,
            'rating'           => $validated['rating'],
            'ulasan'           => $validated['ulasan'],
            'sumber'           => $sumberFinal,
            'status_pengisian' => 'langsung_beranda',
        ]);

        return redirect()->route('home')->with('success', 'Terima kasih! Penilaian dan ulasan Anda telah berhasil dikirim dan dicatat.');
    }

    /**
     * Export data kunjungan ke PDF dengan filter periode/divisi
     */
    public function exportPdf(Request $request)
    {
        $query = Visitor::query();

        if ($request->filled('divisi') && trim($request->divisi) !== '') {
            $query->where('divisi_tujuan', $request->divisi);
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

        $visitors = $query->orderBy('tanggal_kunjungan', 'desc')->get();

        if ($visitors->isEmpty()) {
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data kunjungan tidak ditemukan'
                ], 404);
            }
        }

        $judulPeriode = 'Keseluruhan Data Kunjungan Tamu';
        if ($request->filled('tanggal') && trim($request->tanggal) !== '') {
            $judulPeriode = Carbon::parse($request->tanggal)->locale('id')->isoFormat('D MMMM YYYY');
        } elseif (($request->filled('bulan') && trim($request->bulan) !== '') || ($request->filled('tahun') && trim($request->tahun) !== '')) {
            $bln = ($request->filled('bulan') && trim($request->bulan) !== '') ? Carbon::create()->month((int)$request->bulan)->locale('id')->isoFormat('MMMM') : '';
            $thn = ($request->filled('tahun') && trim($request->tahun) !== '') ? $request->tahun : date('Y');
            $judulPeriode = trim("Bulan $bln Tahun $thn");
        }

        $summary = [
            'total'      => $visitors->count(),
            'diproses'   => $visitors->where('status', 'Diproses')->count(),
            'diterima'   => $visitors->where('status', 'Diterima')->count(),
            'selesai'    => $visitors->where('status', 'Selesai')->count(),
            'dibatalkan' => $visitors->where('status', 'Dibatalkan')->count(),
        ];

        $waktuCetak = Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') . ' WIB';
        $adminPencetak = auth()->user();

        $pdf = Pdf::loadView('admin.pdf', compact('visitors', 'judulPeriode', 'waktuCetak', 'summary', 'adminPencetak'));
        
        return $pdf->stream('laporan-buku-tamu-rri.pdf');
    }

    /**
     * Export Laporan Rekapitulasi Kunjungan Tamu ke Excel (.xlsx)
     */
    public function exportExcel(Request $request)
    {
        $query = Visitor::query();

        if ($request->filled('divisi') && trim($request->divisi) !== '') {
            $query->where('divisi_tujuan', $request->divisi);
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

        $visitors = $query->orderBy('tanggal_kunjungan', 'desc')->get();

        if ($visitors->isEmpty()) {
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data kunjungan tidak ditemukan'
                ], 404);
            }
        }

        $judulPeriode = 'Keseluruhan Data Kunjungan Tamu';
        if ($request->filled('tanggal') && trim($request->tanggal) !== '') {
            $judulPeriode = Carbon::parse($request->tanggal)->locale('id')->isoFormat('D MMMM YYYY');
        } elseif (($request->filled('bulan') && trim($request->bulan) !== '') || ($request->filled('tahun') && trim($request->tahun) !== '')) {
            $bln = ($request->filled('bulan') && trim($request->bulan) !== '') ? Carbon::create()->month((int)$request->bulan)->locale('id')->isoFormat('MMMM') : '';
            $thn = ($request->filled('tahun') && trim($request->tahun) !== '') ? $request->tahun : date('Y');
            $judulPeriode = trim("Bulan $bln Tahun $thn");
        }
        $waktuCetak = Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') . ' WIB';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Kunjungan');

        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
        $sheet->setShowGridlines(false);

        $logoPath = public_path('images/logoo_rri.png');
        if (file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setName('Logo RRI');
            $drawing->setDescription('Logo LPP RRI Bukittinggi');
            $drawing->setPath($logoPath);
            $drawing->setHeight(48); 
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(12);
            $drawing->setOffsetY(5);
            $drawing->setWorksheet($sheet);
        }

        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'LEMBAGA PENYIARAN PUBLIK RADIO REPUBLIK INDONESIA');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12.5)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E3A8A'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'RRI BUKITTINGGI');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('2563EB'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('A3:G3');
        $sheet->setCellValue('A3', 'Jl. M. Yamin No.199, Aur Kuning, Kec. Aur Birugo Tigo Baleh, Kota Bukittinggi, Sumatera Barat 26131');
        $sheet->getStyle('A3')->getFont()->setSize(8.5)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('475569'));
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('A4:G4');
        $sheet->setCellValue('A4', 'Telepon: (0752) 22583 | Unit Teknologi dan Media Baru (TMB)');
        $sheet->getStyle('A4')->getFont()->setSize(8.5)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('475569'));
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getRowDimension(1)->setRowHeight(18);
        $sheet->getRowDimension(2)->setRowHeight(16);
        $sheet->getRowDimension(3)->setRowHeight(15);
        $sheet->getRowDimension(4)->setRowHeight(15);

        $sheet->getStyle('A5:G5')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM)->getColor()->setARGB('1E3A8A');

        $sheet->mergeCells('A7:G7');
        $sheet->setCellValue('A7', 'LAPORAN REKAPITULASI KUNJUNGAN TAMU');
        $sheet->getStyle('A7')->getFont()->setBold(true)->setSize(11.5)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('0F172A'));
        $sheet->getStyle('A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A8:D8');
        $sheet->setCellValue('A8', 'Periode Laporan : ' . $judulPeriode);
        $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E3A8A'));

        $sheet->mergeCells('E8:G8');
        $sheet->setCellValue('E8', 'Dicetak Pada : ' . $waktuCetak);
        $sheet->getStyle('E8')->getFont()->setSize(8.5)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('475569'));
        $sheet->getStyle('E8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $headers = ['NO', 'TANGGAL & WAKTU', 'NAMA, KONTAK & EMAIL', 'ALAMAT / INSTANSI', 'DIVISI TUJUAN', 'MAKSUD & KEPERLUAN', 'STATUS'];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

        foreach ($headers as $index => $header) {
            $col = $cols[$index];
            $sheet->setCellValue($col . '10', $header);
            $sheet->getStyle($col . '10')->getFont()->setBold(true)->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
            $sheet->getStyle($col . '10')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('1E3A8A');
            $sheet->getStyle($col . '10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        }
        $sheet->getRowDimension(10)->setRowHeight(25);

        $row = 11;
        foreach ($visitors as $idx => $v) {
            $tgl = Carbon::parse($v->tanggal_kunjungan)->format('d/m/Y') . "\n(" . $v->jam_kunjungan . " WIB)";
            $kontak = $v->nama_lengkap . "\nHP: " . $v->no_hp . ($v->email ? "\nEmail: " . $v->email : '');
            $divisi = $v->divisi_tujuan . "\n[" . strtoupper($v->temu_janji) . "]";

            $sheet->setCellValue('A' . $row, $idx + 1);
            $sheet->setCellValue('B' . $row, $tgl);
            $sheet->setCellValue('C' . $row, $kontak);
            $sheet->setCellValue('D' . $row, $v->alamat);
            $sheet->setCellValue('E' . $row, $divisi);
            $sheet->setCellValue('F' . $row, $v->keperluan);
            $sheet->setCellValue('G' . $row, strtoupper($v->status));

            $sheet->getRowDimension($row)->setRowHeight(42);

            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
            $sheet->getStyle('C' . $row)->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('D' . $row)->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('E' . $row)->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('F' . $row)->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_CENTER);
            
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('G' . $row)->getFont()->setBold(true);

            if ($v->status == 'Selesai') {
                $sheet->getStyle('G' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('047857'));
            } elseif ($v->status == 'Diterima') {
                $sheet->getStyle('G' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1D4ED8'));
            } elseif ($v->status == 'Diproses') {
                $sheet->getStyle('G' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('B45309'));
            } else {
                $sheet->getStyle('G' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('B91C1C'));
            }

            if ($idx % 2 == 1) {
                $sheet->getStyle('A' . $row . ':G' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F8FAFC');
            }

            $row++;
        }

        $lastDataRow = $row - 1;
        $styleBorderArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'CBD5E1'],
                ],
            ],
        ];
        $sheet->getStyle('A10:G' . $lastDataRow)->applyFromArray($styleBorderArray);

        $row += 2;
        $sheet->mergeCells('A' . $row . ':G' . $row);
        $sheet->setCellValue('A' . $row, 'RINGKASAN REKAPITULASI STATISTIK:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(9.5)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E3A8A'));
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $row++;
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->setCellValue('A' . $row, 'TOTAL KUNJUNGAN');
        $sheet->setCellValue('C' . $row, 'DIPROSES (PENDING)');
        $sheet->setCellValue('D' . $row, 'DITERIMA (BERLANGSUNG)');
        $sheet->setCellValue('E' . $row, 'SELESAI');
        $sheet->setCellValue('F' . $row, 'DIBATALKAN');
        
        $statColsArr = ['A', 'C', 'D', 'E', 'F'];
        foreach ($statColsArr as $sc) {
            $sheet->getStyle($sc . $row)->getFont()->setBold(true)->setSize(8)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E293B'));
            $sheet->getStyle($sc . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E2E8F0');
            $sheet->getStyle($sc . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        }
        $sheet->getStyle('B' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E2E8F0');
        $sheet->getRowDimension($row)->setRowHeight(22);

        $row++;
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->setCellValue('A' . $row, $visitors->count());
        $sheet->setCellValue('C' . $row, $visitors->where('status', 'Diproses')->count());
        $sheet->setCellValue('D' . $row, $visitors->where('status', 'Diterima')->count());
        $sheet->setCellValue('E' . $row, $visitors->where('status', 'Selesai')->count());
        $sheet->setCellValue('F' . $row, $visitors->where('status', 'Dibatalkan')->count());

        $valColsArr = ['A', 'B', 'C', 'D', 'E', 'F'];
        foreach ($valColsArr as $vc) {
            $sheet->getStyle($vc . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($vc . $row)->getFont()->setBold(true)->setSize(10.5);
        }
        $sheet->getStyle('C' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('B45309'));
        $sheet->getStyle('D' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1D4ED8'));
        $sheet->getStyle('E' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('047857'));
        $sheet->getStyle('F' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('B91C1C'));

        $sheet->getRowDimension($row)->setRowHeight(24);
        
        $sheet->getStyle('A' . ($row - 1) . ':F' . $row)->applyFromArray($styleBorderArray);

        $row += 2;
        $sheet->mergeCells('F' . $row . ':G' . $row);
        $sheet->setCellValue('F' . $row, 'Bukittinggi, ' . Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY'));
        $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row++;
        $sheet->mergeCells('F' . $row . ':G' . $row);
        $sheet->setCellValue('F' . $row, 'Petugas / Admin Penanggung Jawab');
        $sheet->getStyle('F' . $row)->getFont()->setBold(true);
        $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row += 4;
        $adminName = auth()->user()->name ?? 'Administrator RRI';
        $sheet->mergeCells('F' . $row . ':G' . $row);
        $sheet->setCellValue('F' . $row, $adminName);
        $sheet->getStyle('F' . $row)->getFont()->setBold(true);
        $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row++;
        $sheet->mergeCells('F' . $row . ':G' . $row);
        $sheet->setCellValue('F' . $row, 'LPP RRI Bukittinggi');
        $sheet->getStyle('F' . $row)->getFont()->setSize(8.5)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('64748B'));
        $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A1:G' . $row)->applyFromArray([
            'outline' => [
                'borderStyle' => Border::BORDER_MEDIUM,
                'color' => ['argb' => '1E3A8A'], 
            ],
        ]);

        $sheet->getColumnDimension('A')->setWidth(7);
        $sheet->getColumnDimension('B')->setWidth(21);
        $sheet->getColumnDimension('C')->setWidth(32);
        $sheet->getColumnDimension('D')->setWidth(28);
        $sheet->getColumnDimension('E')->setWidth(28);
        $sheet->getColumnDimension('F')->setWidth(38);
        $sheet->getColumnDimension('G')->setWidth(16);

        $fileName = 'laporan-buku-tamu-rri.xlsx';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}