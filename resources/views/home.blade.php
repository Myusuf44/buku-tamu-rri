<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu - RRI Bukittinggi</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Canvas Confetti CDN -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <style>
        html, body { 
            margin: 0 !important; 
            padding: 0 !important; 
        }
        body { font-family: 'Inter', sans-serif; }
        .font-mono-clock { font-family: 'JetBrains Mono', monospace; }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }

        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-modal-pop { animation: modalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes shakeError {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }
        .animate-shake { animation: shakeError 0.4s ease-in-out; }
        
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #f59e0b;
        }

        .swal2-popup.custom-swal-popup {
            border-radius: 2rem !important;
            padding: 2.25rem 1.75rem !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25) !important;
        }
        .swal2-title.custom-swal-title {
            font-size: 1.5rem !important;
            font-weight: 900 !important;
            color: #0f172a !important;
            letter-spacing: -0.025em !important;
            margin-top: 0.75rem !important;
        }
        .swal2-html-container.custom-swal-content {
            font-size: 0.875rem !important;
            color: #64748b !important;
            margin-top: 0.5rem !important;
            line-height: 1.6 !important;
        }
        .swal2-confirm.custom-swal-button {
            border-radius: 1rem !important;
            font-size: 0.875rem !important;
            font-weight: 800 !important;
            padding: 0.75rem 2.5rem !important;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            box-shadow: 0 10px 20px -5px rgba(245, 158, 11, 0.4) !important;
            border: none !important;
            transition: all 0.2s ease !important;
        }
        .swal2-confirm.custom-swal-button:hover {
            transform: scale(1.03) !important;
            box-shadow: 0 12px 24px -5px rgba(245, 158, 11, 0.5) !important;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 flex flex-col min-h-screen antialiased overflow-x-hidden overflow-y-auto">

    <!-- HEADER NAVBAR -->
    <header class="bg-slate-50/80 backdrop-blur-xl border-b border-blue-100/80 sticky top-0 z-50 shadow-sm w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- SISI KIRI: LOGO RRI & BADGE ADMIN -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 group">
                    <img src="{{ asset('images/logo_rri.svg') }}" alt="Logo RRI Bukittinggi" class="h-8 sm:h-9 w-auto group-hover:scale-105 transition-transform duration-300">
                    
                    @auth
                        <span class="hidden md:inline-block px-2.5 py-0.5 rounded-full bg-blue-100/80 border border-blue-200 text-[10px] font-extrabold uppercase tracking-wider text-blue-800">
                            Admin Panel
                        </span>
                    @endauth
                </a>

                <!-- SISI KANAN: SEMUA NAVIGASI & TOMBOL AKSI -->
                <div class="hidden md:flex items-center gap-2">
                    
                    <div class="p-1 bg-slate-200/60 rounded-full border border-slate-300/60 flex items-center gap-1">
                        <a href="{{ route('home') }}" class="px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-blue-600 text-white shadow-sm hover:bg-blue-700 transition-all flex items-center gap-1.5">
                            <i class="fas fa-home text-xs"></i>
                            <span>Beranda</span>
                        </a>

                        <a href="{{ route('registrasi') }}" class="px-4 py-1.5 rounded-full text-xs sm:text-sm font-medium text-slate-700 hover:text-blue-700 hover:bg-white/50 transition-all flex items-center gap-1.5">
                            <i class="fas fa-edit text-xs text-slate-500"></i>
                            <span>Buku Tamu</span>
                        </a>

                        @auth
                            <div class="h-4 w-[1px] bg-slate-300 mx-1"></div>
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-1.5 rounded-full text-xs sm:text-sm font-bold bg-slate-900 text-white shadow-sm hover:bg-slate-800 transition-all flex items-center gap-1.5">
                                <i class="fas fa-chart-pie text-xs text-sky-400"></i>
                                <span>Dashboard</span>
                            </a>
                            <button type="button" onclick="openLogoutModal()" class="px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-semibold text-rose-600 hover:bg-rose-50 transition-all flex items-center gap-1 cursor-pointer">
                                <i class="fas fa-sign-out-alt text-xs"></i>
                                <span>Keluar</span>
                            </button>
                        @endauth
                    </div>

                    <button type="button" onclick="scrollToFeedbackAndOpenModal()" class="px-3.5 py-1.5 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-300/80 rounded-full text-xs font-extrabold text-amber-800 transition-all shadow-sm flex items-center gap-1.5 cursor-pointer active:scale-95">
                        <i class="fas fa-star text-amber-500"></i>
                        <span>{{ $avgRatingFormat ?? '5.0' }}</span>
                        <span class="text-amber-700/80 font-semibold text-[11px] hidden lg:inline">Beri Ulasan</span>
                    </button>

                </div>

                <!-- TOMBOL HAMBURGER MOBILE -->
                <div class="flex items-center md:hidden gap-2">
                    @auth
                        <span class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold border border-blue-200">
                            Admin
                        </span>
                    @endauth
                    <button type="button" id="mobileMenuBtn" class="p-2 rounded-xl bg-slate-200/80 text-slate-700 hover:bg-slate-300 focus:outline-none">
                        <i class="fas fa-bars text-lg" id="mobileMenuIcon"></i>
                    </button>
                </div>

            </div>

            <!-- DROPDOWN MENU MOBILE -->
            <div id="mobileMenu" class="hidden md:hidden pb-4 pt-2 border-t border-slate-200/80 animate-fade-in-up">
                <div class="flex flex-col gap-2">
                    <a href="{{ route('home') }}" class="px-4 py-2.5 rounded-xl text-sm font-bold bg-blue-600 text-white flex items-center gap-2">
                        <i class="fas fa-home text-xs"></i>
                        <span>Beranda Utama</span>
                    </a>
                    <a href="{{ route('registrasi') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-700 bg-white border border-slate-200 flex items-center gap-2">
                        <i class="fas fa-edit text-xs text-slate-500"></i>
                        <span>Buku Tamu</span>
                    </a>
                    <button type="button" onclick="scrollToFeedbackAndOpenModal()" class="w-full text-left px-4 py-2.5 rounded-xl text-sm font-bold text-amber-900 bg-amber-50 border border-amber-200 flex items-center gap-2">
                        <i class="fas fa-star text-xs text-amber-500"></i>
                        <span>Beri Ulasan & Nilai Pelayanan</span>
                    </button>

                    @auth
                        <div class="my-1 border-t border-slate-200"></div>
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-xl text-sm font-bold bg-slate-900 text-white flex items-center gap-2">
                            <i class="fas fa-chart-pie text-xs text-sky-400"></i>
                            <span>Dashboard Admin</span>
                        </a>
                        <button type="button" onclick="openLogoutModal()" class="w-full px-4 py-2.5 rounded-xl text-sm font-bold text-rose-600 bg-rose-50 border border-rose-100 flex items-center gap-2">
                            <i class="fas fa-sign-out-alt text-xs"></i>
                            <span>Keluar dari Akun</span>
                        </button>
                    @endauth
                </div>
            </div>

        </div>
    </header>

    <!-- MAIN CONTENT AREA -->
    <main class="flex-grow">
        
        <!-- HERO SECTION UTAMA -->
        <div class="relative bg-slate-950 min-h-[560px] sm:min-h-[640px] flex items-center justify-center overflow-hidden pt-10 pb-28 sm:pt-14 sm:pb-40">
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/gedung-rri.png') }}" 
                     alt="Gedung RRI Bukittinggi" 
                     class="w-full h-full object-cover object-top sm:object-center opacity-75">
                <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-blue-950/60 to-slate-900/90"></div>
            </div>

            <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center flex flex-col items-center">
                <div class="mb-3 sm:mb-4 animate-fade-in-up">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-950/80 border border-blue-400/30 backdrop-blur-md shadow-md text-blue-300">
                        <i class="fas fa-layer-group text-blue-400 text-xs"></i>
                        <span class="text-[11px] sm:text-xs font-bold uppercase tracking-widest">Layanan Digital Kunjungan</span>
                    </div>
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight mb-3 sm:mb-4 animate-fade-in-up delay-100 drop-shadow-md">
                    Buku Tamu <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-sky-300 to-cyan-300">RRI Bukittinggi</span>
                </h1>

                <p class="max-w-2xl mx-auto text-xs sm:text-base text-slate-200 font-normal leading-relaxed mb-5 sm:mb-7 animate-fade-in-up delay-200 drop-shadow-sm px-2 sm:px-0">
                    Selamat datang di layanan pencatatan kunjungan digital terpadu Lembaga Penyiaran Publik Radio Republik Indonesia (RRI) Bukittinggi.
                </p>

                <!-- Widget Jam Realtime -->
                <div class="mb-6 sm:mb-8 animate-fade-in-up delay-200 w-full max-w-md mx-auto">
                    <div class="inline-flex items-center justify-center gap-2 sm:gap-3 px-3.5 py-2 rounded-2xl sm:rounded-full bg-slate-900/80 border border-blue-500/30 backdrop-blur-xl shadow-lg text-slate-200 text-xs sm:text-sm font-semibold">
                        <div class="flex items-center gap-1.5 text-slate-200">
                            <i class="far fa-calendar-alt text-sky-400 text-xs"></i>
                            <span id="clock-hari-tanggal" class="whitespace-nowrap">Rabu, 14 Okt 2026</span>
                        </div>
                        <span class="text-slate-600">•</span>
                        <div class="flex items-center gap-1 font-mono-clock font-bold text-cyan-300 bg-blue-950/90 px-2.5 py-0.5 rounded-full border border-blue-400/40 text-xs">
                            <i class="far fa-clock text-sky-400 text-[11px] mr-0.5"></i>
                            <span id="clock-jam-menit">00:00</span>
                            <span class="text-[10px] text-cyan-400">:</span>
                            <span id="clock-detik" class="text-[10px] text-cyan-400">00</span>
                            <span class="text-[9px] font-sans font-bold text-sky-300 ml-0.5">WIB</span>
                        </div>
                    </div>
                </div>

                <!-- Tombol CTA Utama & Tombol Beri Penilaian -->
                <div class="animate-fade-in-up delay-300 flex flex-col sm:flex-row items-center justify-center gap-3 w-full sm:w-auto px-4 sm:px-0">
                    <a href="{{ route('registrasi') }}" 
                       class="inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 via-blue-500 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 text-white font-extrabold text-sm sm:text-base shadow-xl shadow-blue-600/30 hover:shadow-blue-500/50 hover:scale-105 active:scale-95 transition-all duration-300 border border-blue-400/30 w-full sm:w-auto group">
                        <span>Isi Buku Tamu Sekarang</span>
                        <i class="fas fa-arrow-right text-xs sm:text-sm group-hover:translate-x-1.5 transition-transform duration-300"></i>
                    </a>

                    <button type="button" onclick="scrollToFeedbackAndOpenModal()"
                       class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-2xl bg-amber-500/15 hover:bg-amber-500/25 border-2 border-amber-400/60 hover:border-amber-300 backdrop-blur-md text-amber-300 font-extrabold text-sm sm:text-base shadow-lg shadow-amber-500/10 hover:scale-105 active:scale-95 transition-all duration-300 w-full sm:w-auto cursor-pointer group">
                        <i class="fas fa-star text-amber-400 group-hover:scale-125 transition-transform"></i>
                        <span>Beri Penilaian Pelayanan</span>
                    </button>
                </div>

            </div>
        </div>

        <!-- AREA ALUR PENGGUNAAN APLIKASI -->
        <div class="relative bg-gradient-to-b from-slate-200/90 via-slate-100 to-slate-50 pb-12 sm:pb-16 pt-3">
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 sm:-mt-14 relative z-20">
                
                <div class="text-center mb-7 sm:mb-9 animate-fade-in-up">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/95 border border-slate-200/90 shadow-md text-blue-800 text-xs font-extrabold uppercase tracking-wider backdrop-blur-md">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-pulse"></span>
                        Panduan Pengisian Buku Tamu
                    </span>
                </div>

                <div class="relative grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 text-left animate-fade-in-up delay-200">
                    
                    <div class="hidden md:block absolute top-[4.2rem] left-16 right-16 h-1 border-t-2 border-dashed border-blue-300/60 z-0"></div>

                    <!-- STEP 01 -->
                    <div class="relative group bg-white p-6 sm:p-8 rounded-3xl shadow-xl shadow-slate-300/50 border border-slate-200/90 hover:border-blue-500/50 hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-1.5 transition-all duration-300 z-10 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-5 sm:mb-6">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-tr from-blue-700 to-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-600/30 shrink-0 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-mouse-pointer text-base sm:text-xl text-white"></i>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-black tracking-widest bg-blue-50 text-blue-700 border border-blue-100/80 shrink-0 shadow-xs">01</span>
                            </div>
                            <h3 class="text-base sm:text-lg font-extrabold text-slate-900 mb-2 sm:mb-2.5 group-hover:text-blue-600 transition-colors">
                                Klik & Isi Data Diri
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                Klik tombol <span class="font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">"Isi Buku Tamu"</span>, lalu lengkapi Nama, No. WhatsApp, Email, dan Asal Instansi/Alamat Anda.
                            </p>
                        </div>
                        <div class="w-full h-1.5 bg-gradient-to-r from-blue-600 to-blue-400 rounded-full mt-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>

                    <!-- STEP 02 -->
                    <div class="relative group bg-white p-6 sm:p-8 rounded-3xl shadow-xl shadow-slate-300/50 border border-slate-200/90 hover:border-sky-500/50 hover:shadow-2xl hover:shadow-sky-500/10 hover:-translate-y-1.5 transition-all duration-300 z-10 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-5 sm:mb-6">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-tr from-sky-600 to-sky-400 text-white flex items-center justify-center shadow-lg shadow-sky-500/30 shrink-0 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-calendar-alt text-base sm:text-xl text-white"></i>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-black tracking-widest bg-sky-50 text-sky-700 border border-sky-100/80 shrink-0 shadow-xs">02</span>
                            </div>
                            <h3 class="text-base sm:text-lg font-extrabold text-slate-900 mb-2 sm:mb-2.5 group-hover:text-sky-600 transition-colors">
                                Atur Jadwal & Tujuan
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                Pilih <span class="font-semibold text-slate-800 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">Status Kunjungan</span>, <span class="font-semibold text-slate-800 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">Divisi Tujuan</span>, <span class="font-semibold text-slate-800 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">Tanggal & Jam</span>, serta isi maksud <span class="font-semibold text-slate-800 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">Keperluan</span> Anda.
                            </p>
                        </div>
                        <div class="w-full h-1.5 bg-gradient-to-r from-sky-500 to-sky-300 rounded-full mt-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>

                    <!-- STEP 03 -->
                    <div class="relative group bg-white p-6 sm:p-8 rounded-3xl shadow-xl shadow-slate-300/50 border border-slate-200/90 hover:border-cyan-500/50 hover:shadow-2xl hover:shadow-cyan-500/10 hover:-translate-y-1.5 transition-all duration-300 z-10 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-5 sm:mb-6">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-tr from-cyan-600 to-cyan-400 text-white flex items-center justify-center shadow-lg shadow-cyan-500/30 shrink-0 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-paper-plane text-base sm:text-xl text-white"></i>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-black tracking-widest bg-cyan-50 text-cyan-700 border border-cyan-100/80 shrink-0 shadow-xs">03</span>
                            </div>
                            <h3 class="text-base sm:text-lg font-extrabold text-slate-900 mb-2 sm:mb-2.5 group-hover:text-cyan-600 transition-colors">
                                Cek Status via Email
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                Kirim data Anda. Notifikasi konfirmasi akan dikirim ke <span class="font-semibold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">WhatsApp</span> atau <span class="font-semibold text-blue-700 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">Email</span> Anda (Admin dapat menghubungi via WA).
                            </p>
                        </div>
                        <div class="w-full h-1.5 bg-gradient-to-r from-cyan-600 to-cyan-400 rounded-full mt-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>

                </div>
            </div>
        </div>

        <!-- SEKSI RATING & ULASAN PUBLIK (IKM) -->
        <div id="penilaian-publik" class="relative bg-gradient-to-b from-slate-50 via-white to-slate-100 py-10 sm:py-14 border-t border-slate-200/80 overflow-hidden">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center max-w-4xl mx-auto mb-8">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-amber-500/10 to-orange-500/10 border border-amber-200 text-amber-700 text-xs font-bold uppercase tracking-wider mb-2 shadow-sm">
                        <i class="fas fa-award text-amber-500 animate-bounce"></i>
                        <span>Indeks Kepuasan Masyarakat (IKM)</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                        Penilaian & Kesan Pengunjung
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1.5 leading-relaxed">
                        Seluruh ulasan dan penilaian langsung dari masyarakat terhadap kualitas pelayanan di Lembaga Penyiaran Publik Radio Republik Indonesia Bukittinggi.
                    </p>

                    @php
                        $score = (float)($avgRating ?? 5.0);
                        if ($score >= 4.25) {
                            $mutuLabel = "Sangat Baik (A)";
                            $mutuColor = "text-emerald-600";
                            $mutuBg = "bg-emerald-50 text-emerald-600 border-emerald-100";
                            $mutuIcon = "fa-shield-heart";
                            $mutuSub = "Pelayanan Prima";
                        } elseif ($score >= 3.50) {
                            $mutuLabel = "Baik (B)";
                            $mutuColor = "text-blue-600";
                            $mutuBg = "bg-blue-50 text-blue-600 border-blue-100";
                            $mutuIcon = "fa-thumbs-up";
                            $mutuSub = "Pelayanan Memuaskan";
                        } elseif ($score >= 2.75) {
                            $mutuLabel = "Kurang Baik (C)";
                            $mutuColor = "text-amber-600";
                            $mutuBg = "bg-amber-50 text-amber-600 border-amber-100";
                            $mutuIcon = "fa-triangle-exclamation";
                            $mutuSub = "Perlu Peningkatan";
                        } else {
                            $mutuLabel = "Tidak Baik (D)";
                            $mutuColor = "text-rose-600";
                            $mutuBg = "bg-rose-50 text-rose-600 border-rose-100";
                            $mutuIcon = "fa-triangle-exclamation";
                            $mutuSub = "Evaluasi Total";
                        }
                    @endphp

                    <!-- KARTU RINGKASAN STATISTIK -->
                    <div class="mt-6 bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-7 shadow-md grid grid-cols-1 md:grid-cols-3 gap-6 items-center text-left">
                        
                        <!-- Kolom 1: Skor Utama & Total Ulasan -->
                        <div class="flex flex-col items-center md:items-start text-center md:text-left border-b md:border-b-0 md:border-r border-slate-100 pb-5 md:pb-0 md:pr-6">
                            <div class="text-4xl sm:text-5xl font-black text-slate-900 leading-none">
                                {{ number_format($avgRating ?? 5.0, 1) }}
                                <span class="text-base text-slate-400 font-bold">/ 5.0</span>
                            </div>
                            <div class="flex text-amber-400 text-base my-2 gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= round($avgRating ?? 5) ? 'text-amber-400 drop-shadow-sm' : 'text-slate-200' }}"></i>
                                @endfor
                            </div>
                            <p class="text-xs font-semibold text-slate-500">
                                Berdasarkan <span class="font-extrabold text-slate-800">{{ $totalResponden ?? count($publicFeedbacks ?? []) }} Total Ulasan</span> Terverifikasi <span class="text-[10px] text-slate-400 block sm:inline">(Menampilkan 50 Data Terbaru)</span>
                            </p>
                        </div>

                        <!-- Kolom 2: Breakdown Rating 1 - 5 Bintang -->
                        <div class="space-y-2.5 text-xs font-medium text-slate-600 border-b md:border-b-0 md:border-r border-slate-100 pb-5 md:pb-0 md:pr-6 w-full">
                            
                            <!-- 5 Bintang -->
                            <div class="flex items-center gap-3">
                                <div class="w-20 shrink-0 flex items-center justify-between px-2 py-0.5 bg-amber-50/80 border border-amber-200/60 rounded-lg text-amber-900 font-bold">
                                    <span>5</span>
                                    <i class="fas fa-star text-amber-400 text-[10px]"></i>
                                </div>
                                <div class="flex-grow h-2.5 bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200/50 shadow-inner">
                                    <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full transition-all duration-500" style="width: {{ $percent5 ?? 0 }}%;"></div>
                                </div>
                                <span class="w-10 shrink-0 text-right text-slate-600 font-bold">{{ $percent5 ?? 0 }}%</span>
                            </div>

                            <!-- 4 Bintang -->
                            <div class="flex items-center gap-3">
                                <div class="w-20 shrink-0 flex items-center justify-between px-2 py-0.5 bg-slate-50 border border-slate-200/80 rounded-lg text-slate-700 font-bold">
                                    <span>4</span>
                                    <i class="fas fa-star text-amber-400 text-[10px]"></i>
                                </div>
                                <div class="flex-grow h-2.5 bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200/50 shadow-inner">
                                    <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full transition-all duration-500" style="width: {{ $percent4 ?? 0 }}%;"></div>
                                </div>
                                <span class="w-10 shrink-0 text-right text-slate-600 font-bold">{{ $percent4 ?? 0 }}%</span>
                            </div>

                            <!-- 3 Bintang -->
                            <div class="flex items-center gap-3">
                                <div class="w-20 shrink-0 flex items-center justify-between px-2 py-0.5 bg-slate-50 border border-slate-200/80 rounded-lg text-slate-700 font-bold">
                                    <span>3</span>
                                    <i class="fas fa-star text-amber-400 text-[10px]"></i>
                                </div>
                                <div class="flex-grow h-2.5 bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200/50 shadow-inner">
                                    <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full transition-all duration-500" style="width: {{ $percent3 ?? 0 }}%;"></div>
                                </div>
                                <span class="w-10 shrink-0 text-right text-slate-600 font-bold">{{ $percent3 ?? 0 }}%</span>
                            </div>

                            <!-- 2 Bintang -->
                            <div class="flex items-center gap-3">
                                <div class="w-20 shrink-0 flex items-center justify-between px-2 py-0.5 bg-slate-50 border border-slate-200/80 rounded-lg text-slate-700 font-bold">
                                    <span>2</span>
                                    <i class="fas fa-star text-amber-400 text-[10px]"></i>
                                </div>
                                <div class="flex-grow h-2.5 bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200/50 shadow-inner">
                                    <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full transition-all duration-500" style="width: {{ $percent2 ?? 0 }}%;"></div>
                                </div>
                                <span class="w-10 shrink-0 text-right text-slate-600 font-bold">{{ $percent2 ?? 0 }}%</span>
                            </div>

                            <!-- 1 Bintang -->
                            <div class="flex items-center gap-3">
                                <div class="w-20 shrink-0 flex items-center justify-between px-2 py-0.5 bg-slate-50 border border-slate-200/80 rounded-lg text-slate-700 font-bold">
                                    <span>1</span>
                                    <i class="fas fa-star text-amber-400 text-[10px]"></i>
                                </div>
                                <div class="flex-grow h-2.5 bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200/50 shadow-inner">
                                    <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full transition-all duration-500" style="width: {{ $percent1 ?? 0 }}%;"></div>
                                </div>
                                <span class="w-10 shrink-0 text-right text-slate-600 font-bold">{{ $percent1 ?? 0 }}%</span>
                            </div>

                        </div>

                        <!-- Kolom 3: Mutu Pelayanan & Tombol Tulis Ulasan -->
                        <div class="flex flex-col items-center justify-center text-center">
                            <div class="w-11 h-11 rounded-2xl {{ $mutuBg }} border flex items-center justify-center mb-1.5 shadow-sm transition-all duration-300">
                                <i class="fas {{ $mutuIcon }} text-lg"></i>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Mutu Pelayanan</span>
                            <span class="text-base font-black {{ $mutuColor }} transition-colors duration-300">{{ $mutuLabel }}</span>
                            <span class="text-[10px] font-semibold text-slate-400 mt-0.5 mb-2.5">{{ $mutuSub }} RRI Bukittinggi</span>
                            
                            <button type="button" onclick="openFeedbackModal()" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <i class="fas fa-pen-to-square text-amber-300"></i>
                                <span>Tulis Ulasan Anda</span>
                            </button>
                        </div>

                    </div>
                </div>

                <!-- CONTAINER GRID ULASAN -->
                <div id="reviewCarousel" class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6 transition-opacity duration-700 opacity-100">
                    @forelse($publicFeedbacks ?? [] as $index =>$fb)
                        <div class="feedback-slide {{ $index >= 3 ? 'hidden' : '' }} feedback-card p-5 sm:p-6 rounded-3xl border border-slate-200/80 bg-white flex flex-col justify-between relative group shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1" data-index="{{ $index }}">
                            <div class="absolute top-4 right-5 text-slate-200/60 text-4xl font-serif select-none group-hover:text-blue-100 transition-colors">
                                &ldquo;
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-3 relative z-10">
                                    <div class="flex text-amber-400 text-xs gap-1 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-100">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <=$fb->rating ? 'text-amber-500' : 'text-slate-300' }}"></i>
                                        @endfor
                                        <span class="font-bold text-amber-700 ml-1 text-[11px]">{{ $fb->rating_format }}</span>
                                    </div>
                                    <span class="text-[10px] font-semibold text-slate-400 bg-slate-100 px-2.5 py-0.5 rounded-full">
                                        {{ $fb->waktu_lalu }}
                                    </span>
                                </div>

                                <p class="text-xs sm:text-sm text-slate-700 font-medium italic leading-relaxed mb-5 relative z-10">
                                    "{{ $fb->ulasan }}"
                                </p>
                            </div>

                            <div class="pt-3.5 border-t border-slate-100 flex items-center justify-between relative z-10">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-2xl bg-gradient-to-tr from-blue-600 to-sky-400 text-white font-extrabold text-xs flex items-center justify-center shadow-md shadow-blue-500/20">
                                        {{ $fb->inisial }}
                                    </div>
                                    <div>
                                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors">
                                            {{ $fb->nama_tampil }}
                                        </h4>
                                        <span class="text-[10px] font-medium text-slate-400 block">Tamu LPP RRI</span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200 shadow-sm">
                                    <i class="fas fa-check-circle text-[9px]"></i> Terverifikasi
                                </span>
                            </div>

                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 bg-white rounded-3xl border border-dashed border-slate-300 shadow-sm">
                            <div class="w-14 h-14 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3 text-xl shadow-inner">
                                <i class="fas fa-comment-dots"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800">Belum Ada Ulasan Publik</h3>
                            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Jadilah pengunjung pertama yang memberikan penilaian pelayanan!</p>
                            <button type="button" onclick="openFeedbackModal()" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition cursor-pointer">
                                <i class="fas fa-star text-amber-300"></i>
                                <span>Beri Ulasan Pertama</span>
                            </button>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <div>
                <p class="text-xs sm:text-sm font-semibold text-slate-300">&copy; {{ date('Y') }} LPP RRI Bukittinggi</p>
                <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5">Lembaga Penyiaran Publik Radio Republik Indonesia - Bukittinggi</p>
            </div>
            <div class="text-xs text-slate-500">
                <span>Sistem Buku Tamu Digital TMB</span>
            </div>
        </div>
    </footer>

    <!-- MODAL FORM INPUT PENILAIAN PELAYANAN PUBLIK -->
    <div id="feedbackModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 relative animate-modal-pop">
            
            <button type="button" onclick="closeFeedbackModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 font-bold w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all cursor-pointer">
                &times;
            </button>

            <div class="text-center mb-5">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 border border-amber-100 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-star text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-slate-900">Penilaian Pelayanan Tamu</h3>
                <p class="text-xs text-slate-500 mt-1">Berikan masukan dan rating kepuasan atas pelayanan RRI Bukittinggi</p>
            </div>

            <form id="feedbackForm" action="{{ route('feedback.store') }}" method="POST" class="space-y-4" novalidate onsubmit="return handleFeedbackSubmit(event)">
                @csrf

                <!-- INPUT HIDDEN SUMBER -->
                <input type="hidden" name="sumber" id="inputSumberFeedback" value="Beranda">

                <!-- BANNER INDIKATOR SUMBER -->
                <div id="bannerSumberFeedback" class="hidden items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold border"></div>

                <!-- BAGIAN BINTANG RATING -->
                <div id="starContainer" class="p-3 rounded-2xl transition-all">
                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-2 text-center">Tingkat Kepuasan Anda <span class="text-rose-500">*</span></label>
                    <div class="flex items-center justify-center gap-2 flex-row-reverse star-rating text-3xl text-slate-300">
                        <input type="radio" id="star5" name="rating" value="5" class="hidden" />
                        <label for="star5" class="cursor-pointer transition-colors"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star4" name="rating" value="4" class="hidden" />
                        <label for="star4" class="cursor-pointer transition-colors"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star3" name="rating" value="3" class="hidden" />
                        <label for="star3" class="cursor-pointer transition-colors"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star2" name="rating" value="2" class="hidden" />
                        <label for="star2" class="cursor-pointer transition-colors"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star1" name="rating" value="1" class="hidden" />
                        <label for="star1" class="cursor-pointer transition-colors"><i class="fas fa-star"></i></label>
                    </div>

                    <p id="starErrorText" class="hidden text-[11px] text-rose-500 font-bold text-center mt-2 flex items-center justify-center gap-1">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Silakan klik salah satu bintang rating di atas terlebih dahulu!</span>
                    </p>
                </div>

                <!-- NAMA LENGKAP -->
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Nama Lengkap Anda <span class="text-rose-500">*</span></label>
                    <input type="text" id="feedback_nama" name="nama_lengkap" placeholder="Masukkan nama Anda..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition-all">
                    <p class="error-msg-feedback hidden text-[11px] font-semibold text-rose-600 mt-1.5 flex items-center gap-1.5 animate-fade-in-up">
                        <i class="fas fa-circle-exclamation text-rose-500"></i>
                        <span>Mohon isi nama lengkap Anda terlebih dahulu.</span>
                    </p>
                </div>

                <!-- TELEPON & EMAIL -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">
                            Telepon/WhatsApp (Aktif) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="feedback_hp" name="no_hp" placeholder="08xxxxxxxxxx" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition-all">
                        <p class="error-msg-feedback hidden text-[11px] font-semibold text-rose-600 mt-1.5 flex items-center gap-1.5 animate-fade-in-up">
                            <i class="fas fa-circle-exclamation text-rose-500"></i>
                            <span>Nomor WhatsApp wajib diisi.</span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Email (Opsional)</label>
                        <input type="email" id="feedback_email" name="email" placeholder="email@contoh.com" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition-all">
                        <p class="error-msg-feedback hidden text-[11px] font-semibold text-rose-600 mt-1.5 flex items-center gap-1.5 animate-fade-in-up">
                            <i class="fas fa-circle-exclamation text-rose-500"></i>
                            <span>Format email tidak valid.</span>
                        </p>
                    </div>
                </div>

                <!-- ULASAN -->
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Ulasan & Kesan Pelayanan <span class="text-rose-500">*</span></label>
                    <textarea id="feedback_ulasan" name="ulasan" rows="3" placeholder="Tuliskan masukan atau pengalaman pelayanan Anda..." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition-all"></textarea>
                    <p class="error-msg-feedback hidden text-[11px] font-semibold text-rose-600 mt-1.5 flex items-center gap-1.5 animate-fade-in-up">
                        <i class="fas fa-circle-exclamation text-rose-500"></i>
                        <span>Mohon tuliskan ulasan atau kesan pelayanan Anda.</span>
                    </p>
                </div>

                <div class="pt-2">
                    <button type="submit" id="btnSubmitFeedback" class="w-full py-3 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 active:scale-95 text-white font-extrabold text-xs shadow-lg shadow-amber-500/25 transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <i id="iconSubmitFeedback" class="fas fa-paper-plane"></i>
                        <span id="textSubmitFeedback">Kirim Penilaian Pelayanan</span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- MODAL KONFIRMASI LOGOUT ADMIN -->
    @auth
    <div id="logoutModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100 relative animate-modal-pop">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 shadow-sm">
                <i class="fas fa-sign-out-alt text-2xl"></i>
            </div>
            <div class="text-center mb-6">
                <h3 class="text-xl font-extrabold text-slate-900">Konfirmasi Keluar Akun</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed">
                    Apakah Anda yakin ingin keluar dari <span class="font-bold text-slate-800">Sistem Admin Buku Tamu</span>?
                </p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <button type="button" onclick="closeLogoutModal()" class="w-full py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs sm:text-sm hover:bg-slate-100 active:scale-95 transition-all cursor-pointer">
                    Batal
                </button>
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-rose-600/30 active:scale-95 transition-all cursor-pointer">
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endauth

    <!-- SCRIPT INTERAKTIF & LOGIK VALIDASI KUSTOM UI/UX -->
    <script>
        function updateRealtimeClock() {
            const hariEl = document.getElementById('clock-hari-tanggal');
            const jamEl = document.getElementById('clock-jam-menit');
            const detikEl = document.getElementById('clock-detik');

            if (!jamEl) return;
            const now = new Date();
            const opsiHari = { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric', timeZone: 'Asia/Jakarta' };
            if (hariEl) hariEl.textContent = now.toLocaleDateString('id-ID', opsiHari);

            const jam = String(now.getHours()).padStart(2, '0');
            const menit = String(now.getMinutes()).padStart(2, '0');
            const detik = String(now.getSeconds()).padStart(2, '0');

            jamEl.textContent = `${jam}:${menit}`;
            if (detikEl) detikEl.textContent = detik;
        }

        function openLogoutModal() {
            const modal = document.getElementById('logoutModal');
            if (modal) modal.classList.remove('hidden');
        }
        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            if (modal) modal.classList.add('hidden');
        }

        function openFeedbackModal() {
            const modal = document.getElementById('feedbackModal');
            const inputSumber = document.getElementById('inputSumberFeedback');
            const bannerSumber = document.getElementById('bannerSumberFeedback');

            if (modal) {
                modal.classList.remove('hidden');

                const urlParams = new URLSearchParams(window.location.search);
                const sumberParam = urlParams.get('sumber');

                if (sumberParam === 'whatsapp' || sumberParam === 'wa') {
                    if (inputSumber) inputSumber.value = 'whatsapp';
                    if (bannerSumber) {
                        bannerSumber.className = "flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200";
                        bannerSumber.innerHTML = '<i class="fab fa-whatsapp"></i> Memberikan Ulasan via Tautan WhatsApp';
                        bannerSumber.classList.remove('hidden');
                        bannerSumber.classList.add('flex');
                    }
                } else if (sumberParam === 'email') {
                    if (inputSumber) inputSumber.value = 'email';
                    if (bannerSumber) {
                        bannerSumber.className = "flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200";
                        bannerSumber.innerHTML = '<i class="far fa-envelope"></i> Memberikan Ulasan via Tautan Email';
                        bannerSumber.classList.remove('hidden');
                        bannerSumber.classList.add('flex');
                    }
                } else {
                    if (inputSumber) inputSumber.value = 'Beranda';
                    if (bannerSumber) {
                        bannerSumber.classList.add('hidden');
                        bannerSumber.classList.remove('flex');
                    }
                }
            }
        }

        function closeFeedbackModal() {
            const modal = document.getElementById('feedbackModal');
            if (modal) {
                modal.classList.add('hidden');
                
                if (window.location.search.includes('sumber=')) {
                    const cleanUrl = window.location.pathname + window.location.hash;
                    window.history.replaceState({}, document.title, cleanUrl);
                }
            }
        }

        function scrollToFeedbackAndOpenModal() {
            const menu = document.getElementById('mobileMenu');
            const icon = document.getElementById('mobileMenuIcon');
            if (menu && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
                if (icon) {
                    icon.classList.remove('fa-xmark');
                    icon.classList.add('fa-bars');
                }
            }

            const section = document.getElementById('penilaian-publik');
            if (section) {
                section.scrollIntoView({ behavior: 'smooth' });
                setTimeout(() => {
                    openFeedbackModal();
                }, 450);
            } else {
                openFeedbackModal();
            }
        }

        // FUNGSI VALIDASI KUSTOM UI/UX MODAL FEEDBACK DENGAN CEK FORMAT NO HP & EMAIL
        function handleFeedbackSubmit(event) {
            event.preventDefault(); // Mencegah submit default
            let isValid = true;
            let firstInvalid = null;
            let errorMessage = "";

            // 1. Validasi Bintang Rating
            const starSelected = document.querySelector('input[name="rating"]:checked');
            const starContainer = document.getElementById('starContainer');
            const starErrorText = document.getElementById('starErrorText');

            if (!starSelected) {
                isValid = false;
                starContainer.classList.add('bg-rose-50', 'border', 'border-rose-200', 'animate-shake');
                starErrorText.classList.remove('hidden');
                errorMessage = "Silakan klik salah satu bintang tingkat kepuasan terlebih dahulu!";
                setTimeout(() => {
                    starContainer.classList.remove('animate-shake');
                }, 400);
            } else {
                starContainer.classList.remove('bg-rose-50', 'border', 'border-rose-200');
                starErrorText.classList.add('hidden');
            }

            // 2. Validasi Input Wajib (Nama, No HP, Ulasan)
            const requiredFields = ['feedback_nama', 'feedback_hp', 'feedback_ulasan'];
            requiredFields.forEach(fieldId => {
                const input = document.getElementById(fieldId);
                const parent = input.closest('div');
                const errorMsg = parent.querySelector('.error-msg-feedback');

                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('border-rose-500', 'bg-rose-50/30', 'animate-shake');
                    input.classList.remove('border-slate-200', 'bg-slate-50');
                    if (errorMsg) errorMsg.classList.remove('hidden');
                    
                    if (!firstInvalid) firstInvalid = input;
                    if (!errorMessage) errorMessage = "Mohon lengkapi semua kolom bertanda bintang merah (*).";

                    setTimeout(() => {
                        input.classList.remove('animate-shake');
                    }, 400);
                } else {
                    input.classList.remove('border-rose-500', 'bg-rose-50/30');
                    input.classList.add('border-slate-200', 'bg-slate-50');
                    if (errorMsg) errorMsg.classList.add('hidden');
                }
            });

            // 3. Validasi Format Nomor Telepon / WhatsApp (Harus angka & minimal 10 digit)
            const hpInput = document.getElementById('feedback_hp');
            const phoneRegex = /^[0-9+\-\s]{10,15}$/;
            if (hpInput.value.trim() && !phoneRegex.test(hpInput.value.trim())) {
                isValid = false;
                hpInput.classList.add('border-rose-500', 'bg-rose-50/30', 'animate-shake');
                const hpError = hpInput.closest('div').querySelector('.error-msg-feedback');
                if (hpError) {
                    hpError.querySelector('span').textContent = "Format nomor WhatsApp tidak valid (minimal 10 digit angka).";
                    hpError.classList.remove('hidden');
                }
                if (!firstInvalid) firstInvalid = hpInput;
                errorMessage = "Format nomor WhatsApp yang Anda masukkan salah atau belum lengkap (minimal 10 digit angka).";
            }

            // 4. Validasi Format Email (Opsional, tapi jika diisi harus benar)
            const emailInput = document.getElementById('feedback_email');
            const emailVal = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailVal !== "" && !emailRegex.test(emailVal)) {
                isValid = false;
                emailInput.classList.add('border-rose-500', 'bg-rose-50/30', 'animate-shake');
                const emailError = emailInput.closest('div').querySelector('.error-msg-feedback');
                if (emailError) {
                    emailError.classList.remove('hidden');
                }
                if (!firstInvalid) firstInvalid = emailInput;
                errorMessage = "Format alamat email yang Anda masukkan salah (contoh: email@gmail.com).";
            }

            // Jika ada data yang tidak valid, tampilkan popup SweetAlert peringatan
            if (!isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: errorMessage,
                    confirmButtonText: 'Periksa Kembali',
                    customClass: {
                        popup: 'custom-swal-popup',
                        title: 'custom-swal-title',
                        htmlContainer: 'custom-swal-content',
                        confirmButton: 'custom-swal-button'
                    },
                    buttonsStyling: false
                });
                return false;
            }

            // Jika valid, ubah tombol ke mode loading lalu submit form ke database
            const btn = document.getElementById('btnSubmitFeedback');
            const icon = document.getElementById('iconSubmitFeedback');
            const text = document.getElementById('textSubmitFeedback');

            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            icon.className = 'fas fa-spinner fa-spin';
            text.textContent = 'Menyimpan Penilaian...';

            document.getElementById('feedbackForm').submit();
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateRealtimeClock();
            setInterval(updateRealtimeClock, 1000);

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('sumber') || window.location.hash === '#penilaian-publik') {
                const section = document.getElementById('penilaian-publik');
                if (section) {
                    section.scrollIntoView({ behavior: 'smooth' });
                }
            }

            // Reset error bintang saat diklik
            document.querySelectorAll('input[name="rating"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const starContainer = document.getElementById('starContainer');
                    const starErrorText = document.getElementById('starErrorText');
                    
                    if (starContainer && starErrorText) {
                        starContainer.classList.remove('bg-rose-50', 'border', 'border-rose-200');
                        starErrorText.classList.add('hidden');
                    }
                });
            });

            // Live-feedback pembersihan error saat mengetik
            ['feedback_nama', 'feedback_hp', 'feedback_email', 'feedback_ulasan'].forEach(fieldId => {
                const input = document.getElementById(fieldId);
                if (input) {
                    input.addEventListener('input', function() {
                        const parent = input.closest('div');
                        const errorMsg = parent.querySelector('.error-msg-feedback');
                        if (input.value.trim()) {
                            input.classList.remove('border-rose-500', 'bg-rose-50/30');
                            input.classList.add('border-slate-200', 'bg-slate-50');
                            if (errorMsg) errorMsg.classList.add('hidden');
                        }
                    });
                }
            });

            const btn = document.getElementById('mobileMenuBtn');
            const menu = document.getElementById('mobileMenu');
            const icon = document.getElementById('mobileMenuIcon');

            if (btn && menu && icon) {
                btn.addEventListener('click', function() {
                    menu.classList.toggle('hidden');
                    if (menu.classList.contains('hidden')) {
                        icon.classList.remove('fa-xmark');
                        icon.classList.add('fa-bars');
                    } else {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-xmark');
                    }
                });
            }

            const slides = document.querySelectorAll('.feedback-slide');
            if (slides.length > 3) {
                let currentIndex = 0;
                const itemsPerPage = 3;

                setInterval(() => {
                    const container = document.getElementById('reviewCarousel');
                    if (!container) return;

                    container.classList.add('opacity-0');

                    setTimeout(() => {
                        currentIndex = (currentIndex + itemsPerPage) % slides.length;

                        slides.forEach((slide, idx) => {
                            slide.classList.add('hidden');
                            if (idx >= currentIndex && idx < currentIndex + itemsPerPage) {
                                slide.classList.remove('hidden');
                            }
                        });

                        if (currentIndex + itemsPerPage > slides.length) {
                            const remaining = (currentIndex + itemsPerPage) - slides.length;
                            slides.forEach((slide, idx) => {
                                if (idx < remaining) {
                                    slide.classList.remove('hidden');
                                }
                            });
                        }

                        container.classList.remove('opacity-0');
                    }, 400);

                }, 6000);
            }
        });
    </script>

    <!-- NOTIFIKASI POP-UP CONFETTI KETIKA BERHASIL -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof confetti === 'function') {
                    confetti({
                        particleCount: 80,
                        spread: 70,
                        origin: { y: 0.6 },
                        colors: ['#3b82f6', '#f59e0b', '#10b981', '#06b6d4']
                    });
                }

                Swal.fire({
                    html: `
                        <div class="flex flex-col items-center text-center">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200/80 text-blue-700 text-[11px] font-extrabold uppercase tracking-widest mb-3">
                                <i class="fas fa-check-circle text-blue-600"></i>
                                <span>LPP RRI Bukittinggi</span>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Penilaian Terkirim!</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed max-w-xs">
                                {{ session('success') }}
                            </p>
                        </div>
                    `,
                    icon: 'success',
                    iconColor: '#10b981',
                    showConfirmButton: true,
                    confirmButtonText: 'Selesai & Mengerti',
                    customClass: {
                        popup: 'custom-swal-popup',
                        confirmButton: 'custom-swal-button'
                    },
                    buttonsStyling: false
                });
            });
        </script>
    @endif
</body>
</html>