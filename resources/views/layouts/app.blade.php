<!DOCTYPE html>
<html lang="id" class="scroll-smooth m-0 p-0">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Buku Tamu - RRI Bukittinggi' }}</title>
    
    <!-- Preload Logo RRI -->
    <link rel="preload" href="{{ asset('images/logo_rri.svg') }}" as="image" type="image/svg+xml">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    
    <style>
        html, body {
            margin: 0 !important;
            padding: 0 !important;
        }
        body { font-family: 'Inter', sans-serif; }
        .font-mono-clock { font-family: 'JetBrains Mono', monospace; }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 flex flex-col min-h-screen selection:bg-blue-200 antialiased overflow-x-hidden m-0 p-0">

    <!-- FLOATING TOAST NOTIFICATION -->
    <div id="toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 max-w-sm w-full pointer-events-none px-4 sm:px-0">
        @if (session('success'))
            <div class="toast-item pointer-events-auto flex items-center justify-between gap-3 p-4 bg-white/95 backdrop-blur-md border border-emerald-100 rounded-2xl shadow-xl shadow-slate-900/10 transform translate-y-0 opacity-100 transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 shadow-sm">
                        <i class="fas fa-check-circle text-base"></i>
                    </div>
                    <div>
                        <h5 class="text-xs font-extrabold text-slate-900">Berhasil!</h5>
                        <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ session('success') }}</p>
                    </div>
                </div>
                <button onclick="dismissToast(this)" class="text-slate-400 hover:text-slate-700 p-1 text-sm font-bold transition">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="toast-item pointer-events-auto flex items-center justify-between gap-3 p-4 bg-white/95 backdrop-blur-md border border-rose-100 rounded-2xl shadow-xl shadow-slate-900/10 transform translate-y-0 opacity-100 transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 shadow-sm">
                        <i class="fas fa-shield-exclamation text-base"></i>
                    </div>
                    <div>
                        <h5 class="text-xs font-extrabold text-slate-900">Akses Dibatasi</h5>
                        <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ session('error') }}</p>
                    </div>
                </div>
                <button onclick="dismissToast(this)" class="text-slate-400 hover:text-slate-700 p-1 text-sm font-bold transition">&times;</button>
            </div>
        @endif
    </div>

    <!-- Header Navbar Modern -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-50 shadow-sm w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14 sm:h-16">
                
                <!-- BAGIAN KIRI: Logo RRI -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 group">
                        <img src="{{ asset('images/logo_rri.svg') }}" 
                             alt="Logo RRI Bukittinggi" 
                             loading="eager" 
                             class="h-7 sm:h-9 w-auto object-contain transition-transform group-hover:scale-105">
                    </a>
                </div>

                <!-- BAGIAN KANAN: Navigasi Desktop -->
                <nav class="hidden md:flex items-center gap-2">
                    <div class="p-1 bg-slate-100 rounded-full border border-slate-200 flex items-center gap-1">
                        <a href="{{ route('home') }}" 
                           class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-all duration-200 flex items-center gap-1.5
                                {{ Request::routeIs('home') ? 'bg-white text-blue-700 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                            <i class="fas fa-home text-xs {{ Request::routeIs('home') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                            <span>Beranda</span>
                        </a>

                        <a href="{{ route('registrasi') }}" 
                           class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-all duration-200 flex items-center gap-1.5
                                {{ Request::routeIs('registrasi*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md font-semibold' : 'text-slate-600 hover:text-slate-900' }}">
                            <i class="fas fa-edit text-xs {{ Request::routeIs('registrasi*') ? 'text-white' : 'text-slate-400' }}"></i>
                            <span>Buku Tamu</span>
                        </a>
                    </div>

                    @auth
                    <div class="h-5 w-[1px] bg-slate-200 mx-1"></div>

                    <!-- BADGE PENANDA ROLE (DESKTOP) -->
                    <div class="flex items-center gap-2 px-3 py-1 rounded-full border-2 transition-all shadow-2xs 
                                {{ auth()->user()->isSuperAdmin() 
                                    ? 'bg-purple-50 border-purple-300 text-purple-800' 
                                    : 'bg-emerald-50/50 border-emerald-200 text-emerald-800' }}">
                        <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ auth()->user()->isSuperAdmin() ? 'bg-purple-400 opacity-75' : 'bg-emerald-400 opacity-75' }}"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 {{ auth()->user()->isSuperAdmin() ? 'bg-purple-600' : 'bg-emerald-600' }}"></span>
                        </span>
                        <div class="flex flex-col text-left leading-none">
                            <span class="text-[11px] font-extrabold tracking-wide">
                                {{ auth()->user()->isSuperAdmin() ? 'Super Admin' : 'Admin' }}
                            </span>
                        </div>
                    </div>

                    <!-- Dashboard Button -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all duration-200 flex items-center gap-1.5 border border-slate-700 shrink-0
                            {{ Request::routeIs('admin.dashboard*') ? 'bg-blue-900 text-white shadow-md' : 'bg-slate-800 text-white hover:bg-slate-900' }}">
                        <i class="fas fa-chart-pie text-xs text-blue-400"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- Tombol Kelola Rating & Ulasan (Dapat diakses Admin & Super Admin) -->
                    <a href="{{ route('admin.feedbacks') }}" 
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all duration-200 flex items-center gap-1.5 border border-amber-300 shrink-0
                            {{ Request::routeIs('admin.feedbacks*') ? 'bg-amber-600 text-white shadow-md' : 'bg-amber-500 text-white hover:bg-amber-600' }}">
                        <i class="fas fa-star text-xs text-amber-200"></i>
                        <span>Ulasan & Rating</span>
                    </a>

                    <!-- Tombol Kelola Admin (EKSKLUSIF KHUSUS SUPER ADMIN) -->
                    @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.users.index') }}" 
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all duration-200 flex items-center gap-1.5 border border-purple-300 shrink-0
                            {{ Request::routeIs('admin.users*') ? 'bg-purple-800 text-white shadow-md' : 'bg-purple-700 text-white hover:bg-purple-800' }}">
                        <i class="fas fa-user-shield text-xs text-purple-200"></i>
                        <span>Kelola Admin</span>
                    </a>
                    @endif

                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                title="Keluar dari akun admin"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-200 hover:bg-rose-600 hover:text-white transition-all duration-200 cursor-pointer">
                            <i class="fas fa-sign-out-alt text-xs"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                    @endauth
                </nav>

                <!-- TOMBOL HAMBURGER MOBILE & BADGE ROLE (LAYAR HP) -->
                <div class="flex items-center md:hidden gap-2">
                    @auth
                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold border shadow-2xs
                                    {{ auth()->user()->isSuperAdmin() 
                                        ? 'bg-purple-100 text-purple-800 border-purple-300' 
                                        : 'bg-slate-100 text-slate-800 border-slate-300' }}">
                            <i class="fas {{ auth()->user()->isSuperAdmin() ? 'fa-user-gear text-purple-600' : 'fa-user-check text-blue-600' }}"></i>
                            <span>{{ auth()->user()->isSuperAdmin() ? 'Super Admin' : 'Admin' }}</span>
                        </div>
                    @endauth

                    <button type="button" id="mobileMenuBtn" class="p-2 rounded-xl bg-slate-200/80 text-slate-700 hover:bg-slate-300 focus:outline-none">
                        <i class="fas fa-bars text-lg" id="mobileMenuIcon"></i>
                    </button>
                </div>

            </div>

            <!-- DROPDOWN MENU MOBILE -->
            <div id="mobileMenu" class="hidden md:hidden pb-4 pt-2 border-t border-slate-200/80 animate-fade-in-up">
                <div class="flex flex-col gap-2">
                    <a href="{{ route('home') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold {{ Request::routeIs('home') ? 'bg-blue-600 text-white' : 'text-slate-700 bg-white border border-slate-200' }} flex items-center gap-2">
                        <i class="fas fa-home text-xs"></i> Beranda Utama
                    </a>
                    <a href="{{ route('registrasi') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold {{ Request::routeIs('registrasi*') ? 'bg-blue-600 text-white' : 'text-slate-700 bg-white border border-slate-200' }} flex items-center gap-2">
                        <i class="fas fa-edit text-xs"></i> Buku Tamu
                    </a>

                    @auth
                        <div class="my-1 border-t border-slate-200"></div>
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-xl text-sm font-bold bg-slate-900 text-white flex items-center gap-2">
                            <i class="fas fa-chart-pie text-xs text-sky-400"></i>
                            <span>Dashboard Admin</span>
                        </a>

                        <a href="{{ route('admin.feedbacks') }}" class="px-4 py-2.5 rounded-xl text-sm font-bold bg-amber-500 text-white flex items-center gap-2">
                            <i class="fas fa-star text-xs text-amber-200"></i>
                            <span>Ulasan & Rating Pelayanan</span>
                        </a>

                        @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-bold bg-purple-700 text-white flex items-center gap-2">
                            <i class="fas fa-user-shield text-xs text-purple-200"></i>
                            <span>Kelola Akun Admin</span>
                        </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-sm font-bold text-rose-600 bg-rose-50 border border-rose-100 flex items-center gap-2">
                                <i class="fas fa-sign-out-alt text-xs"></i>
                                <span>Keluar dari Akun</span>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>

        </div>
    </header>
    
    <!-- Konten Utama Halaman -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Clean -->
    <footer class="bg-slate-950 text-slate-400 border-t border-slate-800/80 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <div>
                <p class="text-xs sm:text-sm font-medium text-slate-300">&copy; {{ date('Y') }} LPP RRI Bukittinggi</p>
                <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5">Lembaga Penyiaran Publik Radio Republik Indonesia - Bukittinggi</p>
            </div>
            <div class="text-xs text-slate-500">
                <span>Sistem Buku Tamu Digital TMB</span>
            </div>
        </div>
    </footer>

    <!-- Script Hamburger, Toast Dismiss, & Jam Realtime -->
    <script>
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuIcon = document.getElementById('mobileMenuIcon');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                if (mobileMenu.classList.contains('hidden')) {
                    mobileMenuIcon.classList.remove('fa-times');
                    mobileMenuIcon.classList.add('fa-bars');
                } else {
                    mobileMenuIcon.classList.remove('fa-bars');
                    mobileMenuIcon.classList.add('fa-times');
                }
            });
        }

        // Script Toast Notification Auto Hide
        document.addEventListener('DOMContentLoaded', function () {
            const toasts = document.querySelectorAll('.toast-item');
            toasts.forEach(toast => {
                setTimeout(() => {
                    fadeOutToast(toast);
                }, 4500);
            });
        });

        function dismissToast(button) {
            const toast = button.closest('.toast-item');
            fadeOutToast(toast);
        }

        function fadeOutToast(toast) {
            if (!toast) return;
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    </script>
</body>
</html>