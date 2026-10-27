<!DOCTYPE html>
<html lang="id">
<head>
    <!-- FAVICON -->
    <link rel="icon" type="image/png" href="{{ asset('images/iconapk.png') }}?v=999">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/iconapk.png') }}?v=999">
    
    <!-- CSS untuk memperhalus sudut tampilan ikon -->
    <style>
        link[rel*="icon"] {
            border-radius: 10px !important;
        }
    </style>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Kunjungan Tamu - RRI Bukittinggi</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            font-size: 9px;
            margin: 0;
            padding: 0;
            line-height: 1.45;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 10px;
            margin-bottom: 14px;
            border-collapse: collapse;
        }
        .header-logo {
            width: 110px;
            text-align: left;
            vertical-align: middle;
        }
        .header-logo img {
            width: 95px;
            height: auto;
            display: block;
        }
        .header-text {
            text-align: center;
            vertical-align: middle;
        }
        .header-text h2 {
            margin: 0;
            font-size: 14px;
            color: #1e3a8a;
            font-weight: bold;
            letter-spacing: 0.6px;
        }
        .header-text h3 {
            margin: 3px 0;
            font-size: 12px;
            color: #2563eb;
            font-weight: bold;
            letter-spacing: 0.4px;
        }
        .header-text p {
            margin: 1.5px 0;
            font-size: 8px;
            color: #475569;
        }
        .header-spacer {
            width: 110px;
        }
        
        .title-container {
            text-align: center;
            margin: 10px 0 14px 0;
        }
        .title-laporan {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 2px solid #2563eb;
            display: inline-block;
            padding-bottom: 3px;
        }

        .info-bar {
            width: 100%;
            margin-bottom: 12px;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            border-collapse: collapse;
        }
        .info-bar td {
            padding: 6px 8px;
            vertical-align: middle;
            font-size: 8.5px;
        }
        .print-text {
            color: #475569;
            font-size: 8px;
            font-weight: bold;
            text-align: right;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 12px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 5px;
            text-align: left;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        table.data-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        table.data-table td {
            font-size: 8px;
            color: #1e293b;
        }
        
        .date-box {
            font-weight: bold;
            color: #0f172a;
            font-size: 7.5px;
        }
        .time-badge {
            display: inline-block;
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            padding: 1px 3px;
            border-radius: 3px;
            font-size: 6.5px;
            font-weight: bold;
            margin-top: 2px;
        }

        .visitor-name {
            font-weight: bold;
            color: #0f172a;
            font-size: 8px;
        }
        .visitor-phone {
            display: inline-block;
            color: #2563eb;
            font-size: 6.5px;
            font-weight: bold;
            margin-top: 1px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 0.5px 2px;
            border-radius: 2px;
        }
        .visitor-email {
            display: block;
            color: #475569;
            font-size: 6.5px;
            margin-top: 1px;
            font-style: italic;
        }

        .badge {
            padding: 2px 3px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 6.5px;
            display: inline-block;
            text-align: center;
        }
        .badge-diproses { background-color: #fef3c7; color: #b45309; border: 1px solid #fcd34d; }
        .badge-diterima { background-color: #dbeafe; color: #1d4ed8; border: 1px solid #93c5fd; }
        .badge-selesai { background-color: #d1fae5; color: #047857; border: 1px solid #6ee7b7; }
        .badge-dibatalkan { background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

        .badge-janji {
            display: inline-block;
            padding: 1.5px 3px;
            border-radius: 3px;
            font-size: 6px;
            font-weight: bold;
            margin-top: 2px;
        }
        .badge-sudah-janji { background-color: #f3e8ff; color: #7e22ce; border: 1px solid #d8b4fe; }
        .badge-belum-janji { background-color: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }
        
        .summary-title {
            font-weight: bold;
            font-size: 9px;
            color: #1e3a8a;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            text-align: center;
        }
        .summary-table th {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 5px;
            font-size: 8px;
            font-weight: bold;
            color: #334155;
            text-transform: uppercase;
        }
        .summary-table td {
            border: 1px solid #cbd5e1;
            padding: 6px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .footer-ttd {
            width: 100%;
            margin-top: 20px;
            page-break-inside: avoid;
            font-size: 9px;
            color: #0f172a;
        }
    </style>
</head>
<body>

    <!-- Header Instansi -->
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="{{ public_path('images/logoo_rri.png') }}" alt="Logo RRI" onerror="this.style.display='none'">
            </td>
            <td class="header-text">
                <h2>LEMBAGA PENYIARAN PUBLIK RADIO REPUBLIK INDONESIA</h2>
                <h3>RRI BUKITTINGGI</h3>
                <p>Jl. M. Yamin No.199, Aur Kuning, Kec. Aur Birugo Tigo Baleh, Kota Bukittinggi, Sumatera Barat 26131</p>
                <p>Telepon: (0752) 22583 | Unit Teknologi dan Media Baru (TMB)</p>
            </td>
            <td class="header-spacer"></td>
        </tr>
    </table>

    <div class="title-container">
        <div class="title-laporan">LAPORAN REKAPITULASI KUNJUNGAN TAMU</div>
    </div>

    <table class="info-bar">
        <tr>
            <td style="width: auto; color: #0f172a;">
                <strong style="color: #1e3a8a;">Periode Laporan :</strong> <strong style="color: #0f172a;">{{ $judulPeriode ?? 'Keseluruhan Data' }}</strong>
            </td>
            <td class="print-text" style="width: 240px; white-space: nowrap;">Dicetak Pada : {{ $waktuCetak ?? \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') . ' WIB' }}</td>
        </tr>
    </table>

    <!-- Tabel Data Kunjungan -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">NO</th>
                <th style="width: 12%;">TANGGAL & WAKTU</th>
                <th style="width: 18%;">NAMA, KONTAK & EMAIL</th>
                <th style="width: 12%;">INSTANSI / ALAMAT</th>
                <th style="width: 14%;">DIVISI TUJUAN</th>
                <th style="width: 25%;">MAKSUD & KEPERLUAN</th>
                <th style="width: 8%;">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($visitors as $index => $v)
            <tr>
                <td style="text-align: center; font-weight: bold; color: #475569; vertical-align: middle;">{{ $index + 1 }}</td>
                <td style="text-align: center;">
                    <div class="date-box">{{ \Carbon\Carbon::parse($v->tanggal_kunjungan)->format('d/m/Y') }}</div>
                    <div class="time-badge">{{ \Carbon\Carbon::parse($v->jam_kunjungan)->format('H:i') }} WIB</div>
                </td>
                <td>
                    <div class="visitor-name">{{ $v->nama_lengkap }}</div>
                    <div class="visitor-phone">HP: {{ $v->no_hp }}</div>
                    <div class="visitor-email">{{ $v->email ? $v->email : '-' }}</div>
                </td>
                <td>{{ $v->alamat }}</td>
                <td>
                    <strong style="color: #1e3a8a;">{{ $v->divisi_tujuan }}</strong><br>
                    @if(stripos($v->temu_janji, 'Sudah') !== false || stripos($v->temu_janji, 'Ada') !== false)
                        <span class="badge-janji badge-sudah-janji">SUDAH JANJI</span>
                    @else
                        <span class="badge-janji badge-belum-janji">BELUM JANJI</span>
                    @endif
                </td>
                <td>{{ $v->keperluan }}</td>
                <td style="text-align: center;">
                    @if($v->status == 'Diproses')
                        <span class="badge badge-diproses">DIPROSES</span>
                    @elseif($v->status == 'Diterima')
                        <span class="badge badge-diterima">DITERIMA</span>
                    @elseif($v->status == 'Selesai')
                        <span class="badge badge-selesai">SELESAI</span>
                    @else
                        <span class="badge badge-dibatalkan">DIBATALKAN</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; color: #64748b; font-style: italic;">Tidak ada data kunjungan tamu pada periode atau filter yang dipilih.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-title">RINGKASAN REKAPITULASI STATISTIK:</div>
    <table class="summary-table">
        <tr>
            <th>TOTAL KUNJUNGAN</th>
            <th>DIPROSES (PENDING)</th>
            <th>DITERIMA (BERLANGSUNG)</th>
            <th>SELESAI</th>
            <th>DIBATALKAN</th>
        </tr>
        <tr>
            <td style="color: #1e3a8a;">{{ $summary['total'] ?? $visitors->count() }}</td>
            <td style="color: #b45309;">{{ $summary['diproses'] ?? $visitors->where('status', 'Diproses')->count() }}</td>
            <td style="color: #1d4ed8;">{{ $summary['diterima'] ?? $visitors->where('status', 'Diterima')->count() }}</td>
            <td style="color: #047857;">{{ $summary['selesai'] ?? $visitors->where('status', 'Selesai')->count() }}</td>
            <td style="color: #b91c1c;">{{ $summary['dibatalkan'] ?? $visitors->where('status', 'Dibatalkan')->count() }}</td>
        </tr>
    </table>

    <table class="footer-ttd">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center;">
                Bukittinggi, {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY') }}<br>
                <strong>Petugas / Admin Penanggung Jawab</strong>
                <br><br><br><br><br>
                <strong>{{ $adminPencetak->name ?? 'Administrator RRI' }}</strong><br>
                @if(!empty($adminPencetak->nip))
                    <span style="color: #475569; font-size: 8px;">NIP / NIK. {{ $adminPencetak->nip }}</span>
                @else
                    <span style="color: #475569; font-size: 8px;">NIP / NIK. -</span>
                @endif
            </td>
        </tr>
    </table>

</body>
</html>