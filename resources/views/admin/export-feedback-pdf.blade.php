<!DOCTYPE html>
<html lang="id">
<head>
    <!-- FAVICON -->
    <link rel="icon" type="image/png" href="{{ asset('images/icontab.png') }}?v=999">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/iconapk.png') }}?v=999">
    
    <!-- CSS untuk memperhalus sudut tampilan ikon -->
    <style>
        link[rel*="icon"] {
            border-radius: 10px !important;
        }
    </style>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Penilaian & Ulasan - RRI Bukittinggi</title>
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
            border-bottom: 3px solid #b45309;
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
            color: #b45309;
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
            border-bottom: 2px solid #b45309;
            display: inline-block;
            padding-bottom: 3px;
        }

        .info-bar {
            width: 100%;
            margin-bottom: 12px;
            background-color: #fffbeb;
            border: 1px solid #fcd34d;
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
            background-color: #b45309;
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
        
        .star-rating {
            color: #d97706;
            font-weight: bold;
            text-align: center;
        }

        /* BADGE KELAS SUMBER (DENGAN KONDISIONAL WARNA) */
        .badge-sumber {
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 6.5px;
            display: inline-block;
            text-transform: uppercase;
        }
        .badge-wa {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
        }
        .badge-email {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
        .badge-beranda {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .summary-title {
            font-weight: bold;
            font-size: 9px;
            color: #b45309;
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
            background-color: #fffbeb;
            border: 1px solid #fcd34d;
            padding: 5px;
            font-size: 8px;
            font-weight: bold;
            color: #92400e;
            text-transform: uppercase;
        }
        .summary-table td {
            border: 1px solid #fcd34d;
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
        <div class="title-laporan">LAPORAN REKAPITULASI PENILAIAN & ULASAN PELAYANAN</div>
    </div>

    <table class="info-bar">
        <tr>
            <td style="width: auto; color: #0f172a;">
                <strong style="color: #b45309;">Periode Laporan :</strong> <strong>{{ $judulPeriode }}</strong>
            </td>
            <td class="print-text" style="width: 240px; white-space: nowrap;">Dicetak Pada : {{ $waktuCetak }}</td>
        </tr>
    </table>

    <!-- Tabel Data Ulasan -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">NO</th>
                <th style="width: 14%;">WAKTU KIRIM</th>
                <th style="width: 20%;">IDENTITAS RESPONDEN</th>
                <th style="width: 10%;">RATING</th>
                <th style="width: 38%;">ULASAN / KESAN PELAYANAN</th>
                <th style="width: 14%;">SUMBER</th>
            </tr>
        </thead>
        <tbody>
            @forelse($feedbacks as $index => $fb)
            @php
                $sumberLower = strtolower($fb->sumber);
            @endphp
            <tr>
                <td style="text-align: center; font-weight: bold; color: #475569;">{{ $index + 1 }}</td>
                <td style="text-align: center;">
                    <div style="font-weight: bold;">{{ \Carbon\Carbon::parse($fb->created_at)->format('d/m/Y') }}</div>
                    <div style="color: #64748b; font-size: 7px;">{{ \Carbon\Carbon::parse($fb->created_at)->format('H:i') }} WIB</div>
                </td>
                <td>
                    <div style="font-weight: bold;">{{ $fb->nama_pengunjung }}</div>
                    <div style="color: #2563eb; font-size: 7px;">HP: {{ $fb->no_hp ?: '-' }}</div>
                    <div style="color: #64748b; font-size: 7px; font-style: italic;">{{ $fb->email ?: '-' }}</div>
                </td>
                <td class="star-rating">
                    {{ $fb->rating }}.0 / 5.0
                </td>
                <td>
                    <em style="color: #334155;">"{{ $fb->ulasan }}"</em>
                </td>
                <td style="text-align: center;">
                    @if(in_array($sumberLower, ['whatsapp', 'wa']))
                        <span class="badge-sumber badge-wa">WHATSAPP</span>
                    @elseif($sumberLower == 'email')
                        <span class="badge-sumber badge-email">EMAIL</span>
                    @else
                        <span class="badge-sumber badge-beranda">BERANDA</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px; color: #64748b; font-style: italic;">
                    Tidak ada data ulasan pada periode atau filter yang dipilih.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-title">RINGKASAN REKAPITULASI STATISTIK ULASAN:</div>
    <table class="summary-table">
        <tr>
            <th>TOTAL RESPONDEN</th>
            <th>RATA-RATA RATING</th>
            <th>BINTANG 5 (SANGAT PUAS)</th>
            <th>BINTANG 1-3 (EVALUASI)</th>
            <th>VIA BERANDA</th>
        </tr>
        <tr>
            <td style="color: #1e3a8a;">{{ $summary['total'] }}</td>
            <td style="color: #b45309;">{{ number_format($summary['rataRating'], 1) }} / 5.0</td>
            <td style="color: #047857;">{{ $summary['bintang5'] }}</td>
            <td style="color: #b91c1c;">{{ $summary['evaluasi'] }}</td>
            <td style="color: #1d4ed8;">{{ $summary['beranda'] }}</td>
        </tr>
    </table>

    <table class="footer-ttd">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center;">
                Bukittinggi, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}<br>
                <strong>Petugas / Admin Penanggung Jawab</strong>
                <br><br><br><br><br>
                <strong>{{ $adminPencetak->name ?? 'Administrator RRI' }}</strong><br>
                    <span style="color: #475569; font-size: 8px;">NIP / NIK. {{ $adminPencetak->nip ?? '-' }}</span>
            </td>
        </tr>
    </table>

</body>
</html>