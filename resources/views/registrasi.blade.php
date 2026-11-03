<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
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

        @keyframes shakeError {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-4px); }
            40%, 80% { transform: translateX(4px); }
        }
        .animate-shake { animation: shakeError 0.35s ease-in-out; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen antialiased overflow-x-hidden selection:bg-blue-200">

    <!-- HEADER NAVBAR -->
    @include('partials.navbar')

    <!-- KONTEN UTAMA FORMULIR BUKU TAMU -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8">
        
        <!-- Tombol Kembali Ke Beranda -->
        <div class="mb-4 animate-fade-in-up">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-blue-600 bg-white px-3.5 py-2 rounded-xl border border-slate-200/80 shadow-xs transition-all">
                <i class="fas fa-arrow-left text-[10px]"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        <!-- HEADER CARD IDENTIK DENGAN DASHBOARD ADMIN -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-in-up">
            <div class="space-y-1 text-left">
                <div class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-blue-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                    <span>LPP RRI Bukittinggi</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">Formulir Buku Tamu</h1>
                <p class="text-xs sm:text-sm text-slate-500">Silakan lengkapi data kunjungan Anda dengan informasi yang valid.</p>
            </div>
        </div>

        <!-- ALERT ERROR VALIDASI LARAVEL BACKEND -->
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
            <div class="lg:col-span-8 bg-white rounded-3xl shadow-sm border border-slate-100 p-6 md:p-8">
                <form id="visitorForm" action="{{ route('visitor.store') }}" method="POST" class="space-y-5" novalidate>
                    @csrf

                    <!-- Baris 1: Nama Lengkap & No WhatsApp -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="nama_lengkap" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-user text-xs"></i>
                                </div>
                                <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Budi Santoso"
                                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                            </div>
                            <!-- Error Message UI Kustom -->
                            <p class="error-msg hidden text-[11px] font-semibold text-rose-600 mt-1.5 flex items-center gap-1.5 animate-fade-in-up">
                                <i class="fas fa-circle-exclamation text-rose-500"></i>
                                <span>Mohon isi nama lengkap Anda sesuai kartu identitas.</span>
                            </p>
                        </div>

                        <!-- No HP / WA -->
                        <div>
                            <label for="no_hp" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                No. HP / WhatsApp <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-phone text-xs"></i>
                                </div>
                                <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required placeholder="0812-XXXX-XXXX"
                                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                            </div>
                            <!-- Error Message UI Kustom -->
                            <p class="error-msg hidden text-[11px] font-semibold text-rose-600 mt-1.5 flex items-center gap-1.5 animate-fade-in-up">
                                <i class="fas fa-circle-exclamation text-rose-500"></i>
                                <span>Masukkan nomor WhatsApp aktif agar petugas dapat memberikan konfirmasi.</span>
                            </p>
                        </div>
                    </div>

                    <!-- Baris 2: Email & Status Kunjungan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Email <span class="text-slate-400 font-normal">(opsional)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-envelope text-xs"></i>
                                </div>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="budi@email.com"
                                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                            </div>
                        </div>

                        <!-- Status Kunjungan -->
                        <div>
                            <label for="temu_janji" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Status Kunjungan <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative w-full">
                                <div class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-blue-600">
                                    <i class="fas fa-calendar-check text-xs"></i>
                                </div>
                                <select id="temu_janji" name="temu_janji" required
                                        class="w-full appearance-none bg-white rounded-xl border border-slate-200 pl-11 pr-10 py-2.5 text-base md:text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                                    <option value="Belum Janji" {{ old('temu_janji') == 'Belum Janji' ? 'selected' : '' }}>Datang Langsung (Belum Janji)</option>
                                    <option value="Sudah Janji" {{ old('temu_janji') == 'Sudah Janji' ? 'selected' : '' }}>Sudah Membuat Janji Temu</option>
                                </select>
                                <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Asal Instansi / Alamat -->
                    <div>
                        <label for="alamat" class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Asal Instansi / Alamat <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-building text-xs"></i>
                            </div>
                            <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}" required placeholder="Tuliskan nama instansi atau alamat asal Anda..."
                                   class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                        </div>
                        <!-- Error Message UI Kustom -->
                        <p class="error-msg hidden text-[11px] font-semibold text-rose-600 mt-1.5 flex items-center gap-1.5 animate-fade-in-up">
                            <i class="fas fa-circle-exclamation text-rose-500"></i>
                            <span>Mohon cantumkan instansi, organisasi, atau alamat asal Anda.</span>
                        </p>
                    </div>

                    <!-- Baris: Kategori Asal Instansi & Jumlah Pengunjung -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 p-4 rounded-2xl bg-blue-50/40 border border-blue-100/80">
                        <!-- Kategori Asal Instansi -->
                        <div>
                            <label for="asal_instansi" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Kategori Asal Instansi <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative w-full">
                                <div class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-blue-600">
                                    <i class="fas fa-sitemap text-xs"></i>
                                </div>
                                <select id="asal_instansi" name="asal_instansi" required
                                        class="w-full appearance-none bg-white rounded-xl border border-slate-200 pl-11 pr-10 py-2.5 text-base md:text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach (['Instansi Pemerintah', 'Swasta', 'Masyarakat Umum'] as $kategori)
                                        <option value="{{ $kategori }}" {{ old('asal_instansi') == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                            <!-- Error Message UI Kustom -->
                            <p class="error-msg hidden text-[11px] font-semibold text-rose-600 mt-1.5 flex items-center gap-1.5 animate-fade-in-up">
                                <i class="fas fa-circle-exclamation text-rose-500"></i>
                                <span>Silakan pilih kategori asal instansi Anda.</span>
                            </p>
                        </div>

                        <!-- Jumlah Pengunjung -->
                        <div>
                            <label for="jumlah_pengunjung" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Jumlah Pengunjung <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-600">
                                    <i class="fas fa-users text-xs"></i>
                                </div>
                                <input type="number" id="jumlah_pengunjung" name="jumlah_pengunjung" value="{{ old('jumlah_pengunjung', 1) }}" min="1" max="500" step="1" required
                                       class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                            </div>
                            <!-- Error Message UI Kustom -->
                            <p class="error-msg hidden text-[11px] font-semibold text-rose-600 mt-1.5 flex items-center gap-1.5 animate-fade-in-up">
                                <i class="fas fa-circle-exclamation text-rose-500"></i>
                                <span>Masukkan jumlah pengunjung (minimal 1 orang).</span>
                            </p>
                        </div>
                    </div>

                    <!-- Baris 3: Divisi Tujuan & Tanggal Kunjungan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <!-- Divisi Tujuan -->
                        <div>
                            <label for="divisi_tujuan" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Divisi / Unit Tujuan <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative w-full">
                                <div class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-blue-600">
                                    <i class="fas fa-layer-group text-xs"></i>
                                </div>
                                <select id="divisi_tujuan" name="divisi_tujuan" required
                                        class="w-full appearance-none bg-white rounded-xl border border-slate-200 pl-11 pr-10 py-2.5 text-base md:text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                                    <option value="">-- Pilih Divisi --</option>
                                    <option value="Kepala Stasiun (Kepsta)" {{ old('divisi_tujuan') == 'Kepala Stasiun (Kepsta)' ? 'selected' : '' }}>Kepala Stasiun (Kepsta)</option>
                                    <option value="Tata Usaha (TU)" {{ old('divisi_tujuan') == 'Tata Usaha (TU)' ? 'selected' : '' }}>Tata Usaha (TU)</option>
                                    <option value="Siaran" {{ old('divisi_tujuan') == 'Siaran' ? 'selected' : '' }}>Siaran</option>
                                    <option value="Pemberitaan" {{ old('divisi_tujuan') == 'Pemberitaan' ? 'selected' : '' }}>Pemberitaan</option>
                                    <option value="Teknologi dan Media Baru (TMB)" {{ old('divisi_tujuan') == 'Teknologi dan Media Baru (TMB)' ? 'selected' : '' }}>Teknologi dan Media Baru (TMB)</option>
                                    <option value="Layanan Pengembangan Usaha (LPU)" {{ old('divisi_tujuan') == 'Layanan Pengembangan Usaha (LPU)' ? 'selected' : '' }}>Layanan Pengembangan Usaha (LPU)</option>
                                    <option value="Lainnya" {{ old('divisi_tujuan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                            <!-- Error Message UI Kustom -->
                            <p class="error-msg hidden text-[11px] font-semibold text-rose-600 mt-1.5 flex items-center gap-1.5 animate-fade-in-up">
                                <i class="fas fa-circle-exclamation text-rose-500"></i>
                                <span>Silakan pilih divisi atau unit kerja yang ingin Anda tuju.</span>
                            </p>
                        </div>

                        <!-- Tanggal Kunjungan -->
                        <div>
                            <label for="tanggal_kunjungan" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Tanggal Kunjungan <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" value="{{ old('tanggal_kunjungan', \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d')) }}" required
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                            <!-- Error Message UI Kustom -->
                            <p class="error-msg hidden text-[11px] font-semibold text-rose-600 mt-1.5 flex items-center gap-1.5 animate-fade-in-up">
                                <i class="fas fa-circle-exclamation text-rose-500"></i>
                                <span>Mohon tentukan tanggal rencana kunjungan Anda.</span>
                            </p>
                        </div>
                    </div>

                    <!-- Jam Kedatangan & Keperluan -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
                        <!-- Jam Datang -->
                        <div class="sm:col-span-1">
                            <label for="jam_kunjungan" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Jam Datang <span class="text-rose-500">*</span>
                            </label>
                            <input type="time" id="jam_kunjungan" name="jam_kunjungan" value="{{ old('jam_kunjungan', \Carbon\Carbon::now('Asia/Jakarta')->format('H:i')) }}" required
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                            <!-- Error Message UI Kustom -->
                            <p class="error-msg hidden text-[11px] font-semibold text-rose-600 mt-1.5 flex items-center gap-1.5 animate-fade-in-up">
                                <i class="fas fa-circle-exclamation text-rose-500"></i>
                                <span>Tentukan jam kedatangan Anda.</span>
                            </p>
                        </div>

                        <!-- Keperluan Kunjungan -->
                        <div class="sm:col-span-2">
                            <label for="keperluan" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Keperluan Kunjungan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="keperluan" name="keperluan" value="{{ old('keperluan') }}" required placeholder="Jelaskan maksud dan tujuan kunjungan..."
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                            <!-- Error Message UI Kustom -->
                            <p class="error-msg hidden text-[11px] font-semibold text-rose-600 mt-1.5 flex items-center gap-1.5 animate-fade-in-up">
                                <i class="fas fa-circle-exclamation text-rose-500"></i>
                                <span>Mohon uraikan singkat maksud dan tujuan kedatangan Anda.</span>
                            </p>
                        </div>
                    </div>

                    <!-- Tombol Submit Form -->
                    <div class="pt-4">
                        <button type="button" onclick="openConfirmationModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl shadow-sm transition active:scale-[0.99] text-sm flex items-center justify-center gap-2 group cursor-pointer">
                            <i class="fas fa-paper-plane text-xs group-hover:translate-x-0.5 transition-transform"></i>
                            <span>Kirim Data Kunjungan</span>
                        </button>
                    </div>

                </form>
            </div>

            <!-- KARTU INFORMASI KETENTUAN (4 COLUMNS) -->
            <div class="lg:col-span-4 bg-gradient-to-br from-blue-700 via-blue-800 to-slate-900 border border-white/10 shadow-xl shadow-blue-950/20 p-6 md:p-8 rounded-3xl relative overflow-hidden flex flex-col justify-between h-full text-white">
                <!-- Ambient Decorative Light -->
                <div class="absolute -top-12 -left-12 w-40 h-40 bg-sky-400/15 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="w-10 h-10 rounded-xl bg-white/10 text-sky-200 border border-white/15 backdrop-blur-sm flex items-center justify-center mb-4">
                        <i class="fas fa-info-circle text-base"></i>
                    </div>

                    <h3 class="text-lg text-white font-bold tracking-wide mb-3">Ketentuan Kunjungan</h3>

                    <ul class="space-y-3 text-blue-100/90 text-sm font-normal leading-relaxed">
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-sky-300 text-xs mt-1 shrink-0"></i>
                            <span>Anda dapat mengisi buku tamu secara mandiri di lobi LPP RRI Bukittinggi pada hari-H kedatangan.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-sky-300 text-xs mt-1 shrink-0"></i>
                            <span>Bila belum memiliki janji temu, mohon bersabar menunggu di area lobi selagi petugas menghubungi divisi terkait.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-sky-300 text-xs mt-1 shrink-0"></i>
                            <span>Pastikan nomor WhatsApp dan email Anda aktif agar memudahkan koordinasi atau konfirmasi kedatangan.</span>
                        </li>
                    </ul>
                </div>

                <div class="relative z-10 mt-6 pt-4 border-t border-white/15 text-[11px] text-blue-200/80 font-medium">
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

    <!-- MODAL KONFIRMASI SEBELUM KIRIM -->
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

    <!-- MODAL PERINGATAN WAKTU KEDATANGAN LAMPAU -->
    <div id="pastTimeWarningModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100 relative animate-modal-pop text-center">
            
            <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-100 text-amber-500 flex items-center justify-center mx-auto mb-4 shadow-sm">
                <i class="fas fa-clock text-2xl"></i>
            </div>

            <h3 class="text-xl font-extrabold text-slate-900 mb-2">Waktu Kedatangan Tidak Sesuai</h3>
            <p class="text-xs sm:text-sm text-slate-500 mb-6 leading-relaxed">
                Mohon maaf, Anda tidak dapat memilih jam kedatangan yang sudah terlewat untuk hari ini. Silakan sesuaikan kembali dengan jam kehadiran Anda saat ini atau sesudahnya.
            </p>

            <div>
                <button type="button" onclick="closePastTimeModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-xl px-5 py-2.5 font-semibold text-sm shadow-sm active:scale-95 transition-all cursor-pointer">
                    Perbaiki Waktu
                </button>
            </div>

        </div>
    </div>

    <!-- MODAL POP-UP SUKSES REGISTRASI -->
    @if (session('success'))
    <div id="successModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm animate-fade-in">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 text-center relative animate-modal-pop">
            
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                <i class="fas fa-check-circle text-3xl"></i>
            </div>

            <h3 class="text-xl font-black text-slate-900 mb-2">Registrasi Berhasil Dikirim!</h3>
            
            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 mb-6 text-left">
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed text-center">
                    Terima kasih telah mengisi buku tamu digital LPP RRI Bukittinggi. Data kunjungan Anda saat ini berstatus <span class="font-bold text-amber-600">Diproses</span>. 
                    Informasi konfirmasi dan tindak lanjut kedatangan akan segera dihubungi oleh petugas melalui nomor <span class="font-semibold text-slate-800">WhatsApp</span> atau <span class="font-semibold text-slate-800">Email</span> Anda.
                </p>
            </div>

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

    <!-- SCRIPT VALIDASI CUSTOM UI/UX TOOLTIP -->
    <script>
        // Fungsi Validasi UI/UX Modern
        function validateFormUI() {
            const form = document.getElementById('visitorForm');
            const inputs = form.querySelectorAll('input[required], select[required]');
            let isValid = true;
            let firstInvalidInput = null;

            inputs.forEach(input => {
                const parent = input.closest('div');
                const errorMsg = parent.parentElement.querySelector('.error-msg') || parent.querySelector('.error-msg');

                if (!input.value.trim()) {
                    isValid = false;
                    
                    // Efek visual border merah & animasi getar (shake)
                    input.classList.add('border-rose-500', 'bg-rose-50/30', 'animate-shake');
                    input.classList.remove('border-slate-200', 'bg-slate-50', 'bg-white');

                    if (errorMsg) {
                        errorMsg.classList.remove('hidden');
                    }

                    if (!firstInvalidInput) {
                        firstInvalidInput = input;
                    }
                } else {
                    // Reset ke tampilan normal jika terisi
                    input.classList.remove('border-rose-500', 'bg-rose-50/30', 'animate-shake');
                    input.classList.add('border-slate-200');
                    if (input.tagName.toLowerCase() === 'select') {
                        input.classList.add('bg-white');
                    } else {
                        input.classList.add('bg-slate-50');
                    }

                    if (errorMsg) {
                        errorMsg.classList.add('hidden');
                    }
                }
            });

            if (!isValid && firstInvalidInput) {
                firstInvalidInput.focus();
            }

            return isValid;
        }

        function isPastTimeSelected() {
            const tglInput = document.getElementById('tanggal_kunjungan');
            const jamInput = document.getElementById('jam_kunjungan') || document.querySelector('input[name="jam_datang"]');
            if (!tglInput || !jamInput || !tglInput.value || !jamInput.value) {
                return false;
            }

            const now = new Date();
            const yyyy = now.getFullYear();
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const dd = String(now.getDate()).padStart(2, '0');
            const todayStr = `${yyyy}-${mm}-${dd}`;

            if (tglInput.value === todayStr) {
                const currentHours = String(now.getHours()).padStart(2, '0');
                const currentMinutes = String(now.getMinutes()).padStart(2, '0');
                const currentTimeStr = `${currentHours}:${currentMinutes}`;
                const selectedTimeStr = jamInput.value.substring(0, 5);

                if (selectedTimeStr < currentTimeStr) {
                    jamInput.classList.add('border-rose-500', 'bg-rose-50/30', 'animate-shake');
                    jamInput.classList.remove('border-slate-200', 'bg-slate-50');
                    return true;
                }
            }

            return false;
        }

        function openPastTimeModal() {
            const modal = document.getElementById('pastTimeWarningModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closePastTimeModal() {
            const modal = document.getElementById('pastTimeWarningModal');
            if (modal) modal.classList.add('hidden');
            const jamInput = document.getElementById('jam_kunjungan') || document.querySelector('input[name="jam_datang"]');
            if (jamInput) {
                jamInput.focus();
            }
        }

        function openConfirmationModal() {
            if (!validateFormUI()) {
                return;
            }
            if (isPastTimeSelected()) {
                openPastTimeModal();
                return;
            }
            const modal = document.getElementById('confirmationModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeConfirmationModal() {
            const modal = document.getElementById('confirmationModal');
            if (modal) modal.classList.add('hidden');
        }

        function submitVisitorForm() {
            if (isPastTimeSelected()) {
                closeConfirmationModal();
                openPastTimeModal();
                return;
            }
            document.getElementById('visitorForm').submit();
        }

        // Live Real-Time Validation saat mengetik/memilih
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('visitorForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!validateFormUI()) {
                        e.preventDefault();
                        return false;
                    }
                    if (isPastTimeSelected()) {
                        e.preventDefault();
                        closeConfirmationModal();
                        openPastTimeModal();
                        return false;
                    }
                });

                const inputs = form.querySelectorAll('input[required], select[required]');
                
                inputs.forEach(input => {
                    const checkInput = () => {
                        const parent = input.closest('div');
                        const errorMsg = parent.parentElement.querySelector('.error-msg') || parent.querySelector('.error-msg');

                        if (input.value.trim()) {
                            input.classList.remove('border-rose-500', 'bg-rose-50/30', 'animate-shake');
                            input.classList.add('border-slate-200');
                            if (input.tagName.toLowerCase() === 'select') {
                                input.classList.add('bg-white');
                            } else {
                                input.classList.add('bg-slate-50');
                            }
                            if (errorMsg) errorMsg.classList.add('hidden');
                        }
                    };

                    input.addEventListener('input', checkInput);
                    input.addEventListener('change', checkInput);
                });
            }

            @if ($errors->has('jam_datang') || $errors->has('jam_kunjungan'))
                openPastTimeModal();
            @endif
        });
    </script>
</body>
</html>