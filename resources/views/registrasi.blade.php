<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Buku Tamu - RRI Bukittinggi</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        html, body { 
            margin: 0 !important; 
            padding: 0 !important; 
        }
        body { font-family: 'Inter', sans-serif; }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-modal-pop { animation: modalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 flex flex-col min-h-screen antialiased overflow-x-hidden selection:bg-blue-600 selection:text-white">

    <!-- HEADER NAVBAR RESPONSIF -->
    <header class="bg-slate-50/90 backdrop-blur-md border-b border-blue-100/80 sticky top-0 z-50 shadow-sm w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Logo RRI & Badge Admin Panel -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 group">
                    <img src="{{ asset('images/logo_rri.svg') }}" alt="Logo RRI Bukittinggi" class="h-8 sm:h-9 w-auto group-hover:scale-105 transition-transform duration-300">
                    
                    @auth
                        <span class="hidden md:inline-block px-2.5 py-0.5 rounded-full bg-blue-100/80 border border-blue-200 text-[10px] font-extrabold uppercase tracking-wider text-blue-800">
                            Admin Panel
                        </span>
                    @endauth
                </a>

                <!-- NAVIGASI DESKTOP VIEW -->
                <nav class="hidden md:flex items-center gap-2">
                    <div class="p-1 bg-slate-200/60 rounded-full border border-slate-300/60 flex items-center gap-1">
                        <!-- Menu Beranda -->
                        <a href="{{ route('home') }}" class="px-4 py-1.5 rounded-full text-xs sm:text-sm font-medium text-slate-700 hover:text-blue-700 hover:bg-white/50 transition-all flex items-center gap-1.5">
                            <i class="fas fa-home text-xs text-slate-500"></i>Beranda
                        </a>

                        <!-- Menu Buku Tamu (Active State) -->
                        <a href="{{ route('registrasi') }}" class="px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-blue-600 text-white shadow-sm hover:bg-blue-700 transition-all flex items-center gap-1.5">
                            <i class="fas fa-edit text-xs"></i>Buku Tamu
                        </a>

                        <!-- JIKA USER ADMIN -->
                        @auth
                            <div class="h-4 w-[1px] bg-slate-300 mx-0.5"></div>
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-1.5 rounded-full text-xs sm:text-sm font-bold bg-slate-900 text-white shadow-sm hover:bg-slate-800 transition-all flex items-center gap-1.5">
                                <i class="fas fa-chart-pie text-xs text-sky-400"></i>
                                <span>Dashboard</span>
                            </a>
                            <button type="button" onclick="openLogoutModal()" class="px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-semibold text-rose-600 hover:bg-rose-50 transition-all flex items-center gap-1">
                                <i class="fas fa-sign-out-alt text-xs"></i>
                                <span>Keluar</span>
                            </button>
                        @endauth
                    </div>
                </nav>

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
                    <a href="{{ route('home') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-700 bg-white border border-slate-200 flex items-center gap-2">
                        <i class="fas fa-home text-xs text-slate-500"></i>Beranda Utama
                    </a>
                    <a href="{{ route('registrasi') }}" class="px-4 py-2.5 rounded-xl text-sm font-bold bg-blue-600 text-white flex items-center gap-2">
                        <i class="fas fa-edit text-xs"></i>Buku Tamu
                    </a>

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

    <!-- KONTEN UTAMA FORMULIR BUKU TAMU -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
        
        <!-- Tombol Kembali Ke Beranda -->
        <div class="mb-6 animate-fade-in-up">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-blue-600 bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-sm transition-all">
                <i class="fas fa-arrow-left text-[10px]"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        <!-- Judul & Subjudul -->
        <div class="mb-8 animate-fade-in-up">
            <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Formulir Buku Tamu</h1>
            <p class="text-xs sm:text-base text-slate-500 mt-1">Silakan lengkapi data kunjungan Anda dengan informasi yang valid.</p>
        </div>

        <!-- ALERT ERROR VALIDASI -->
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm animate-fade-in-up">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-exclamation-circle text-rose-600"></i>
                    <span class="text-xs font-extrabold uppercase tracking-wider">Mohon periksa kembali isian Anda:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- GRID CONTAINER FORMULIR & INFORMASI KETENTUAN -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start animate-fade-in-up">
            
            <!-- FORM INPUT TAMU (8 COLUMNS) -->
            <div class="lg:col-span-8 bg-white p-6 sm:p-8 rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-200/80">
                <form id="visitorForm" action="{{ route('visitor.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Baris 1: Nama Lengkap & No WhatsApp -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label for="nama_lengkap" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-user text-xs"></i>
                                </div>
                                <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Budi Santoso"
                                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition-all">
                            </div>
                        </div>

                        <div>
                            <label for="no_hp" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                No. HP / WhatsApp <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-phone text-xs"></i>
                                </div>
                                <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required placeholder="0812-XXXX-XXXX"
                                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Baris 2: Email & Status Kunjungan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Email <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-envelope text-xs"></i>
                                </div>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="budi@email.com"
                                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition-all">
                            </div>
                        </div>

                        <div>
                            <label for="temu_janji" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Status Kunjungan <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-calendar-check text-xs"></i>
                                </div>
                                <select id="temu_janji" name="temu_janji" required
                                        class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition-all">
                                    <option value="Belum Janji" {{ old('temu_janji') == 'Belum Janji' ? 'selected' : '' }}>Datang Langsung (Belum Janji)</option>
                                    <option value="Sudah Janji" {{ old('temu_janji') == 'Sudah Janji' ? 'selected' : '' }}>Sudah Membuat Janji Temu</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Asal Instansi / Alamat -->
                    <div>
                        <label for="alamat" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Asal Instansi / Alamat <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-building text-xs"></i>
                            </div>
                            <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}" required placeholder="Tuliskan nama instansi atau alamat asal Anda..."
                                   class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition-all">
                        </div>
                    </div>

                   <!-- Baris 3: Divisi Tujuan & Tanggal Kunjungan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label for="divisi_tujuan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Divisi / Unit Tujuan <span class="text-rose-500">*</span>
                            </label>
                            <select id="divisi_tujuan" name="divisi_tujuan" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition-all">
                                <option value="">-- Pilih Divisi --</option>
                                <option value="Kepala Stasiun (Kepsta)" {{ old('divisi_tujuan') == 'Kepala Stasiun (Kepsta)' ? 'selected' : '' }}>Kepala Stasiun (Kepsta)</option>
                                <option value="Tata Usaha (TU)" {{ old('divisi_tujuan') == 'Tata Usaha (TU)' ? 'selected' : '' }}>Tata Usaha (TU)</option>
                                <option value="Siaran" {{ old('divisi_tujuan') == 'Siaran' ? 'selected' : '' }}>Siaran</option>
                                <option value="Pemberitaan" {{ old('divisi_tujuan') == 'Pemberitaan' ? 'selected' : '' }}>Pemberitaan</option>
                                <option value="Teknologi dan Media Baru (TMB)" {{ old('divisi_tujuan') == 'Teknologi dan Media Baru (TMB)' ? 'selected' : '' }}>Teknologi dan Media Baru (TMB)</option>
                                <option value="Layanan Pengembangan Usaha (LPU)" {{ old('divisi_tujuan') == 'Layanan Pengembangan Usaha (LPU)' ? 'selected' : '' }}>Layanan Pengembangan Usaha (LPU)</option>
                                <option value="Lainnya" {{ old('divisi_tujuan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label for="tanggal_kunjungan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Tanggal Kunjungan <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" value="{{ old('tanggal_kunjungan', date('Y-m-d')) }}" required
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition-all">
                        </div>
                    </div>

                    <!-- Jam Kedatangan & Keperluan -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
                        <div class="sm:col-span-1">
                            <label for="jam_kunjungan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Jam Datang <span class="text-rose-500">*</span>
                            </label>
                            <input type="time" id="jam_kunjungan" name="jam_kunjungan" value="{{ old('jam_kunjungan', date('H:i')) }}" required
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition-all">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="keperluan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Keperluan Kunjungan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="keperluan" name="keperluan" value="{{ old('keperluan') }}" required placeholder="Jelaskan maksud dan tujuan kunjungan..."
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition-all">
                        </div>
                    </div>

                    <!-- Tombol Submit Form -->
                    <div class="pt-4">
                        <button type="button" onclick="openConfirmationModal()" class="w-full py-3.5 px-6 bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-[0.98] text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-600/25 hover:shadow-blue-600/35 transition-all flex items-center justify-center gap-2 group cursor-pointer">
                            <i class="fas fa-paper-plane text-xs group-hover:translate-x-0.5 transition-transform"></i>
                            <span>Kirim Data Kunjungan</span>
                        </button>
                    </div>

                </form>
            </div>

            <!-- KARTU INFORMASI KETENTUAN (4 COLUMNS) -->
            <div class="lg:col-span-4 bg-gradient-to-br from-blue-700 via-blue-800 to-slate-900 p-6 sm:p-8 rounded-3xl text-white shadow-xl shadow-slate-900/20 relative overflow-hidden flex flex-col justify-between h-full">
                <!-- Ambient Decorative Light -->
                <div class="absolute -top-12 -left-12 w-40 h-40 bg-cyan-400/20 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-400/30 flex items-center justify-center mb-5 backdrop-blur-md">
                        <i class="fas fa-info-circle text-cyan-300 text-lg"></i>
                    </div>

                    <h3 class="text-xl font-bold tracking-tight mb-4">Ketentuan Kunjungan</h3>

                    <ul class="space-y-3.5 text-xs sm:text-sm text-blue-100/90 font-normal leading-relaxed">
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-cyan-300 text-xs mt-1 shrink-0"></i>
                            <span>Anda dapat mengisi buku tamu secara mandiri di lobi LPP RRI Bukittinggi pada hari-H kedatangan.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-cyan-300 text-xs mt-1 shrink-0"></i>
                            <span>Bila belum memiliki janji temu, mohon bersabar menunggu di area lobi selagi petugas menghubungi divisi terkait.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-cyan-300 text-xs mt-1 shrink-0"></i>
                            <span>Pastikan nomor WhatsApp dan email Anda aktif agar memudahkan koordinasi atau konfirmasi kedatangan.</span>
                        </li>
                    </ul>
                </div>

                <div class="relative z-10 mt-8 pt-5 border-t border-blue-500/30 text-[11px] text-blue-200/70">
                    <span>LPP RRI Bukittinggi &copy; {{ date('Y') }}</span>
                </div>
            </div>

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200/80 py-6 mt-12 text-center text-xs text-slate-500 font-medium">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} LPP RRI Bukittinggi. Sistem Informasi Buku Tamu Digital TMB.</p>
        </div>
    </footer>

    <!-- MODAL KONfirmasi SEBELUM KIRIM (KREATIF & PROFESIONAL) -->
    <div id="confirmationModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100 relative animate-modal-pop text-center">
            
            <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-4 shadow-sm">
                <i class="fas fa-question-circle text-2xl"></i>
            </div>

            <h3 class="text-xl font-extrabold text-slate-900 mb-2">Pastikan Data Sudah Benar</h3>
            <p class="text-xs sm:text-sm text-slate-500 mb-6 leading-relaxed">
                Halo! Sebelum data kunjungan Anda dikirim ke sistem LPP RRI Bukittinggi, mohon pastikan kembali bahwa nama, nomor WhatsApp, instansi, dan tujuan sudah terisi dengan lengkap dan benar agar petugas dapat menghubungi Anda dengan lancar.
            </p>

            <div class="grid grid-cols-2 gap-3">
                <button type="button" onclick="closeConfirmationModal()" class="py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs sm:text-sm hover:bg-slate-100 active:scale-95 transition-all">
                    Periksa Lagi
                </button>
                <button type="button" onclick="submitVisitorForm()" class="py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-blue-600/30 active:scale-95 transition-all">
                    Ya, Sudah Benar & Kirim
                </button>
            </div>

        </div>
    </div>

    <!-- MODAL POP-UP SUKSES REGISTRASI (DENGAN 2 PILIHAN TOMBOL AKSI) -->
    @if (session('success'))
    <div id="successModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm animate-fade-in">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 text-center relative animate-modal-pop">
            
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                <i class="fas fa-check-circle text-3xl"></i>
            </div>

            <h3 class="text-xl font-black text-slate-900 mb-2">Registrasi Berhasil Dikirim!</h3>
            
            <!-- Ucapan Terima Kasih & Pemberitahuan Pelayanan Publik -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 mb-6 text-left">
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed text-center">
                    Terima kasih telah mengisi buku tamu digital LPP RRI Bukittinggi. Data kunjungan Anda saat ini berstatus <span class="font-bold text-amber-600">Diproses</span>. 
                    Informasi konfirmasi dan tindak lanjut kedatangan akan segera dihubungi oleh petugas melalui nomor <span class="font-semibold text-slate-800">WhatsApp</span> atau <span class="font-semibold text-slate-800">Email</span> Anda.
                </p>
            </div>

            <!-- 2 Pilihan Tombol Aksi yang Relevan untuk Publik -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('home') }}" class="py-3 px-4 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-bold rounded-xl text-xs sm:text-sm shadow-md shadow-blue-600/20 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-home"></i>
                    <span>Kembali ke Beranda</span>
                </a>
                <a href="{{ route('registrasi') }}" class="py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl text-xs sm:text-sm transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-edit"></i>
                    <span>Isi Buku Tamu Lain</span>
                </a>
            </div>

        </div>
    </div>
    @endif

    <!-- MODAL KONFIRMASI LOGOUT ADMIN (KHUSUS ADMIN) -->
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
                <button type="button" onclick="closeLogoutModal()" class="w-full py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs sm:text-sm hover:bg-slate-100 active:scale-95 transition-all">
                    Batal
                </button>
                
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-rose-600/30 active:scale-95 transition-all">
                        Ya, Keluar
                    </button>
                </form>
            </div>

        </div>
    </div>
    @endauth

    <!-- SCRIPT INTERAKTIF MODAL VALIDASI & KIRIM -->
    <script>
        // Modal Logout Admin
        function openLogoutModal() {
            const modal = document.getElementById('logoutModal');
            if (modal) modal.classList.remove('hidden');
        }
        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            if (modal) modal.classList.add('hidden');
        }

        // Modal Konfirmasi Data Sebelum Kirim
        function openConfirmationModal() {
            const form = document.querySelector('form');
            if (form.checkValidity()) {
                const modal = document.getElementById('confirmationModal');
                if (modal) modal.classList.remove('hidden');
            } else {
                form.reportValidity();
            }
        }
        function closeConfirmationModal() {
            const modal = document.getElementById('confirmationModal');
            if (modal) modal.classList.add('hidden');
        }
        function submitVisitorForm() {
            document.getElementById('visitorForm').submit();
        }

        // Toggle Hamburger Menu Mobile
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
</body>
</html>