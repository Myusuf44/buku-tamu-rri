<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemberitahuan Pembatalan Kunjungan - RRI Bukittinggi</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
        
        <!-- Header Instansi & Logo -->
        <div style="background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%); padding: 24px; text-align: center; color: #ffffff;">
            <img src="{{ url('images/logoo_rri.png') }}" alt="Logo RRI Bukittinggi" style="width: 65px; height: auto; margin-bottom: 8px; display: inline-block;">
            <h2 style="margin: 0; font-size: 16px; font-weight: 900; letter-spacing: 0.5px;">LPP RRI BUKITTINGGI</h2>
            <p style="margin: 4px 0 0 0; font-size: 10px; color: #fee2e2; text-transform: uppercase; letter-spacing: 1px;">Pemberitahuan Penyesuaian Jadwal Kunjungan</p>
        </div>

        <!-- Konten Utama -->
        <div style="padding: 24px;">
            <p style="font-size: 13px; font-weight: bold; color: #334155; margin-top: 0;">
                Yth. Bapak/Ibu <span style="color: #b91c1c;">{{ $visit->nama_lengkap ?? 'Tamu LPP RRI' }}</span>,
            </p>
            
            <p style="font-size: 12px; color: #475569; line-height: 1.6;">
                Kami menginformasikan bahwa rencana agenda kunjungan Anda ke LPP RRI Bukittinggi saat ini <strong style="color: #b91c1c;">DIBATALKAN / BELUM DAPAT DIPROSES</strong> dikarenakan penyesuaian jadwal dinas internal instansi atau atas permintaan konfirmasi sebelumnya.
            </p>

            <!-- Box Status Dibatalkan -->
            <div style="background-color: #fef2f2; border: 1px solid #fca5a5; border-radius: 12px; padding: 14px; margin: 16px 0;">
                <div style="font-size: 11px; font-weight: 900; color: #991b1b; text-transform: uppercase; margin-bottom: 8px;">
                    ❌ Status: Kunjungan Dibatalkan
                </div>
                <table style="width: 100%; font-size: 11px; color: #334155; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 4px 0; color: #64748b; width: 35%;">Divisi / Unit Tujuan</td>
                        <td style="padding: 4px 0; font-weight: bold;">: {{ $visit->divisi_tujuan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">Rencana Tanggal</td>
                        <td style="padding: 4px 0; font-weight: bold;">: {{ isset($visit->tanggal_kunjungan) ? \Carbon\Carbon::parse($visit->tanggal_kunjungan)->locale('id')->isoFormat('D MMMM YYYY') : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">Waktu Kunjungan</td>
                        <td style="padding: 4px 0; font-weight: bold;">: {{ $visit->jam_kunjungan ?? '-' }} WIB</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">Alasan Pembatalan</td>
                        <td style="padding: 4px 0; font-weight: bold; color: #b91c1c;">: {{ $alasan_text ?? $visit->alasan_batal ?? 'Penyesuaian agenda layanan instansi.' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Petunjuk Penjadwalan Ulang -->
            <div style="background-color: #f8fafc; border-left: 4px solid #b91c1c; padding: 12px; font-size: 11px; color: #334155; margin-bottom: 16px; border-radius: 0 8px 8px 0;">
                <strong style="color: #991b1b; display: block; margin-bottom: 4px;">Informasi Penjadwalan Ulang (Reschedule):</strong>
                <p style="margin: 0; line-height: 1.5; color: #475569;">
                    Apabila Anda memerlukan penjadwalan ulang kunjungan di lain waktu, Anda dapat mengajukan kembali melalui situs web Buku Tamu Digital resmi kami.
                </p>
            </div>

            <p style="font-size: 11px; color: #64748b; line-height: 1.5; margin-bottom: 0;">
                Mohon maaf atas ketidaknyamanan ini. Terima kasih atas pengertian dan kerja sama Bapak/Ibu.
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 16px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8;">
            &copy; {{ date('Y') }} LPP RRI Bukittinggi - Tim Teknologi & Media Baru.<br>
            <em>Sekali di Udara, Tetap di Udara</em>
        </div>
    </div>
</body>
</html>