<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

// Library PHPSpreadsheet untuk Ekspor Excel Ulasan yang Rapi & Profesional
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

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
            'rataRating' => $feedbacks->avg('rating') ?? 0,
            'bintang5'   => $feedbacks->where('rating', 5)->count(),
            'evaluasi'   => $feedbacks->where('rating', '<=', 3)->count(),
            'beranda'    => $feedbacks->where('sumber', 'beranda')->count(),
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

        // Inisialisasi PHPSpreadsheet untuk Excel yang Rapi & Profesional
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Ulasan');

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

        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LEMBAGA PENYIARAN PUBLIK RADIO REPUBLIK INDONESIA');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12.5)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E3A8A'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', 'RRI BUKITTINGGI');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('B45309'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('A3:F3');
        $sheet->setCellValue('A3', 'Jl. M. Yamin No.199, Aur Kuning, Kec. Aur Birugo Tigo Baleh, Kota Bukittinggi, Sumatera Barat 26131');
        $sheet->getStyle('A3')->getFont()->setSize(8.5)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('475569'));
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('A4:F4');
        $sheet->setCellValue('A4', 'Telepon: (0752) 22583 | Unit Teknologi dan Media Baru (TMB)');
        $sheet->getStyle('A4')->getFont()->setSize(8.5)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('475569'));
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getRowDimension(1)->setRowHeight(18);
        $sheet->getRowDimension(2)->setRowHeight(16);
        $sheet->getRowDimension(3)->setRowHeight(15);
        $sheet->getRowDimension(4)->setRowHeight(15);

        $sheet->getStyle('A5:F5')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM)->getColor()->setARGB('B45309');

        $sheet->mergeCells('A7:F7');
        $sheet->setCellValue('A7', 'LAPORAN REKAPITULASI PENILAIAN & ULASAN PELAYANAN');
        $sheet->getStyle('A7')->getFont()->setBold(true)->setSize(11.5)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('0F172A'));
        $sheet->getStyle('A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A8:C8');
        $sheet->setCellValue('A8', 'Periode Laporan : ' . $judulPeriode);
        $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('B45309'));

        $sheet->mergeCells('D8:F8');
        $sheet->setCellValue('D8', 'Dicetak Pada : ' . $waktuCetak);
        $sheet->getStyle('D8')->getFont()->setSize(8.5)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('475569'));
        $sheet->getStyle('D8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $headers = ['NO', 'WAKTU KIRIM', 'IDENTITAS RESPONDEN', 'RATING', 'ULASAN / KESAN PELAYANAN', 'SUMBER'];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F'];

        foreach ($headers as $index => $header) {
            $col = $cols[$index];
            $sheet->setCellValue($col . '10', $header);
            $sheet->getStyle($col . '10')->getFont()->setBold(true)->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
            $sheet->getStyle($col . '10')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('B45309');
            $sheet->getStyle($col . '10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        }
        $sheet->getRowDimension(10)->setRowHeight(25);

        $row = 11;
        foreach ($feedbacks as $idx => $fb) {
            $waktukirim = $fb->created_at ? $fb->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y') . "\n(" . $fb->created_at->setTimezone('Asia/Jakarta')->format('H:i') . ' WIB)' : '-';
            $identitas  = $fb->nama_pengunjung . "\nHP: " . ($fb->no_hp ?: '-') . ($fb->email ? "\nEmail: " . $fb->email : '');
            $ratingVal  = $fb->rating . '.0 / 5.0';
            $ulasanText = '"' . $fb->ulasan . '"';
            $sumberVal  = strtoupper($fb->sumber);

            $sheet->setCellValue('A' . $row, $idx + 1);
            $sheet->setCellValue('B' . $row, $waktukirim);
            $sheet->setCellValue('C' . $row, $identitas);
            $sheet->setCellValue('D' . $row, $ratingVal);
            $sheet->setCellValue('E' . $row, $ulasanText);
            $sheet->setCellValue('F' . $row, $sumberVal);

            $sheet->getRowDimension($row)->setRowHeight(42);

            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
            $sheet->getStyle('C' . $row)->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('E' . $row)->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('F' . $row)->getFont()->setBold(true);

            if ($sumberVal == 'WHATSAPP' || $sumberVal == 'WA') {
                $sheet->getStyle('F' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('15803D'));
            } elseif ($sumberVal == 'EMAIL') {
                $sheet->getStyle('F' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E40AF'));
            } else {
                $sheet->getStyle('F' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('92400E'));
            }

            if ($idx % 2 == 1) {
                $sheet->getStyle('A' . $row . ':F' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFBEB');
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
        $sheet->getStyle('A10:F' . $lastDataRow)->applyFromArray($styleBorderArray);

        $row += 2;
        $sheet->mergeCells('A' . $row . ':F' . $row);
        $sheet->setCellValue('A' . $row, 'RINGKASAN REKAPITULASI STATISTIK ULASAN:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(9.5)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('B45309'));
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $row++;
        $sheet->setCellValue('A' . $row, 'TOTAL RESPONDEN');
        $sheet->setCellValue('B' . $row, 'RATA-RATA RATING');
        $sheet->setCellValue('C' . $row, 'BINTANG 5 (SANGAT PUAS)');
        $sheet->setCellValue('D' . $row, 'BINTANG 1-3 (EVALUASI)');
        $sheet->mergeCells('E' . $row . ':F' . $row);
        $sheet->setCellValue('E' . $row, 'VIA BERANDA');

        $statColsArr = ['A', 'B', 'C', 'D', 'E'];
        foreach ($statColsArr as $sc) {
            $sheet->getStyle($sc . $row)->getFont()->setBold(true)->setSize(8)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('92400E'));
            $sheet->getStyle($sc . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FEF3C7');
            $sheet->getStyle($sc . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        }
        $sheet->getStyle('F' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FEF3C7');
        $sheet->getRowDimension($row)->setRowHeight(22);

        $row++;
        $totalRespondenVal = $feedbacks->count();
        $rataRatingVal     = ($feedbacks->avg('rating') ?? 0);
        $bintang5Val       = $feedbacks->where('rating', 5)->count();
        $evaluasiVal       = $feedbacks->where('rating', '<=', 3)->count();
        $berandaVal        = $feedbacks->where('sumber', 'beranda')->count();

        $sheet->setCellValue('A' . $row, $totalRespondenVal);
        $sheet->setCellValue('B' . $row, number_format($rataRatingVal, 1) . ' / 5.0');
        $sheet->setCellValue('C' . $row, $bintang5Val);
        $sheet->setCellValue('D' . $row, $evaluasiVal);
        $sheet->mergeCells('E' . $row . ':F' . $row);
        $sheet->setCellValue('E' . $row, $berandaVal);

        $valColsArr = ['A', 'B', 'C', 'D', 'E'];
        foreach ($valColsArr as $vc) {
            $sheet->getStyle($vc . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($vc . $row)->getFont()->setBold(true)->setSize(10);
        }
        $sheet->getStyle('A' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E3A8A'));
        $sheet->getStyle('B' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('B45309'));
        $sheet->getStyle('C' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('047857'));
        $sheet->getStyle('D' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('B91C1C'));
        $sheet->getStyle('E' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1D4ED8'));

        $sheet->getRowDimension($row)->setRowHeight(24);
        $sheet->getStyle('A' . ($row - 1) . ':F' . $row)->applyFromArray($styleBorderArray);

        $row += 2;
        $sheet->mergeCells('E' . $row . ':F' . $row);
        $sheet->setCellValue('E' . $row, 'Bukittinggi, ' . Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY'));
        $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row++;
        $sheet->mergeCells('E' . $row . ':F' . $row);
        $sheet->setCellValue('E' . $row, 'Petugas / Admin Penanggung Jawab');
        $sheet->getStyle('E' . $row)->getFont()->setBold(true);
        $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row += 4;
        $adminUser = auth()->user();
        $adminName = $adminUser->name ?? 'Administrator RRI';
        $adminNip  = !empty($adminUser->nip) ? 'NIP / NIK. ' . $adminUser->nip : 'NIP / NIK. -';

        $sheet->mergeCells('E' . $row . ':F' . $row);
        $sheet->setCellValue('E' . $row, $adminName);
        $sheet->getStyle('E' . $row)->getFont()->setBold(true);
        $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row++;
        $sheet->mergeCells('E' . $row . ':F' . $row);
        $sheet->setCellValue('E' . $row, $adminNip);
        $sheet->getStyle('E' . $row)->getFont()->setSize(8.5)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('475569'));
        $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A1:F' . $row)->applyFromArray([
            'outline' => [
                'borderStyle' => Border::BORDER_MEDIUM,
                'color' => ['argb' => 'B45309'], 
            ],
        ]);

        $sheet->getColumnDimension('A')->setWidth(7);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(32);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(45);
        $sheet->getColumnDimension('F')->setWidth(18);

        $fileName = 'laporan-ulasan-pelayanan-' . Carbon::now('Asia/Jakarta')->format('Y-m-d') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
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