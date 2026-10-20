<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kunjungan Disetujui - RRI Bukittinggi</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
        
        <!-- Header Instansi & Logo -->
        <div style="background: linear-gradient(135deg, #047857 0%, #065f46 100%); padding: 24px; text-align: center; color: #ffffff;">
            <img src="http://127.0.0.1:8000/images/logoo_rri.png" alt="Logo RRI Bukittinggi" style="width: 65px; height: auto; margin-bottom: 8px; display: inline-block;" crossorigin="anonymous">
            <p style="margin: 4px 0 0 0; font-size: 10px; color: #d1fae5; text-transform: uppercase; letter-spacing: 1px;">Konfirmasi Persetujuan Kunjungan Resmi</p>
        </div>

        <!-- Konten Utama -->
        <div style="padding: 24px;">
            <p style="font-size: 13px; font-weight: bold; color: #334155; margin-top: 0;">
                Yth. Bapak/Ibu <span style="color: #047857;">{{ $visit->nama_lengkap }}</span>,
            </p>
            
            <p style="font-size: 12px; color: #475569; line-height: 1.6;">
                Kabar baik! Pengajuan kunjungan Anda telah <strong style="color: #047857;">DISETUJUI</strong> oleh pihak LPP RRI Bukittinggi. Kami siap menyambut kedatangan Anda sesuai dengan rincian jadwal di bawah ini:
            </p>

            <!-- Box Status Diterima -->
            <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 14px; margin: 16px 0;">
                <div style="font-size: 11px; font-weight: 900; color: #065f46; text-transform: uppercase; margin-bottom: 8px;">
                    ✅ Status: Kunjungan Diterima / Dijadwalkan
                </div>
                <table style="width: 100%; font-size: 11px; color: #334155; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 4px 0; color: #64748b; width: 35%;">Divisi / Unit Tujuan</td>
                        <td style="padding: 4px 0; font-weight: bold;">: {{ $visit->divisi_tujuan }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">Hari / Tanggal</td>
                        <td style="padding: 4px 0; font-weight: bold;">: {{ \Carbon\Carbon::parse($visit->tanggal_kunjungan)->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">Waktu Kedatangan</td>
                        <td style="padding: 4px 0; font-weight: bold;">: {{ $visit->jam_kunjungan }} WIB</td>
                    </tr>
                </table>
            </div>

            <!-- Ketentuan Protokol -->
            <div style="background-color: #f8fafc; border-left: 4px solid #047857; padding: 12px; font-size: 11px; color: #334155; margin-bottom: 16px; border-radius: 0 8px 8px 0;">
                <strong style="color: #065f46; display: block; margin-bottom: 4px;">Ketentuan & Protokol Kunjungan:</strong>
                <ul style="margin: 0; padding-left: 16px; line-height: 1.5; color: #475569;">
                    <li>Hadir tepat waktu atau 10 menit sebelum jadwal yang telah ditentukan.</li>
                    <li>Menunjukkan bukti email konfirmasi ini kepada petugas resepsionis / keamanan di lobi utama gedung.</li>
                    <li>Mengenakan pakaian yang rapi, sopan, serta mematuhi tata tertib di lingkungan LPP RRI Bukittinggi.</li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 16px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8;">
            &copy; {{ date('Y') }} LPP RRI Bukittinggi - Tim Teknologi & Media Baru.
        </div>
    </div>
</body>
</html>