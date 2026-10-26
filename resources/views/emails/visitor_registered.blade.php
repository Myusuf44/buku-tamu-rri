<!DOCTYPE html>
<html lang="id">
<head>
    
    <meta charset="UTF-8">
    <title>Registrasi Kunjungan Diproses - RRI Bukittinggi</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
        
        <!-- Header Instansi & Logo -->
        <div style="background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%); padding: 24px; text-align: center; color: #ffffff;">
            <img src="{{ url('images/logoo_rri.png') }}" alt="Logo RRI Bukittinggi" style="width: 65px; height: auto; margin-bottom: 8px; display: inline-block;">
            <h2 style="margin: 0; font-size: 16px; font-weight: 900; letter-spacing: 0.5px;">LPP RRI BUKITTINGGI</h2>
            <p style="margin: 4px 0 0 0; font-size: 10px; color: #cbd5e1; text-transform: uppercase; letter-spacing: 1px;">Layanan Registrasi Buku Tamu Digital TMB</p>
        </div>

        <!-- Konten Utama -->
        <div style="padding: 24px;">
            <p style="font-size: 13px; font-weight: bold; color: #334155; margin-top: 0;">
                Yth. Bapak/Ibu <span style="color: #b45309;">{{ $visit->nama_lengkap }}</span>,
            </p>
            
            <p style="font-size: 12px; color: #475569; line-height: 1.6;">
                Terima kasih telah mengisi Formulir Buku Tamu Digital LPP Radio Republik Indonesia (RRI) Bukittinggi. Pengajuan rencana kunjungan Anda telah berhasil dicatat oleh sistem kami dan saat ini <strong style="color: #d97706;">sedang dalam tahap verifikasi / peninjauan oleh tim admin kami</strong>.
            </p>

            <!-- Box Status Pending -->
            <div style="background-color: #fffbeb; border: 1px solid #fcd34d; border-radius: 12px; padding: 14px; margin: 16px 0;">
                <div style="font-size: 11px; font-weight: 900; color: #92400e; text-transform: uppercase; margin-bottom: 8px;">
                    ⏳ Status Kunjungan: Sedang Diproses (Pending)
                </div>
                <table style="width: 100%; font-size: 11px; color: #334155; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 4px 0; color: #64748b; width: 35%;">Divisi / Unit Tujuan</td>
                        <td style="padding: 4px 0; font-weight: bold;">: {{ $visit->divisi_tujuan }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">Rencana Tanggal</td>
                        <td style="padding: 4px 0; font-weight: bold;">: {{ \Carbon\Carbon::parse($visit->tanggal_kunjungan)->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">Estimasi Jam</td>
                        <td style="padding: 4px 0; font-weight: bold;">: {{ $visit->jam_kunjungan }} WIB</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">Maksud Keperluan</td>
                        <td style="padding: 4px 0; font-weight: bold;">: {{ $visit->keperluan }}</td>
                    </tr>
                </table>
            </div>

            <p style="font-size: 11px; color: #64748b; line-height: 1.5; background: #f1f5f9; padding: 12px; border-radius: 8px; margin-bottom: 0;">
                💡 <em>Informasi Penting:</em> Email ini merupakan bukti bahwa registrasi Anda telah masuk ke antrean sistem. Anda akan menerima email konfirmasi lanjutan setelah admin meninjau kesiapan jadwal kunjungan Anda.
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 16px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8;">
            Email ini dikirimkan secara otomatis oleh Sistem Buku Tamu Digital RRI Bukittinggi.<br>
            &copy; {{ date('Y') }} LPP RRI Bukittinggi - Tim Teknologi & Media Baru.
        </div>
    </div>
</body>
</html>