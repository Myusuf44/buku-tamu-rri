@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 animate-fade-in-up">
    
    <!-- HEADER HERO KELOLA ULASAN -->
    <div class="rounded-3xl bg-white border border-slate-100 shadow-sm p-6 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1 text-left">
            <div class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-amber-600">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Evaluasi Publik &amp; Pelayanan</span>
            </div>

            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Kelola Penilaian &amp; Ulasan
            </h1>

            <p class="text-xs text-slate-500 font-medium">
                Pantau rekapitulasi rating kepuasan masyarakat, filter ulasan berdasarkan sumber, dan cetak laporan berkala.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <!-- Tombol Ekspor Laporan Ulasan -->
            <button type="button" 
                    onclick="openExportFeedbackModal()" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 active:scale-95 text-white font-semibold rounded-xl shadow-sm transition-all duration-200 text-xs cursor-pointer">
                <i class="fas fa-file-export text-xs"></i>
                <span>Cetak / Ekspor Ulasan</span>
            </button>

            <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 active:scale-95 text-white font-semibold rounded-xl shadow-sm transition-all duration-200 text-xs">
                <i class="fas fa-arrow-left text-xs"></i>
                <span>Dashboard Utama</span>
            </a>
        </div>
    </div>

    <!-- METRIC CARDS STATISTIK ULASAN -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 animate-fade-in-up delay-100">
        <div class="rounded-3xl bg-white border border-slate-100 shadow-sm p-6 flex flex-col justify-between group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-amber-700">Rata-Rata Rating</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-star text-xs"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-amber-600 tracking-tight">{{ number_format($rataRating ?? 0, 1) }} <span class="text-xs text-slate-400 font-semibold">/ 5.0</span></div>
                <p class="text-[11px] text-slate-400 mt-0.5">Skala kepuasan pelayanan</p>
            </div>
        </div>

        <div class="rounded-3xl bg-white border border-slate-100 shadow-sm p-6 flex flex-col justify-between group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-slate-500">Total Responden</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-users text-xs"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($totalResponden ?? 0) }}</div>
                <p class="text-[11px] text-slate-400 mt-0.5">Pengguna memberikan ulasan</p>
            </div>
        </div>

        <div class="rounded-3xl bg-white border border-slate-100 shadow-sm p-6 flex flex-col justify-between group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-slate-500">Via Beranda</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-globe text-xs"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-indigo-600 tracking-tight">{{ number_format($totalBeranda ?? 0) }}</div>
                <p class="text-[11px] text-slate-400 mt-0.5">Formulir online publik</p>
            </div>
        </div>

        <div class="rounded-3xl bg-white border border-slate-100 shadow-sm p-6 flex flex-col justify-between group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-slate-500">Puas (Bintang 4-5)</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-smile text-xs"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-emerald-600 tracking-tight">{{ number_format($totalPuas ?? 0) }}</div>
                <p class="text-[11px] text-slate-400 mt-0.5">Responden sangat puas</p>
            </div>
        </div>
    </div>

    <!-- FILTER & PENCARIAN DATA ULASAN -->
    <div class="rounded-3xl bg-white border border-slate-100 shadow-sm p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold shrink-0">
                    <i class="fas fa-filter text-xs"></i>
                </div>
                <h2 class="text-xs sm:text-sm font-extrabold uppercase tracking-wide text-slate-800">FILTER &amp; PENCARIAN ULASAN</h2>
            </div>

            <!-- Pill Buttons Filter Rating Cepat -->
            <div class="flex flex-wrap items-center gap-1.5">
                <a href="{{ route('admin.feedbacks', array_merge(request()->except('rating', 'page'), [])) }}"
                   class="rounded-xl py-2 px-3 text-xs font-semibold transition-all duration-200 active:scale-95 inline-flex items-center gap-1 {{ !request()->filled('rating') ? 'bg-amber-600 text-white shadow-xs' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                    <span>Semua</span>
                </a>
                @for($star = 5; $star >= 1; $star--)
                    <a href="{{ route('admin.feedbacks', array_merge(request()->except('page'), ['rating' => $star])) }}"
                       class="rounded-xl py-2 px-3 text-xs font-semibold transition-all duration-200 active:scale-95 inline-flex items-center gap-1 {{ (string) request('rating') === (string) $star ? 'bg-amber-600 text-white shadow-xs' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                        <i class="fas fa-star text-[10px] {{ (string) request('rating') === (string) $star ? 'text-amber-200' : 'text-amber-400' }}"></i>
                        <span>{{ $star }}</span>
                    </a>
                @endfor
            </div>
        </div>

        <form action="{{ route('admin.feedbacks') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-3">
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">CARI NAMA / ULASAN</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau pesan..." 
                           class="w-full pl-9 pr-3.5 py-2 bg-slate-50/70 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-600/30 transition-all">
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">TANGGAL SPESIFIK</label>
                <input type="date" name="tanggal_exact" value="{{ request('tanggal_exact') }}" 
                       class="w-full px-3 py-2 bg-slate-50/70 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-600/30 transition-all">
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">BULAN</label>
                <select name="bulan" class="w-full px-3 py-2 bg-slate-50/70 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-600/30 transition-all">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ sprintf('%02d', $m) }}" {{ request('bulan') == sprintf('%02d', $m) ? 'selected' : '' }}> {{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">SUMBER RATING</label>
                <select name="sumber" class="w-full px-3 py-2 bg-slate-50/70 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-600/30 transition-all">
                    <option value="">Semua Sumber</option>
                    <option value="beranda" {{ strtolower(request('sumber')) == 'beranda' ? 'selected' : '' }}>Beranda</option>
                    <option value="whatsapp" {{ strtolower(request('sumber')) == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                    <option value="email" {{ strtolower(request('sumber')) == 'email' ? 'selected' : '' }}>Email</option>
                </select>
            </div>

            <div class="md:col-span-1">
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">RATING</label>
                <select name="rating" class="w-full px-2 py-2 bg-slate-50/70 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-600/30 transition-all">
                    <option value="">Semua</option>
                    @for($i=5; $i>=1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                    @endfor
                </select>
            </div>

            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-amber-600 hover:bg-amber-700 active:scale-95 text-white font-semibold rounded-xl text-xs shadow-sm transition-all duration-200 flex items-center justify-center gap-1.5">
                    <i class="fas fa-filter text-xs"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('admin.feedbacks') }}" title="Reset Filter" class="py-2 px-3 bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 active:scale-95 font-semibold rounded-xl text-xs transition-all duration-200 flex items-center justify-center shrink-0">
                    <i class="fas fa-redo-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- CONTAINER UTAMA DAFTAR ULASAN -->
    <div class="rounded-3xl bg-white border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm sm:text-base font-extrabold text-slate-900">Daftar Seluruh Responden Ulasan</h3>
            <span class="text-xs font-bold text-slate-500">Total Ditampilkan: <span class="text-slate-800">{{ $feedbacks->count() }} Data</span></span>
        </div>

        <!-- MOBILE CARD VIEW (block md:hidden) -->
        <div class="block md:hidden p-3 bg-slate-50/50 space-y-3">
            @forelse($feedbacks as $fIndex => $fb)
                @php
                    $fbHpClean = preg_replace('/[^0-9]/', '', $fb->no_hp);
                    if (str_starts_with($fbHpClean, '0')) {
                        $fbHpClean = '62' . substr($fbHpClean, 1);
                    }
                    $noUrut = ($feedbacks->currentPage() - 1) * $feedbacks->perPage() + $fIndex + 1;
                    $sumberLower = strtolower($fb->sumber);
                    $waktuFormatted = \Carbon\Carbon::parse($fb->created_at)->locale('id')->isoFormat('D MMM YYYY');
                    $jamFormatted = \Carbon\Carbon::parse($fb->created_at)->format('H:i');
                @endphp

                <div class="bg-white p-4 rounded-2xl border border-slate-200/80 border-l-4 border-l-amber-400 shadow-xs">
                    <!-- Header Kartu: Nama Tamu + Jumlah Bintang + Tanggal Ulasan -->
                    <div class="flex items-start justify-between gap-2 pb-2.5 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 font-mono text-[10px] font-bold">#{{ $noUrut }}</span>
                                <span class="font-bold text-slate-800 text-sm">{{ $fb->nama_pengunjung }}</span>
                            </div>
                            <div class="text-xs text-slate-500 flex items-center gap-1 mt-1">
                                <i class="far fa-calendar-alt text-[10px] text-slate-400"></i>
                                <span>{{ $waktuFormatted }}</span>
                                <span class="text-slate-300">•</span>
                                <span class="font-mono text-[11px]">{{ $jamFormatted }} WIB</span>
                            </div>
                        </div>

                        <div class="text-right shrink-0">
                            <div class="flex items-center justify-end text-amber-400 text-xs gap-0.5">
                                @for($s = 1; $s <= 5; $s++)
                                    <i class="fas fa-star {{ $s <= $fb->rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                @endfor
                            </div>
                            <span class="text-[11px] font-bold text-amber-700 mt-0.5 inline-block">{{ $fb->rating }}.0 / 5</span>
                        </div>
                    </div>

                    <!-- Badge Sumber & Kontak -->
                    <div class="flex flex-wrap items-center justify-between gap-2 mt-2.5">
                        <span class="text-[11px] text-slate-500 font-mono">{{ $fb->no_hp ?: '-' }}</span>
                        @if($sumberLower == 'whatsapp' || $sumberLower == 'wa')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                WHATSAPP
                            </span>
                        @elseif($sumberLower == 'email')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-200">
                                EMAIL
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-50 text-amber-800 border border-amber-200">
                                BERANDA
                            </span>
                        @endif
                    </div>

                    <!-- Isi: Teks Komentar/Ulasan Tamu -->
                    <div class="bg-slate-50/80 rounded-xl p-3 text-xs text-slate-700 italic leading-relaxed my-3 border border-slate-100">
                        "{{ $fb->ulasan }}"
                    </div>

                    <!-- Footer Kartu: Tombol Aksi Selebar Kartu -->
                    <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center gap-2">
                        <button type="button" 
                                onclick="openFeedbackDetailModal('{{ addslashes($fb->nama_pengunjung) }}', '{{ $fb->rating }}.0 / 5.0', '{{ addslashes($fb->ulasan) }}', '{{ strtoupper($fb->sumber) }}', '{{ $fb->no_hp ?: '-' }}', '{{ $fb->email ?: '-' }}', '{{ \Carbon\Carbon::parse($fb->created_at)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB')"
                                class="flex-1 rounded-xl font-semibold text-xs py-2 px-3 bg-blue-50 text-blue-600 border border-blue-200/80 hover:bg-blue-100 transition-all duration-200 active:scale-95 inline-flex items-center justify-center gap-1.5">
                            <i class="fas fa-eye text-[11px]"></i>
                            <span>Tampilkan</span>
                        </button>

                        @if($fb->no_hp && $fb->no_hp !== '-')
                            <a href="https://wa.me/{{ $fbHpClean }}" target="_blank" title="Balas via WhatsApp"
                               class="rounded-xl font-semibold text-xs py-2 px-3 bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition-all duration-200 active:scale-95 inline-flex items-center justify-center gap-1.5">
                                <i class="fab fa-whatsapp text-xs"></i>
                                <span>Balas WA</span>
                            </a>
                        @endif

                        @if($fb->email)
                            <a href="mailto:{{ $fb->email }}" title="Balas via Email"
                               class="rounded-xl font-semibold text-xs py-2 px-3 bg-slate-100 text-slate-700 border border-slate-200 hover:bg-slate-200 transition-all duration-200 active:scale-95 inline-flex items-center justify-center gap-1.5">
                                <i class="far fa-envelope text-xs"></i>
                                <span>Email</span>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-10 text-center text-slate-400 bg-white rounded-2xl border border-slate-200/80">
                    <i class="fas fa-star-half-alt text-3xl mb-2 block"></i>
                    <p class="text-xs font-bold uppercase tracking-wider">Belum Ada Data Penilaian Pelayanan</p>
                </div>
            @endforelse
        </div>

        <!-- DESKTOP TABLE VIEW (hidden md:table) -->
        <div class="hidden md:block w-full overflow-hidden">
            <table class="hidden md:table w-full table-auto text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100/90 border-b-2 border-slate-200 text-[10px] uppercase font-black text-slate-700 tracking-wider">
                        <th class="py-3 px-3 text-center w-12 border-r border-slate-200/80">No.</th>
                        <th class="py-3 px-3 border-r border-slate-200/80">Waktu Kirim</th>
                        <th class="py-3 px-3 border-r border-slate-200/80">Identitas Responden</th>
                        <th class="py-3 px-3 text-center border-r border-slate-200/80">Rating</th>
                        <th class="py-3 px-3 border-r border-slate-200/80">Ulasan / Kesan Pelayanan</th>
                        <th class="py-3 px-3 text-center border-r border-slate-200/80">Sumber</th>
                        <th class="py-3 px-3 text-center">Kontak Direct</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs text-slate-700 font-medium">
                    @forelse($feedbacks as $fIndex => $fb)
                        @php
                            $fbHpClean = preg_replace('/[^0-9]/', '', $fb->no_hp);
                            if (str_starts_with($fbHpClean, '0')) $fbHpClean = '62' . substr($fbHpClean, 1);
                            $noUrut = ($feedbacks->currentPage() - 1) * $feedbacks->perPage() + $fIndex + 1;
                            
                            $sumberLower = strtolower($fb->sumber);
                        @endphp
                        <tr class="{{ $fIndex % 2 == 0 ? 'bg-white' : 'bg-slate-50/70' }} hover:bg-amber-50/30 transition-colors">
                            <td class="py-3 px-3 text-center font-mono font-bold text-slate-600 bg-slate-100/50 border-r border-slate-200/80">
                                {{ $noUrut }}
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap border-r border-slate-100">
                                <div class="font-bold text-slate-900 text-xs">{{ \Carbon\Carbon::parse($fb->created_at)->locale('id')->isoFormat('D MMM YYYY') }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ \Carbon\Carbon::parse($fb->created_at)->format('H:i') }} WIB</div>
                            </td>
                            <td class="py-3 px-3 border-r border-slate-100">
                                <div class="font-extrabold text-slate-900">{{ $fb->nama_pengunjung }}</div>
                                <div class="text-[11px] text-slate-500 font-mono">{{ $fb->no_hp ?: '-' }}</div>
                            </td>
                            <td class="py-3 px-3 text-center whitespace-nowrap border-r border-slate-100">
                                <div class="flex items-center justify-center text-amber-400 text-xs gap-0.5">
                                    @for($s = 1; $s <= 5; $s++)
                                        <i class="fas fa-star {{ $s <= $fb->rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                    @endfor
                                </div>
                                <span class="font-bold text-slate-800 text-xs block mt-0.5">{{ $fb->rating }}.0 / 5</span>
                            </td>
                            <td class="py-3 px-3 min-w-[220px] max-w-[320px] border-r border-slate-100">
                                <p class="text-xs text-slate-600 italic leading-relaxed line-clamp-2 mb-2">
                                    "{{ $fb->ulasan }}"
                                </p>
                                <button type="button" 
                                        onclick="openFeedbackDetailModal('{{ addslashes($fb->nama_pengunjung) }}', '{{ $fb->rating }}.0 / 5.0', '{{ addslashes($fb->ulasan) }}', '{{ strtoupper($fb->sumber) }}', '{{ $fb->no_hp ?: '-' }}', '{{ $fb->email ?: '-' }}', '{{ \Carbon\Carbon::parse($fb->created_at)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB')"
                                        class="inline-flex items-center gap-1.5 rounded-xl font-semibold text-xs py-2 px-3 text-blue-600 hover:text-blue-700 bg-blue-50/70 hover:bg-blue-100/70 border border-blue-200/60 transition-all duration-200 active:scale-95">
                                    <i class="fas fa-eye text-[11px]"></i>
                                    <span>Tampilkan Rincian</span>
                                </button>
                            </td>
                            
                            <!-- BADGE KONTRASTIF WARNA SUMBER -->
                            <td class="py-3 px-3 text-center whitespace-nowrap border-r border-slate-100">
                                @if($sumberLower == 'whatsapp' || $sumberLower == 'wa')
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-50 text-emerald-700 border border-emerald-300 shadow-xs">
                                        WHATSAPP
                                    </span>
                                @elseif($sumberLower == 'email')
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-blue-50 text-blue-700 border border-blue-300 shadow-xs">
                                        EMAIL
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-amber-50 text-amber-800 border border-amber-300 shadow-xs">
                                        BERANDA
                                    </span>
                                @endif
                            </td>

                            <td class="py-3 px-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if($fb->no_hp && $fb->no_hp !== '-')
                                        <a href="https://wa.me/{{ $fbHpClean }}" target="_blank" title="Balas via WhatsApp" class="rounded-xl font-semibold text-xs py-2 px-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-200 transition-all duration-200 active:scale-95 inline-flex items-center gap-1">
                                            <i class="fab fa-whatsapp text-sm"></i>
                                            <span>Balas</span>
                                        </a>
                                    @endif
                                    @if($fb->email)
                                        <a href="mailto:{{ $fb->email }}" title="Balas via Email" class="rounded-xl font-semibold text-xs py-2 px-3 bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200 transition-all duration-200 active:scale-95 inline-flex items-center gap-1">
                                            <i class="far fa-envelope text-sm"></i>
                                            <span>Email</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i class="fas fa-star-half-alt text-3xl mb-2 block"></i>
                                <p class="text-xs font-bold uppercase tracking-wider">Belum Ada Data Penilaian Pelayanan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($feedbacks->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $feedbacks->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>

<!-- MODAL CETAK / EKSPOR LAPORAN ULASAN -->
<div id="exportFeedbackModal" class="hidden fixed inset-0 z-[999999] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-[2.5rem] max-w-lg w-full p-6 sm:p-8 shadow-2xl relative animate-fade-in-up max-h-[90vh] overflow-y-auto">
        
        <button type="button" onclick="closeExportFeedbackModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 font-bold w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
            &times;
        </button>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <i class="fas fa-file-export text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900">Cetak & Ekspor Laporan Ulasan</h3>
                <p class="text-xs text-slate-500">Pilih format berkas dan filter periode ulasan</p>
            </div>
        </div>

        <form action="{{ route('admin.export.feedback.pdf') }}" method="GET" id="exportFeedbackForm" target="_blank" class="space-y-4">
            
            <!-- Format Berkas -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1.5">Format Berkas</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center justify-center gap-2 p-3.5 rounded-2xl border border-slate-200 cursor-pointer hover:bg-slate-50 font-bold text-xs text-slate-700 transition-all">
                        <input type="radio" name="format_file" value="pdf" checked>
                        <i class="fas fa-file-pdf text-rose-600 text-sm"></i> Dokumen PDF
                    </label>
                    <label class="flex items-center justify-center gap-2 p-3.5 rounded-2xl border border-slate-200 cursor-pointer hover:bg-slate-50 font-bold text-xs text-slate-700 transition-all">
                        <input type="radio" name="format_file" value="excel">
                        <i class="fas fa-file-excel text-emerald-600 text-sm"></i> Microsoft Excel (.xlsx)
                    </label>
                </div>
            </div>

            <!-- Sumber Rating -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1.5">Sumber Rating</label>
                <select name="sumber" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-600/30 transition-all">
                    <option value="">Semua Sumber (Keseluruhan)</option>
                    <option value="beranda">Beranda</option>
                    <option value="whatsapp">WhatsApp</option>
                    <option value="email">Email</option>
                </select>
            </div>

            <!-- Grid Filter Periode -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1.5">Per Tanggal</label>
                    <input type="date" name="tanggal" id="exportFeedbackTanggal" onchange="resetFeedbackBulanTahun()" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-600/30 transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1.5">Per Bulan</label>
                    <select name="bulan" id="exportFeedbackBulan" onchange="resetFeedbackTanggalSpesifik()" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-600/30 transition-all">
                        <option value="">Pilih Bulan</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ sprintf('%02d', $m) }}">{{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1.5">Per Tahun</label>
                    <input type="number" name="tahun" id="exportFeedbackTahun" onchange="resetFeedbackTanggalSpesifik()" min="2000" max="{{ date('Y') }}" placeholder="Cth: {{ date('Y') }}" value=""
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-600/30 transition-all">
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-2 flex items-center gap-3">
                <button type="button" onclick="closeExportFeedbackModal()" class="w-1/3 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold text-xs transition-all">
                    Batal
                </button>
                <button type="button" onclick="submitFeedbackExport(event)" class="w-2/3 py-3.5 rounded-2xl bg-gradient-to-r from-amber-600 via-amber-700 to-amber-800 hover:from-amber-700 hover:to-amber-900 active:scale-95 text-white font-extrabold text-xs tracking-wide shadow-lg shadow-amber-600/25 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-cloud-download-alt text-sm"></i>
                    <span>Unduh Laporan Ulasan</span>
                </button>
            </div>

        </form>
    </div>
</div>

<!-- MODAL RINCIAN LENGKAP ULASAN -->
<div id="feedbackDetailModal" class="hidden fixed inset-0 z-[999999] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-[2.5rem] max-w-lg w-full p-6 sm:p-8 shadow-2xl relative animate-fade-in-up max-h-[90vh] overflow-y-auto">
        
        <button type="button" onclick="closeFeedbackDetailModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 font-bold w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
            &times;
        </button>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold shrink-0">
                <i class="fas fa-star text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900">Rincian Lengkap Ulasan</h3>
                <p class="text-xs text-slate-500">Detail penilaian dan kesan pelayanan responden</p>
            </div>
        </div>

        <div class="space-y-4 text-left">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-3">
                <div>
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-0.5">Nama Responden</span>
                    <span id="modalRespondenNama" class="text-sm font-extrabold text-slate-900">-</span>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-200/60">
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-0.5">Nomor Kontak / HP</span>
                        <span id="modalRespondenHp" class="text-xs font-bold text-slate-700">-</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-0.5">Alamat Email</span>
                        <span id="modalRespondenEmail" class="text-xs font-bold text-slate-700">-</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-200/60">
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-0.5">Rating Kepuasan</span>
                        <span id="modalRespondenRating" class="text-xs font-extrabold text-amber-600">-</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-0.5">Sumber Penilaian</span>
                        <span id="modalRespondenSumber" class="text-xs font-bold text-slate-700">-</span>
                    </div>
                </div>
                <div>
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-0.5">Waktu Pengiriman</span>
                    <span id="modalRespondenWaktu" class="text-xs font-bold text-slate-700">-</span>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1.5">Ulasan / Kesan Pelayanan Lengkap</label>
                <div class="p-4 rounded-2xl bg-amber-50/40 border border-amber-100 text-slate-800 text-xs sm:text-sm font-semibold italic leading-relaxed" id="modalRespondenUlasan">
                    -
                </div>
            </div>
        </div>

        <!-- TOMBOL AKSI DIRECT & TUTUP -->
        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-2">
            <!-- Tombol Direct WhatsApp -->
            <a id="modalBtnWa" href="#" target="_blank" class="hidden flex-1 py-3 px-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-extrabold text-xs tracking-wide transition-all shadow-md shadow-emerald-600/20 items-center justify-center gap-2">
                <i class="fab fa-whatsapp text-sm"></i>
                <span>Hubungi WA</span>
            </a>

            <!-- Tombol Direct Email -->
            <a id="modalBtnEmail" href="#" target="_blank" class="hidden flex-1 py-3 px-3 rounded-2xl bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-extrabold text-xs tracking-wide transition-all shadow-md shadow-blue-600/20 items-center justify-center gap-2">
                <i class="far fa-envelope text-sm"></i>
                <span>Kirim Email</span>
            </a>

            <!-- Tombol Tutup Rincian -->
            <button type="button" onclick="closeFeedbackDetailModal()" class="flex-1 py-3 px-3 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs tracking-wide transition-all shadow-md">
                Tutup Rincian
            </button>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openExportFeedbackModal() {
        const modal = document.getElementById('exportFeedbackModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeExportFeedbackModal() {
        const modal = document.getElementById('exportFeedbackModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function openFeedbackDetailModal(nama, rating, ulasan, sumber, hp, email, waktu) {
        document.getElementById('modalRespondenNama').innerText = nama;
        document.getElementById('modalRespondenRating').innerText = rating;
        document.getElementById('modalRespondenUlasan').innerText = '"' + ulasan + '"';
        document.getElementById('modalRespondenSumber').innerText = sumber;
        document.getElementById('modalRespondenHp').innerText = hp;
        document.getElementById('modalRespondenEmail').innerText = email;
        document.getElementById('modalRespondenWaktu').innerText = waktu;

        // Pengaturan Tombol Direct WhatsApp
        const btnWa = document.getElementById('modalBtnWa');
        if (hp && hp !== '-' && hp.trim() !== '') {
            let cleanHp = hp.replace(/[^0-9]/g, '');
            if (cleanHp.startsWith('0')) cleanHp = '62' + cleanHp.slice(1);
            
            const textWa = encodeURIComponent(`Halo Bpk/Ibu ${nama}, terima kasih atas penilaian dan ulasan yang diberikan untuk pelayanan LPP RRI Bukittinggi.`);
            btnWa.href = `https://wa.me/${cleanHp}?text=${textWa}`;
            btnWa.classList.remove('hidden');
            btnWa.classList.add('flex');
        } else {
            btnWa.classList.add('hidden');
            btnWa.classList.remove('flex');
        }

        // Pengaturan Tombol Direct Email
        const btnEmail = document.getElementById('modalBtnEmail');
        if (email && email !== '-' && email.trim() !== '') {
            const subject = encodeURIComponent(`Tanggapan Ulasan Pelayanan LPP RRI Bukittinggi`);
            btnEmail.href = `mailto:${email}?subject=${subject}`;
            btnEmail.classList.remove('hidden');
            btnEmail.classList.add('flex');
        } else {
            btnEmail.classList.add('hidden');
            btnEmail.classList.remove('flex');
        }

        const modal = document.getElementById('feedbackDetailModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeFeedbackDetailModal() {
        const modal = document.getElementById('feedbackDetailModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function resetFeedbackBulanTahun() {
        const tgl = document.getElementById('exportFeedbackTanggal');
        if (tgl && tgl.value) {
            document.getElementById('exportFeedbackBulan').value = '';
            document.getElementById('exportFeedbackTahun').value = '';
        }
    }

    function resetFeedbackTanggalSpesifik() {
        const bln = document.getElementById('exportFeedbackBulan');
        const thn = document.getElementById('exportFeedbackTahun');
        if ((bln && bln.value) || (thn && thn.value)) {
            document.getElementById('exportFeedbackTanggal').value = '';
        }
    }

    function submitFeedbackExport(event) {
        if (event) event.preventDefault();
        
        const form = document.getElementById('exportFeedbackForm');
        const formData = new FormData(form);
        
        const formatFileRadio = document.querySelector('#exportFeedbackForm input[name="format_file"]:checked');
        const formatFile = formatFileRadio ? formatFileRadio.value : 'pdf';
        
        const targetActionUrl = (formatFile === 'excel') 
            ? "{{ route('admin.export.feedback.excel') }}" 
            : "{{ route('admin.export.feedback.pdf') }}";

        const cleanParams = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            if (key !== 'format_file' && value !== null && value.toString().trim() !== '') {
                cleanParams.append(key, value);
            }
        }

        const fullUrl = targetActionUrl + (cleanParams.toString() ? '?' + cleanParams.toString() : '');

        const btnSubmit = event.target.closest('button') || document.querySelector('#exportFeedbackForm button[type="button"]:last-child');
        const oldBtnText = btnSubmit ? btnSubmit.innerHTML : '';
        if (btnSubmit) {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Menyiapkan Berkas...</span>';
        }

        fetch(fullUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok || response.status === 404) {
                throw new Error('Data ulasan tidak ditemukan');
            }
            
            closeExportFeedbackModal();

            if (formatFile === 'pdf') {
                return response.blob().then(blob => {
                    const blobUrl = URL.createObjectURL(blob);
                    window.open(blobUrl, '_blank');
                });
            } else {
                window.location.href = fullUrl;
            }
        })
        .catch(error => {
            closeExportFeedbackModal();

            Swal.fire({
                html: `
                    <div class="flex flex-col items-center text-center p-2">
                        <div class="w-20 h-20 rounded-3xl bg-amber-50 border border-amber-200/60 text-amber-500 flex items-center justify-center mb-5 shadow-xl shadow-amber-500/10 transition-transform hover:scale-105 duration-300">
                            <i class="fas fa-folder-open text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight mb-2">
                            Data Ulasan Tidak Ditemukan
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed max-w-xs mb-2">
                            Maaf, belum ada catatan penilaian ulasan untuk periode atau filter sumber yang Anda pilih.
                        </p>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: '<span class="flex items-center justify-center gap-2"><i class="fas fa-check-circle text-xs"></i> Saya Mengerti</span>',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-[2.5rem] shadow-2xl border border-slate-100/80 p-6 sm:p-8 bg-white/95 backdrop-blur-md max-w-md w-full',
                    confirmButton: 'w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-amber-600 via-amber-700 to-amber-800 hover:from-amber-700 hover:to-amber-900 text-white font-extrabold text-xs tracking-wide shadow-lg shadow-amber-600/30 active:scale-[0.98] transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-amber-600/20'
                }
            });
        })
        .finally(() => {
            if (btnSubmit) {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = oldBtnText;
            }
        });
    }
</script>
@endsection 