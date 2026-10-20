<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Terima Kasih Kunjungan Anda - RRI Bukittinggi</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
        
        <!-- Header Instansi & Logo -->
        <div style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%); padding: 24px; text-align: center; color: #ffffff;">
            <img src="{{ url('images/logoo_rri.png') }}" alt="Logo RRI Bukittinggi" style="width: 65px; height: auto; margin-bottom: 8px; display: inline-block;">
        </div>

        <!-- Konten Utama -->
        <div style="padding: 24px; text-align: center;">
            <p style="font-size: 14px; font-weight: bold; color: #1e293b; margin-top: 0;">
                Halo, Bapak/Ibu <span style="color: #b45309;">{{ $visit->nama_lengkap }}</span>! 👋
            </p>
            
            <p style="font-size: 12px; color: #475569; line-height: 1.6; text-align: left;">
                Sesi kunjungan Anda ke LPP RRI Bukittinggi telah selesai dilaksanakan. Kami sangat mengapresiasi waktu, antusiasme, serta kerendahan hati Anda telah berkunjung ke kantor kami.
            </p>

            <p style="font-size: 12px; color: #475569; line-height: 1.6; text-align: left;">
                Untuk membantu kami terus meningkatkan kualitas pelayanan publik, mohon kesediaan Bapak/Ibu memberikan penilaian atau ulasan pelayanan melalui tombol di bawah ini:
            </p>

            <!-- Tombol CTA Ulasan langsung mengarah ke beranda dengan parameter email & anchor #penilaian-publik -->
            <div style="margin: 24px 0;">
                <a href="{{ route('home') }}?sumber=email#penilaian-publik" 
                style="background-color: #b45309; color: #ffffff; padding: 12px 28px; border-radius: 12px; font-size: 12px; font-weight: bold; text-decoration: none; display: inline-block; box-shadow: 0 4px 10px rgba(180, 83, 9, 0.3);">
                ⭐ Berikan Ulasan Pelayanan
                </a>
            </div>

            <p style="font-size: 10px; color: #94a3b8; font-style: italic; background: #fffbeb; padding: 10px; border-radius: 8px; border: 1px solid #fcd34d;">
                *Ulasan yang Anda kirimkan melalui tautan email ini akan otomatis tercatat ke dalam laporan rekapitulasi penilaian pelayanan RRI Bukittinggi dengan sumber <strong>Email</strong>.
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 16px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8;">
            &copy; {{ date('Y') }} LPP RRI Bukittinggi - Tim Teknologi & Media Baru.
        </div>
    </div>
</body>
</html>