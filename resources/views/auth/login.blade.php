<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Login Administrator - Buku Tamu RRI Bukittinggi</title>
    
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
        
        @keyframes cardEntrance {
            from { opacity: 0; transform: translateY(16px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-card-entrance {
            animation: cardEntrance 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-800 min-h-screen flex flex-col justify-between relative overflow-x-hidden antialiased selection:bg-blue-600 selection:text-white">

    <!-- BACKGROUND GEDUNG RRI (ATMOSPHERIC OVERLAY) -->
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/gedung-rri.png') }}" 
             alt="Gedung RRI Bukittinggi" 
             class="w-full h-full object-cover object-top sm:object-center opacity-60">
        
        <!-- Premium Atmospheric Dark Navy Overlay -->
        <div class="absolute inset-0 bg-gradient-to-tr from-slate-950/95 via-blue-950/65 to-slate-950/85 backdrop-blur-[2px]"></div>
    </div>

    <!-- HEADER TRANSPARAN SEAMLESS (MENYATU DENGAN BACKGROUND) -->
    <header class="relative z-20 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6">
        <div class="flex items-center justify-between h-14 sm:h-16">
            <!-- Logo RRI Putih Kontras -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 group">
                <img src="{{ asset('images/logo_rri.svg') }}" alt="Logo RRI Bukittinggi" class="h-8 sm:h-10 w-auto brightness-0 invert group-hover:scale-105 transition-transform duration-300 drop-shadow-md">
            </a>

            <!-- Navigasi Pill Glassmorphism Melayang -->
            <nav class="flex items-center gap-2">
                <div class="p-1 bg-slate-900/60 backdrop-blur-md rounded-full border border-white/10 shadow-xl flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-3.5 sm:px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold text-slate-200 hover:text-white hover:bg-white/10 transition-all flex items-center gap-1.5">
                        <i class="fas fa-home text-xs text-sky-400"></i>Beranda
                    </a>
                    <a href="{{ route('registrasi') }}" class="px-3.5 sm:px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold text-slate-200 hover:text-white hover:bg-white/10 transition-all flex items-center gap-1.5">
                        <i class="fas fa-edit text-xs text-sky-400"></i>Buku Tamu
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <!-- MAIN LOGIN CARD AREA (COMPACT & PAS DI TENGAH) -->
    <main class="relative z-10 flex-grow flex items-center justify-center p-4 sm:p-6 my-2 sm:my-4">
        <div class="w-full max-w-3xl bg-white rounded-2xl sm:rounded-3xl shadow-2xl shadow-black/80 border border-white/30 overflow-hidden grid grid-cols-1 md:grid-cols-12 animate-card-entrance">
            
            <!-- PANEL KIRI: BRANDING & SYSTEM INFO (5 COLUMNS) -->
            <div class="md:col-span-5 bg-gradient-to-br from-blue-700 via-blue-800 to-slate-950 p-6 sm:p-8 text-white flex flex-col justify-between relative overflow-hidden">
                
                <!-- Ambient Decorative Light -->
                <div class="absolute -top-12 -left-12 w-40 h-40 bg-cyan-400/20 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -bottom-12 -right-12 w-40 h-40 bg-blue-500/20 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10">
                    <!-- Badge Portal -->
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[11px] font-semibold mb-5 backdrop-blur-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                        <span>Portal Administrator</span>
                    </div>

                    <!-- Title -->
                    <h2 class="text-xl sm:text-2xl font-black tracking-tight leading-tight mb-2.5">
                        Buku Tamu <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 via-sky-200 to-blue-200">RRI Bukittinggi</span>
                    </h2>

                    <p class="text-xs text-blue-100/80 leading-relaxed font-normal mb-6">
                        Masuk ke panel kontrol utama untuk memantau kehadiran tamu, verifikasi pengajuan, dan rekapitulasi data.
                    </p>

                    <!-- Poin Keunggulan Fitur -->
                    <div class="space-y-2.5 border-t border-blue-500/30 pt-5">
                        <div class="flex items-center gap-2.5 text-xs text-blue-100/90 font-medium">
                            <div class="w-5 h-5 rounded-md bg-blue-500/20 border border-blue-400/30 flex items-center justify-center shrink-0">
                                <i class="fas fa-check text-[9px] text-cyan-300"></i>
                            </div>
                            <span>Akses Terenkripsi Sesuai Peran</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-xs text-blue-100/90 font-medium">
                            <div class="w-5 h-5 rounded-md bg-blue-500/20 border border-blue-400/30 flex items-center justify-center shrink-0">
                                <i class="fas fa-check text-[9px] text-cyan-300"></i>
                            </div>
                            <span>Pemantauan & Notifikasi Realtime</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Keamanan Kiri -->
                <div class="relative z-10 mt-6 pt-4 border-t border-blue-500/20 flex items-center gap-2 text-[10px] text-blue-200/70">
                    <i class="fas fa-shield-halved text-cyan-300 text-xs"></i>
                    <span>Terproteksi Enkripsi TMB RRI</span>
                </div>
            </div>

            <!-- PANEL KANAN: FORM LOGIN (7 COLUMNS) -->
            <div class="md:col-span-7 p-6 sm:p-8 flex flex-col justify-center bg-gradient-to-br from-white via-slate-50/90 to-blue-50/30">
                
                <!-- Header Form & Wadah Ikon -->
                <div class="mb-4">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 text-white flex items-center justify-center mb-3 shadow-md shadow-blue-500/20 border border-blue-400/30">
                        <i class="fas fa-user-shield text-lg text-white"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Login Petugas</h3>
                    <p class="text-xs text-slate-500 mt-0.5 font-normal">
                        Masukkan kredensial akun administrator Anda
                    </p>
                </div>

                <!-- ALERT STATUS (Misal: Sukses Reset Password) -->
                @if (session('status'))
                    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold flex items-center gap-2">
                        <i class="fas fa-circle-check text-emerald-600 text-sm"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- KOTAK NOTIFIKASI ERROR / VALIDASI GAGAL -->
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs font-bold">
                        <div class="flex items-center gap-1.5 mb-1">
                            <i class="fas fa-triangle-exclamation text-rose-600"></i>
                            <span>Gagal Masuk Sistem:</span>
                        </div>
                        <ul class="list-disc pl-4 space-y-0.5 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- FORM INPUT UTAMA -->
                <form action="{{ route('login.post') }}" method="POST" class="space-y-3.5 sm:space-y-4">
                    @csrf

                    <!-- Field Email -->
                    <div>
                        <label for="email" class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-700 mb-1">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600">
                                <i class="fas fa-envelope text-xs"></i>
                            </div>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required 
                                   placeholder="admin@rri.co.id" 
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 shadow-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition-all">
                        </div>
                    </div>

                    <!-- Field Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-700">
                                Password
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-700 transition-colors">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600">
                                <i class="fas fa-key text-xs"></i>
                            </div>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   placeholder="••••••••" 
                                   class="w-full pl-9 pr-10 py-2.5 bg-white border border-slate-200/90 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 shadow-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition-all">
                            
                            <!-- Toggle Button Mata (Show/Hide Password) -->
                            <button type="button" 
                                    id="togglePassword" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-blue-600 focus:outline-none transition-colors">
                                <i class="fas fa-eye text-xs" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center pt-0.5">
                        <input type="checkbox" 
                               id="remember" 
                               name="remember" 
                               class="w-3.5 h-3.5 text-blue-600 border-slate-300 rounded focus:ring-blue-500 cursor-pointer">
                        <label for="remember" class="ml-2 text-xs font-semibold text-slate-600 cursor-pointer select-none">
                            Ingat saya di perangkat ini
                        </label>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" 
                            class="w-full py-3 px-5 bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-[0.98] text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-blue-600/25 hover:shadow-blue-600/35 transition-all duration-200 flex items-center justify-center gap-2 group mt-2">
                        <i class="fas fa-right-to-bracket text-xs group-hover:translate-x-0.5 transition-transform"></i>
                        <span>Masuk Dashboard</span>
                    </button>
                </form>

                <!-- Kembali ke Beranda -->
                <div class="mt-5 text-center border-t border-slate-200/60 pt-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors">
                        <i class="fas fa-arrow-left text-[10px]"></i>
                        <span>Kembali ke Beranda Utama</span>
                    </a>
                </div>

            </div>
        </div>
    </main>

    <!-- FOOTER MINIMALIS TRANSPARAN -->
    <footer class="relative z-10 w-full text-center py-4 text-[11px] text-slate-400/90 font-medium">
        <p>&copy; {{ date('Y') }} LPP RRI Bukittinggi. Sistem Buku Tamu Digital TMB.</p>
    </footer>

    <!-- SCRIPT INTERAKTIF & ANTI-CACHE BACK BUTTON -->
    <script>
        // Memaksa reload dari server jika halaman diakses via tombol Back browser (bfcache)
        window.addEventListener("pageshow", function (event) {
            if (event.persisted || (performance.navigation && performance.navigation.type === 2)) {
                window.location.reload();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (togglePassword && passwordInput && eyeIcon) {
                togglePassword.addEventListener('click', function() {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    
                    if (isPassword) {
                        eyeIcon.classList.remove('fa-eye');
                        eyeIcon.classList.add('fa-eye-slash');
                    } else {
                        eyeIcon.classList.remove('fa-eye-slash');
                        eyeIcon.classList.add('fa-eye');
                    }
                });
            }
        });
    </script>
</body>
</html>