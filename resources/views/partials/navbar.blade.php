@php
    $isHome = request()->routeIs('home');
    $isBukuTamu = request()->routeIs('registrasi*') || request()->routeIs('tamu.*') || request()->routeIs('visitor.*');
    $isAdmin = request()->routeIs('admin.*');
    $navAvgRating = $avgRatingFormat ?? number_format(\App\Models\Feedback::avg('rating') ?: 5.0, 1);
@endphp

<!-- HEADER NAVBAR BERSAMA -->
<header class="bg-slate-50/80 backdrop-blur-xl border-b border-blue-100/80 sticky top-0 z-50 shadow-sm w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <!-- SISI KIRI: LOGO RRI & BADGE ADMIN PANEL -->
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 group">
                <img src="{{ asset('images/logo_rri.svg') }}" alt="Logo RRI Bukittinggi" class="h-8 sm:h-9 w-auto group-hover:scale-105 transition-transform duration-300">
                
                @auth
                    <span class="hidden md:inline-block px-2.5 py-0.5 rounded-full bg-blue-100/80 border border-blue-200 text-[10px] font-extrabold uppercase tracking-wider text-blue-800">
                        Admin Panel
                    </span>
                @endauth
            </a>

            <!-- SISI KANAN: SEMUA NAVIGASI & TOMBOL AKSI (DESKTOP - FLOATING MODERN PILL) -->
            <nav class="hidden md:flex items-center">
                <div class="bg-slate-100/90 backdrop-blur-sm p-1.5 rounded-2xl border border-slate-200/70 inline-flex items-center gap-1.5 shadow-sm">
                    <a href="{{ route('home') }}" 
                       class="{{ $isHome ? 'bg-blue-600 text-white px-3.5 py-1.5 text-xs font-semibold rounded-xl shadow-xs' : 'text-slate-600 hover:text-slate-900 px-3 py-1.5 text-xs font-semibold rounded-xl hover:bg-white/80' }} transition flex items-center gap-1.5">
                        <i class="fas fa-home text-xs {{ $isHome ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Beranda</span>
                    </a>

                    <a href="{{ route('registrasi') }}" 
                       class="{{ $isBukuTamu ? 'bg-blue-600 text-white px-3.5 py-1.5 text-xs font-semibold rounded-xl shadow-xs' : 'text-slate-600 hover:text-slate-900 px-3 py-1.5 text-xs font-semibold rounded-xl hover:bg-white/80' }} transition flex items-center gap-1.5">
                        <i class="fas fa-edit text-xs {{ $isBukuTamu ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Buku Tamu</span>
                    </a>

                    @auth
                        <div class="h-4 w-[1px] bg-slate-300/80 mx-1"></div>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="bg-[#1e3a8a] hover:bg-blue-950 text-white px-3.5 py-1.5 text-xs font-semibold rounded-xl shadow-xs transition flex items-center gap-1.5">
                            <i class="fas fa-chart-pie text-xs text-blue-300"></i>
                            <span>Dashboard</span>
                        </a>
                        <button type="button" onclick="openLogoutModal()" class="text-rose-600 hover:bg-rose-50 px-3 py-1.5 text-xs font-semibold rounded-xl transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fas fa-sign-out-alt text-xs"></i>
                            <span>Keluar</span>
                        </button>
                    @endauth

                    <div class="h-4 w-[1px] bg-slate-300/80 mx-1"></div>

                    <!-- Tombol Rating Bintang Kuning '5.0 Beri Ulasan' -->
                    <button type="button" onclick="scrollToFeedbackAndOpenModal()" class="bg-amber-50 text-amber-700 border border-amber-200/80 hover:bg-amber-100 px-3 py-1.5 text-xs font-semibold rounded-xl shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                        <i class="fas fa-star text-amber-500"></i>
                        <span>{{ $navAvgRating }}</span>
                        <span class="hidden lg:inline">Beri Ulasan</span>
                    </button>
                </div>
            </nav>

            <!-- TOMBOL HAMBURGER MOBILE -->
            <div class="flex items-center md:hidden gap-2">
                @auth
                    <span class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold border border-blue-200">
                        Admin
                    </span>
                @endauth
                <button type="button" id="mobileMenuBtn" class="p-2 rounded-xl bg-slate-200/80 text-slate-700 hover:bg-slate-300 shadow-sm transition-all duration-200 active:scale-[0.98] focus:outline-none">
                    <i class="fas fa-bars text-lg" id="mobileMenuIcon"></i>
                </button>
            </div>

        </div>

        <!-- DROPDOWN MENU MOBILE -->
        <div id="mobileMenu" class="hidden md:hidden pb-4 pt-2 border-t border-slate-200/80 animate-fade-in-up">
            <div class="flex flex-col gap-2">
                <a href="{{ route('home') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold {{ $isHome ? 'bg-blue-600 text-white' : 'text-slate-700 bg-white border border-slate-200' }} shadow-sm hover:shadow-md transition-all duration-200 active:scale-[0.98] flex items-center gap-2">
                    <i class="fas fa-home text-xs {{ $isHome ? 'text-white' : 'text-slate-500' }}"></i>
                    <span>Beranda Utama</span>
                </a>
                <a href="{{ route('registrasi') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold {{ $isBukuTamu ? 'bg-blue-600 text-white' : 'text-slate-700 bg-white border border-slate-200' }} shadow-sm hover:shadow-md transition-all duration-200 active:scale-[0.98] flex items-center gap-2">
                    <i class="fas fa-edit text-xs {{ $isBukuTamu ? 'text-white' : 'text-slate-500' }}"></i>
                    <span>Buku Tamu</span>
                </a>
                <button type="button" onclick="scrollToFeedbackAndOpenModal()" class="w-full text-left px-4 py-2.5 rounded-xl text-sm font-semibold text-amber-900 bg-amber-50 border border-amber-200 shadow-sm hover:shadow-md transition-all duration-200 active:scale-[0.98] flex items-center gap-2">
                    <i class="fas fa-star text-xs text-amber-500"></i>
                    <span>Beri Ulasan &amp; Nilai Pelayanan</span>
                </button>

                @auth
                    <div class="my-1 border-t border-slate-200"></div>
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold bg-slate-900 text-white shadow-sm hover:shadow-md transition-all duration-200 active:scale-[0.98] flex items-center gap-2">
                        <i class="fas fa-chart-pie text-xs text-sky-400"></i>
                        <span>Dashboard Admin</span>
                    </a>
                    <button type="button" onclick="openLogoutModal()" class="w-full px-4 py-2.5 rounded-xl text-sm font-semibold text-rose-600 bg-rose-50 border border-rose-100 shadow-sm hover:shadow-md transition-all duration-200 active:scale-[0.98] flex items-center gap-2">
                        <i class="fas fa-sign-out-alt text-xs"></i>
                        <span>Keluar dari Akun</span>
                    </button>
                @endauth
            </div>
        </div>

    </div>
</header>

<!-- MODAL KONFIRMASI LOGOUT ADMIN BERSAMA -->
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
            <button type="button" onclick="closeLogoutModal()" class="w-full py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 font-semibold text-xs sm:text-sm hover:bg-slate-100 shadow-sm hover:shadow-md active:scale-[0.98] transition-all duration-200 cursor-pointer">
                Batal
            </button>
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs sm:text-sm shadow-sm hover:shadow-md active:scale-[0.98] transition-all duration-200 cursor-pointer">
                    Ya, Keluar
                </button>
            </form>
        </div>
    </div>
</div>
@endauth

<script>
    function openLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if (modal) modal.classList.add('hidden');
    }

    function scrollToFeedbackAndOpenModal() {
        const menu = document.getElementById('mobileMenu');
        const icon = document.getElementById('mobileMenuIcon');
        if (menu && !menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
            if (icon) {
                icon.classList.remove('fa-xmark', 'fa-times');
                icon.classList.add('fa-bars');
            }
        }

        const section = document.getElementById('penilaian-publik');
        if (section) {
            section.scrollIntoView({ behavior: 'smooth' });
            if (typeof openFeedbackModal === 'function') {
                setTimeout(() => {
                    openFeedbackModal();
                }, 450);
            }
        } else if (typeof openFeedbackModal === 'function') {
            openFeedbackModal();
        } else {
            window.location.href = "{{ route('home') }}#penilaian-publik";
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobileMenuBtn');
        const menu = document.getElementById('mobileMenu');
        const icon = document.getElementById('mobileMenuIcon');

        if (btn && menu && icon && !btn.dataset.boundNavbar) {
            btn.dataset.boundNavbar = 'true';
            btn.addEventListener('click', function() {
                menu.classList.toggle('hidden');
                if (menu.classList.contains('hidden')) {
                    icon.classList.remove('fa-xmark', 'fa-times');
                    icon.classList.add('fa-bars');
                } else {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-xmark');
                }
            });
        }
    });
</script>

