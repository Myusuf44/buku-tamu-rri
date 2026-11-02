@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 animate-fade-in-up">
    
    <!-- HEADER HERO DASHBOARD -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1 text-left">
            <div class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-blue-600">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                <span>LPP RRI Bukittinggi</span>
            </div>

            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Pusat Kendali Buku Tamu
            </h1>

            <p id="header-realtime-clock" class="text-xs text-slate-500 font-medium">
                {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd, DD MMMM YYYY • HH:mm') }} WIB
            </p>
        </div>

        <div class="shrink-0">
            <button type="button" 
                    onclick="openExportModal()" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold rounded-xl shadow-sm transition-all duration-200 text-xs cursor-pointer">
                <i class="fas fa-file-export text-xs"></i>
                <span>Cetak / Ekspor Laporan</span>
            </button>
        </div>
    </div>

    <!-- 4 KARTU METRIK UTAMA -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 animate-fade-in-up delay-100">
        <!-- 1. Total Pengunjung -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-slate-500">Total Pengunjung</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-users text-xs"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($totalKunjungan ?? 0) }}</div>
                <p class="text-[11px] text-slate-400 mt-0.5">Akumulasi seluruh tamu</p>
            </div>
        </div>

        <!-- 2. Perlu Diproses -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between relative overflow-hidden">
            @if(($menunggu ?? 0) > 0)
                <span class="absolute top-0 left-0 w-1 h-full bg-amber-400"></span>
            @endif
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-slate-500">Perlu Diproses</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-clock text-xs"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-amber-600 tracking-tight">{{ number_format($menunggu ?? 0) }}</div>
                <p class="text-[11px] text-slate-400 mt-0.5">Permohonan menunggu verifikasi</p>
            </div>
        </div>

        <!-- 3. Hari Ini -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-slate-500">Hari Ini</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-calendar-day text-xs"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($tamuHariIni ?? 0) }}</div>
                <p class="text-[11px] text-slate-400 mt-0.5">Jadwal kunjungan hari ini</p>
            </div>
        </div>

        <!-- 4. Selesai -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-slate-500">Selesai</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-check-circle text-xs"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-emerald-600 tracking-tight">{{ number_format($selesaiHariIni ?? 0) }}</div>
                <p class="text-[11px] text-slate-400 mt-0.5">Kunjungan tuntas hari ini</p>
            </div>
        </div>
    </div>

    <!-- MINI CARD HORIZONTAL: DISTRIBUSI KUNJUNGAN (SEGMENTED PROGRESS BAR) -->
    @php
        $jmlPemerintah = (int) ($rekapPerInstansi['Instansi Pemerintah'] ?? 0);
        $jmlSwasta = (int) ($rekapPerInstansi['Swasta'] ?? 0);
        $jmlUmum = (int) ($rekapPerInstansi['Masyarakat Umum'] ?? 0);
        $totalDistribusi = max($jmlPemerintah + $jmlSwasta + $jmlUmum, 1);

        $pctPemerintah = round(($jmlPemerintah / $totalDistribusi) * 100, 1);
        $pctSwasta = round(($jmlSwasta / $totalDistribusi) * 100, 1);
        $pctUmum = max(0, round(100 - $pctPemerintah - $pctSwasta, 1));
    @endphp
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 sm:px-5 mb-6 animate-fade-in-up delay-100">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2.5">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-800">Distribusi Kunjungan</span>
                <span class="text-[11px] text-slate-400">• Berdasarkan Asal Instansi</span>
            </div>
            <div class="flex flex-wrap items-center gap-4 text-xs">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                    <span class="text-slate-600 font-medium">Pemerintah:</span>
                    <span class="font-bold text-slate-900">{{ number_format($jmlPemerintah) }}</span>
                    <span class="text-[11px] text-slate-400">({{ $pctPemerintah }}%)</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                    <span class="text-slate-600 font-medium">Swasta:</span>
                    <span class="font-bold text-slate-900">{{ number_format($jmlSwasta) }}</span>
                    <span class="text-[11px] text-slate-400">({{ $pctSwasta }}%)</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span class="text-slate-600 font-medium">Umum:</span>
                    <span class="font-bold text-slate-900">{{ number_format($jmlUmum) }}</span>
                    <span class="text-[11px] text-slate-400">({{ $pctUmum }}%)</span>
                </div>
            </div>
        </div>
        <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden flex">
            @if(($jmlPemerintah + $jmlSwasta + $jmlUmum) > 0)
                <div style="width: {{ $pctPemerintah }}%" class="bg-blue-600 h-full transition-all duration-500" title="Instansi Pemerintah: {{ $jmlPemerintah }} Orang ({{ $pctPemerintah }}%)"></div>
                <div style="width: {{ $pctSwasta }}%" class="bg-purple-600 h-full transition-all duration-500" title="Swasta: {{ $jmlSwasta }} Orang ({{ $pctSwasta }}%)"></div>
                <div style="width: {{ $pctUmum }}%" class="bg-emerald-500 h-full transition-all duration-500" title="Masyarakat Umum: {{ $jmlUmum }} Orang ({{ $pctUmum }}%)"></div>
            @else
                <div class="w-full bg-slate-200 h-full"></div>
            @endif
        </div>
    </div>

    <!-- FILTER & PENCARIAN DATA KUNJUNGAN TAMU -->
    <div class="bg-white rounded-2xl sm:rounded-[2rem] shadow-lg shadow-slate-200/50 border border-slate-100 p-4 sm:p-6 mb-6 animate-fade-in-up delay-200">
        <div class="flex items-center justify-between mb-4 pb-2.5 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold shrink-0">
                    <i class="fas fa-filter text-xs sm:text-sm"></i>
                </div>
                <h2 class="text-xs sm:text-sm font-extrabold uppercase tracking-wide text-slate-800">FILTER & PENCARIAN DATA</h2>
            </div>
        </div>

        <form action="{{ route('admin.dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            
            <div class="md:col-span-3">
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">CARI NAMA / INSTANSI</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/instansi..." 
                           class="w-full pl-9 pr-3.5 py-2 bg-slate-50/70 border border-slate-200/90 rounded-xl sm:rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition-all">
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">TANGGAL SPESIFIK</label>
                <input type="date" name="tanggal_exact" value="{{ request('tanggal_exact') }}" 
                       class="w-full px-3 py-2 bg-slate-50/70 border border-slate-200/90 rounded-xl sm:rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition-all">
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">BULAN KUNJUNGAN</label>
                <select name="bulan" class="w-full px-3 py-2 bg-slate-50/70 border border-slate-200/90 rounded-xl sm:rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition-all">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ sprintf('%02d', $m) }}" {{ request('bulan') == sprintf('%02d', $m) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">DIVISI TUJUAN</label>
                <select name="divisi" class="w-full px-3 py-2 bg-slate-50/70 border border-slate-200/90 rounded-xl sm:rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition-all">
                    <option value="">Semua Divisi</option>
                    <option value="Kepala Stasiun (Kepsta)" {{ request('divisi') == 'Kepala Stasiun (Kepsta)' ? 'selected' : '' }}>Kepala Stasiun (Kepsta)</option>
                    <option value="Tata Usaha (TU)" {{ request('divisi') == 'Tata Usaha (TU)' ? 'selected' : '' }}>Tata Usaha (TU)</option>
                    <option value="Siaran" {{ request('divisi') == 'Siaran' ? 'selected' : '' }}>Siaran</option>
                    <option value="Pemberitaan" {{ request('divisi') == 'Pemberitaan' ? 'selected' : '' }}>Pemberitaan</option>
                    <option value="Teknologi dan Media Baru (TMB)" {{ request('divisi') == 'Teknologi dan Media Baru (TMB)' ? 'selected' : '' }}>Teknologi dan Media Baru (TMB)</option>
                    <option value="Layanan Pengembangan Usaha (LPU)" {{ request('divisi') == 'Layanan Pengembangan Usaha (LPU)' ? 'selected' : '' }}>Layanan Pengembangan Usaha (LPU)</option>
                    <option value="Lainnya" {{ request('divisi') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">ASAL INSTANSI</label>
                <select name="asal_instansi" class="w-full px-3 py-2 bg-slate-50/70 border border-slate-200/90 rounded-xl sm:rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition-all">
                    <option value="">Semua Instansi</option>
                    <option value="Instansi Pemerintah" {{ request('asal_instansi') == 'Instansi Pemerintah' ? 'selected' : '' }}>Pemerintah</option>
                    <option value="Swasta" {{ request('asal_instansi') == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                    <option value="Masyarakat Umum" {{ request('asal_instansi') == 'Masyarakat Umum' ? 'selected' : '' }}>Umum</option>
                </select>
            </div>

            <div class="md:col-span-1">
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">STATUS</label>
                <select name="status" class="w-full px-2 py-2 bg-slate-50/70 border border-slate-200/90 rounded-xl sm:rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition-all">
                    <option value="">Semua</option>
                    <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>

            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold rounded-xl sm:rounded-2xl text-xs shadow-md shadow-blue-500/20 transition-all flex items-center justify-center gap-1.5">
                    <i class="fas fa-filter text-xs"></i>
                    <span>Filter</span>
                </button>
                <button type="button" onclick="smoothRefreshDashboard('{{ route('admin.dashboard') }}')" title="Reset Filter" class="w-9 h-9 sm:w-10 sm:h-10 bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-600 font-bold rounded-xl sm:rounded-2xl text-xs transition-all flex items-center justify-center shrink-0">
                    <i class="fas fa-redo-alt"></i>
                </button>
            </div>

        </form>
    </div>

    <!-- DAFTAR REKAPITULASI KUNJUNGAN TAMU -->
    <div class="bg-white rounded-2xl sm:rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden animate-fade-in-up delay-300">
        
        <div class="px-4 sm:px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-1.5">
            <h3 class="text-sm sm:text-base font-extrabold text-slate-900">Daftar Rekapitulasi Kunjungan Tamu</h3>
            <span class="text-xs font-bold text-slate-500">
                Total Ditampilkan: <span class="text-slate-800">{{ $visitors->count() }} Data</span>
            </span>
        </div>

        <!-- MOBILE CARD VIEW (block md:hidden) -->
        <div class="block md:hidden p-3 bg-slate-50/50">
            @forelse($visitors as $index => $visitor)
                @php
                    $tglCarbon = \Carbon\Carbon::parse($visitor->tanggal_kunjungan);
                    $formattedDate = $tglCarbon->locale('id')->isoFormat('D MMM YYYY');
                    $jamClean = \Carbon\Carbon::parse($visitor->jam_kunjungan)->format('H:i');

                    $isAccepted = ($visitor->status == 'Diterima');
                    $canComplete = $isAccepted;

                    $hariIni = \Carbon\Carbon::today();
                    $isToday = $tglCarbon->isToday();
                    $isTomorrow = $tglCarbon->isTomorrow();
                    $isFuture = $tglCarbon->isAfter($hariIni);
                    $isPast = $tglCarbon->isBefore($hariIni);

                    $selisihHariLalu = (int) $tglCarbon->diffInDays($hariIni, false);
                    $nomorUrut = ($visitors->currentPage() - 1) * $visitors->perPage() + $index + 1;
                    $jmlOrang = (int) ($visitor->jumlah_pengunjung ?? 1);

                    $hpClean = preg_replace('/[^0-9]/', '', $visitor->no_hp);
                    if (str_starts_with($hpClean, '0')) {
                        $hpClean = '62' . substr($hpClean, 1);
                    }

                    $leftBorderClass = match ($visitor->status) {
                        'Diterima'   => 'border-l-4 border-l-blue-500',
                        'Selesai'    => 'border-l-4 border-l-emerald-500',
                        'Dibatalkan' => 'border-l-4 border-l-rose-500',
                        default      => 'border-l-4 border-l-amber-400',
                    };
                @endphp

                <div class="bg-white p-4 rounded-xl border border-slate-200/80 {{ $leftBorderClass }} shadow-sm mb-3 last:mb-0">
                    <!-- 1. Header Card Dinamis -->
                    <div class="pb-2.5 mb-2.5 border-b border-slate-100">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <div class="flex items-center gap-1.5">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-mono text-[10px] font-bold">#{{ $nomorUrut }}</span>
                                @if($isToday)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200 animate-pulse">
                                        <i class="fas fa-exclamation-circle text-[8px]"></i> HARI INI
                                    </span>
                                @elseif($isTomorrow)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">
                                        <i class="fas fa-clock text-[8px]"></i> BESOK
                                    </span>
                                @elseif($isFuture)
                                    @php $selisihMendatang = (int) $hariIni->diffInDays($tglCarbon, false); @endphp
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        <i class="fas fa-calendar-alt text-[8px]"></i> {{ $selisihMendatang <= 7 ? $selisihMendatang . ' Hari Lagi' : ceil($selisihMendatang / 7) . ' Mgg Lagi' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                        <i class="fas fa-history text-[8px]"></i> {{ $selisihHariLalu <= 7 ? $selisihHariLalu . ' Hari Lalu' : ceil($selisihHariLalu / 7) . ' Mgg Lalu' }}
                                    </span>
                                @endif
                            </div>

                            <div class="shrink-0">
                                @if($visitor->status == 'Selesai')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1">
                                        <i class="fas fa-check-circle text-[9px]"></i> Selesai
                                    </span>
                                @elseif($visitor->status == 'Diterima')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200 inline-flex items-center gap-1">
                                        <i class="fas fa-user-check text-[9px]"></i> Diterima
                                    </span>
                                @elseif($visitor->status == 'Dibatalkan')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200 inline-flex items-center gap-1" title="Alasan: {{ $visitor->alasan_batal ?? 'Tidak tercatat' }}">
                                        <i class="fas fa-times-circle text-[9px]"></i> Dibatalkan
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200 inline-flex items-center gap-1">
                                        <i class="fas fa-spinner text-[9px]"></i> Diproses
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="text-xs text-slate-500 flex items-center gap-1">
                            <i class="far fa-calendar-alt text-[10px] text-slate-400"></i>
                            <span>{{ $formattedDate }}</span>
                            <span class="text-slate-300">•</span>
                            <i class="far fa-clock text-[10px] text-slate-400"></i>
                            <span class="font-mono">{{ $jamClean }} WIB</span>
                        </div>
                    </div>

                    <!-- 2. Identitas & Kategori Tamu -->
                    <div>
                        <div class="font-bold text-slate-800 text-base leading-snug">{{ $visitor->nama_lengkap }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $visitor->alamat }}</div>

                        <a href="https://wa.me/{{ $hpClean }}" 
                           target="_blank" 
                           title="Klik untuk obrolan WhatsApp langsung"
                           class="inline-flex items-center gap-1.5 mt-1 text-xs font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                            <i class="fab fa-whatsapp text-sm text-emerald-500"></i>
                            <span class="font-mono hover:underline">{{ $visitor->no_hp }}</span>
                        </a>

                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            <span class="bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-blue-200/60">
                                {{ $visitor->asal_instansi ?? '-' }} ({{ $jmlOrang }} Orang)
                            </span>
                            <span class="bg-slate-100 text-slate-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-slate-200/60">
                                {{ $visitor->divisi_tujuan }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full border {{ $visitor->temu_janji == 'Ada Janji' ? 'bg-indigo-50 text-indigo-700 border-indigo-200/60' : 'bg-slate-50 text-slate-600 border-slate-200/60' }}">
                                <i class="fas {{ $visitor->temu_janji == 'Ada Janji' ? 'fa-calendar-check' : 'fa-calendar-minus' }} text-[10px]"></i>
                                {{ $visitor->temu_janji }}
                            </span>
                        </div>
                    </div>

                    <!-- 3. Kotak Keperluan / Agenda -->
                    <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100 my-3">
                        <p class="text-xs text-slate-600 line-clamp-2">
                            {{ $visitor->keperluan }}
                        </p>
                        <button type="button" 
                                onclick="openDetailModal('{{ addslashes($visitor->nama_lengkap) }}', '{{ addslashes($visitor->alamat) }}', '{{ $visitor->no_hp }}', '{{ addslashes($visitor->email ?? '') }}', '{{ addslashes($visitor->divisi_tujuan) }}', '{{ addslashes($visitor->temu_janji) }}', '{{ $formattedDate }}', '{{ $jamClean }}', '{{ addslashes($visitor->keperluan) }}', '{{ $visitor->status }}', '{{ addslashes($visitor->asal_instansi ?? '') }}', '{{ $jmlOrang }}')"
                                class="w-full text-xs font-medium text-blue-600 hover:text-blue-800 flex items-center justify-end gap-1 mt-1">
                            <span>Rincian Lengkap</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </button>
                    </div>

                    <!-- 4. Tombol Aksi Modern (Action Row) -->
                    <div class="pt-2 border-t border-slate-100">
                        @if($visitor->status == 'Diproses')
                            <div class="flex flex-col gap-1.5 w-full">
                                <button type="button" 
                                        onclick="openConfirmModal('{{ route('admin.visitors.approve', $visitor->id) }}', 'Diterima', '{{ addslashes($visitor->nama_lengkap) }}', '{{ addslashes($visitor->divisi_tujuan) }}', '{{ addslashes($visitor->keperluan) }}', '{{ $visitor->no_hp }}', '{{ $formattedDate }}', '{{ $jamClean }}', '{{ addslashes($visitor->asal_instansi ?? '-') }}', '{{ $jmlOrang }}')" 
                                        class="w-full rounded-xl py-2 px-3 text-xs font-semibold shadow-sm transition active:scale-95 bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center gap-1.5">
                                    <i class="fas fa-user-check text-[10px]"></i> Terima
                                </button>
                                <button type="button" 
                                        onclick="openCancelModal('{{ route('admin.visitors.cancel', $visitor->id) }}', '{{ addslashes($visitor->nama_lengkap) }}', '{{ addslashes($visitor->divisi_tujuan) }}', '{{ addslashes($visitor->keperluan) }}', '{{ $visitor->no_hp }}', '{{ $formattedDate }}', '{{ $jamClean }}', '{{ addslashes($visitor->asal_instansi ?? '-') }}', '{{ $jmlOrang }}')" 
                                        class="w-full rounded-xl py-2 px-3 text-xs font-semibold shadow-sm transition active:scale-95 bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200/80 flex items-center justify-center gap-1.5">
                                    <i class="fas fa-times text-[10px]"></i> Batal
                                </button>
                            </div>
                        @elseif($visitor->status == 'Diterima')
                            <div class="flex flex-col gap-1.5 w-full">
                                <button type="button" 
                                        onclick="openCompleteModal('{{ route('admin.visitors.complete', $visitor->id) }}', '{{ addslashes($visitor->nama_lengkap) }}', '{{ addslashes($visitor->divisi_tujuan) }}', '{{ addslashes($visitor->keperluan) }}', '{{ $visitor->no_hp }}', '{{ $formattedDate }}', '{{ $jamClean }}', '{{ addslashes($visitor->asal_instansi ?? '-') }}', '{{ $jmlOrang }}')" 
                                        class="w-full rounded-xl py-2 px-3 text-xs font-semibold shadow-sm transition active:scale-95 bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center gap-1.5">
                                    <i class="fas fa-check text-[10px]"></i> Selesaikan Kunjungan
                                </button>
                                <button type="button" 
                                        onclick="openCancelModal('{{ route('admin.visitors.cancel', $visitor->id) }}', '{{ addslashes($visitor->nama_lengkap) }}', '{{ addslashes($visitor->divisi_tujuan) }}', '{{ addslashes($visitor->keperluan) }}', '{{ $visitor->no_hp }}', '{{ $formattedDate }}', '{{ $jamClean }}', '{{ addslashes($visitor->asal_instansi ?? '-') }}', '{{ $jmlOrang }}')" 
                                        class="w-full rounded-xl py-2 px-3 text-xs font-semibold shadow-sm transition active:scale-95 bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200/80 flex items-center justify-center gap-1.5">
                                    <i class="fas fa-times text-[10px]"></i> Batal
                                </button>
                            </div>
                        @elseif($visitor->status == 'Selesai')
                            <div class="w-full py-1.5 px-3 rounded-xl bg-emerald-50/70 border border-emerald-200/70 text-emerald-700 text-xs font-semibold flex items-center justify-center gap-1.5">
                                <i class="fas fa-check-circle text-[10px]"></i> Kunjungan Selesai
                            </div>
                        @else
                            <div class="w-full py-1.5 px-3 rounded-xl bg-rose-50/70 border border-rose-200/70 text-rose-700 text-xs font-semibold flex items-center justify-center gap-1.5" title="Alasan: {{ $visitor->alasan_batal ?? 'Tidak tercatat' }}">
                                <i class="fas fa-times-circle text-[10px]"></i> Kunjungan Dibatalkan
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-10 text-center text-slate-400 bg-white rounded-xl border border-slate-200/80">
                    <i class="fas fa-inbox text-3xl mb-2 block"></i>
                    <p class="text-xs font-bold uppercase tracking-wider">Belum ada data kunjungan tamu</p>
                </div>
            @endforelse
        </div>

        <!-- DESKTOP TABLE VIEW (hidden md:block) -->
        <div class="hidden md:block w-full overflow-hidden">
            <table class="w-full table-auto text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100/90 border-b-2 border-slate-200 text-[10px] uppercase font-black text-slate-700 tracking-wider">
                        <th class="py-3 px-3 text-center w-10 border-r border-slate-200/80">No.</th>
                        <th class="py-3 px-3 border-r border-slate-200/80">Jadwal &amp; Waktu</th>
                        <th class="py-3 px-3 border-r border-slate-200/80">Identitas &amp; Kontak</th>
                        <th class="py-3 px-3 border-r border-slate-200/80">Instansi &amp; Pengunjung</th>
                        <th class="py-3 px-3 border-r border-slate-200/80">Tujuan &amp; Janji</th>
                        <th class="py-3 px-3 border-r border-slate-200/80">Keperluan</th>
                        <th class="py-3 px-3 text-center border-r border-slate-200/80">Status</th>
                        <th class="py-3 px-3 text-center">Aksi Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs text-slate-700 font-medium">
                    @forelse($visitors as $index => $visitor)
                        @php
                            $tglCarbon = \Carbon\Carbon::parse($visitor->tanggal_kunjungan);
                            $formattedDate = $tglCarbon->locale('id')->isoFormat('D MMM YYYY');
                            $jamClean = \Carbon\Carbon::parse($visitor->jam_kunjungan)->format('H:i');
                            
                            $isAccepted = ($visitor->status == 'Diterima');
                            $canComplete = $isAccepted;

                            $hariIni = \Carbon\Carbon::today();
                            $isToday = $tglCarbon->isToday();
                            $isTomorrow = $tglCarbon->isTomorrow();
                            $isFuture = $tglCarbon->isAfter($hariIni);
                            $isPast = $tglCarbon->isBefore($hariIni);
                            
                            $selisihHariLalu = (int) $tglCarbon->diffInDays($hariIni, false);
                            $nomorUrut = ($visitors->currentPage() - 1) * $visitors->perPage() + $index + 1;
                            $jmlOrang = (int) ($visitor->jumlah_pengunjung ?? 1);
                        @endphp
                        <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-slate-50/70' }} hover:bg-blue-50/40 transition-colors border-b border-slate-200/70">
                            
                            <td class="py-3 px-3 text-center font-mono font-bold text-slate-600 bg-slate-100/50 border-r border-slate-200/80">
                                {{ $nomorUrut }}
                            </td>

                            <td class="py-3 px-3 whitespace-nowrap border-r border-slate-100">
                                <div class="mb-0.5">
                                    @if($isToday)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200 animate-pulse">
                                            <i class="fas fa-exclamation-circle text-[7px]"></i> HARI INI
                                        </span>
                                    @elseif($isTomorrow)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[8px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">
                                            <i class="fas fa-clock text-[7px]"></i> BESOK
                                        </span>
                                    @elseif($isFuture)
                                        @php $selisihMendatang = (int) $hariIni->diffInDays($tglCarbon, false); @endphp
                                        @if($selisihMendatang <= 7)
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[8px] font-extrabold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                <i class="fas fa-calendar-alt text-[7px]"></i> {{ $selisihMendatang }} Hari Lagi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[8px] font-extrabold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                                                <i class="fas fa-calendar-week text-[7px]"></i> {{ ceil($selisihMendatang / 7) }} Mgg Lagi
                                            </span>
                                        @endif
                                    @else
                                        @if($selisihHariLalu == 1)
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[8px] font-extrabold uppercase tracking-wider bg-slate-200 text-slate-700 border border-slate-300">
                                                <i class="fas fa-history text-[7px]"></i> 1 Hari Lalu
                                            </span>
                                        @elseif($selisihHariLalu > 1 && $selisihHariLalu <= 7)
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[8px] font-extrabold uppercase tracking-wider bg-slate-200 text-slate-700 border border-slate-300">
                                                <i class="fas fa-history text-[7px]"></i> {{ $selisihHariLalu }} Hari Lalu
                                            </span>
                                        @else
                                            @php $mingguLalu = ceil($selisihHariLalu / 7); @endphp
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[8px] font-extrabold uppercase tracking-wider bg-slate-200 text-slate-700 border border-slate-300">
                                                <i class="fas fa-history text-[7px]"></i> {{ $mingguLalu }} Mgg Lalu
                                            </span>
                                        @endif
                                    @endif
                                </div>

                                <div class="font-bold text-slate-900 text-[11px] leading-tight">{{ $formattedDate }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">
                                    <i class="far fa-clock text-[8px] mr-0.5"></i> {{ $jamClean }} WIB
                                </div>
                            </td>

                            <td class="py-3 px-3 max-w-[180px] border-r border-slate-100">
                                <div class="font-extrabold text-slate-900 text-xs truncate" title="{{ $visitor->nama_lengkap }}">{{ $visitor->nama_lengkap }}</div>
                                <div class="text-[11px] text-slate-500 mb-1 truncate" title="{{ $visitor->alamat }}">{{ $visitor->alamat }}</div>
                                
                                @php
                                    $hpClean = preg_replace('/[^0-9]/', '', $visitor->no_hp);
                                    if (str_starts_with($hpClean, '0')) {
                                        $hpClean = '62' . substr($hpClean, 1);
                                    }
                                @endphp

                                <a href="https://wa.me/{{ $hpClean }}" 
                                   target="_blank" 
                                   title="Klik untuk obrolan WhatsApp langsung"
                                   class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-[10px] font-bold transition-all shadow-2xs">
                                    <i class="fab fa-whatsapp text-emerald-600 text-[10px]"></i>
                                    <span class="font-mono text-[10px] truncate">{{ $visitor->no_hp }}</span>
                                </a>
                            </td>

                            <td class="py-3 px-3 whitespace-nowrap border-r border-slate-100">
                                @php
                                    $kategoriBadge = match ($visitor->asal_instansi) {
                                        'Instansi Pemerintah' => 'bg-blue-50 text-blue-700 border-blue-200/80',
                                        'Swasta'              => 'bg-amber-50 text-amber-700 border-amber-200/80',
                                        default               => 'bg-slate-100 text-slate-700 border-slate-200/80',
                                    };
                                @endphp
                                <div class="flex flex-col gap-1.5 items-start">
                                    <span class="px-2 py-0.5 rounded-md border text-xs font-semibold {{ $kategoriBadge }}">
                                        {{ $visitor->asal_instansi ?? '-' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-medium border border-slate-200/60">
                                        <i class="fas fa-users text-[10px] text-slate-500"></i>
                                        <span>{{ $jmlOrang }} Orang</span>
                                    </span>
                                </div>
                            </td>

                            <td class="py-3 px-3 border-r border-slate-100">
                                <div class="flex flex-col gap-1.5 items-start">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-800 font-bold text-xs border border-slate-200/80 leading-snug">
                                        {{ $visitor->divisi_tujuan }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-md {{ $visitor->temu_janji == 'Ada Janji' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/80' : 'bg-slate-100 text-slate-600 border border-slate-200/60' }}">
                                        <i class="fas {{ $visitor->temu_janji == 'Ada Janji' ? 'fa-calendar-check' : 'fa-calendar-minus' }} text-[9px]"></i>
                                        {{ $visitor->temu_janji }}
                                    </span>
                                </div>
                            </td>

                            <td class="py-3 px-3 max-w-[220px] border-r border-slate-100">
                                <p class="text-xs text-slate-600 line-clamp-2 italic mb-1.5" title="{{ $visitor->keperluan }}">
                                    {{ $visitor->keperluan }}
                                </p>
                                <button type="button" 
                                        onclick="openDetailModal('{{ addslashes($visitor->nama_lengkap) }}', '{{ addslashes($visitor->alamat) }}', '{{ $visitor->no_hp }}', '{{ addslashes($visitor->email ?? '') }}', '{{ addslashes($visitor->divisi_tujuan) }}', '{{ addslashes($visitor->temu_janji) }}', '{{ $formattedDate }}', '{{ $jamClean }}', '{{ addslashes($visitor->keperluan) }}', '{{ $visitor->status }}', '{{ addslashes($visitor->asal_instansi ?? '') }}', '{{ $jmlOrang }}')"
                                        class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                    <i class="fas fa-eye text-[10px]"></i>
                                    <span>Rincian Lengkap</span>
                                </button>
                            </td>

                            <td class="py-3 px-3 text-center whitespace-nowrap border-r border-slate-100">
                                @if($visitor->status == 'Selesai')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1">
                                        <i class="fas fa-check-circle text-[9px]"></i> Selesai
                                    </span>
                                @elseif($visitor->status == 'Diterima')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200 inline-flex items-center gap-1">
                                        <i class="fas fa-user-check text-[9px]"></i> Diterima
                                    </span>
                                @elseif($visitor->status == 'Dibatalkan')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200 inline-flex items-center gap-1" title="Alasan: {{ $visitor->alasan_batal ?? 'Tidak tercatat' }}">
                                        <i class="fas fa-times-circle text-[9px]"></i> Batal
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200 inline-flex items-center gap-1">
                                        <i class="fas fa-spinner text-[9px]"></i> Diproses
                                    </span>
                                @endif
                            </td>

                            <td class="py-3 px-3 text-center whitespace-nowrap">
                                <div class="flex flex-col gap-1.5 w-full">
                                    @if($visitor->status == 'Diproses')
                                        <button type="button" 
                                                onclick="openConfirmModal('{{ route('admin.visitors.approve', $visitor->id) }}', 'Diterima', '{{ addslashes($visitor->nama_lengkap) }}', '{{ addslashes($visitor->divisi_tujuan) }}', '{{ addslashes($visitor->keperluan) }}', '{{ $visitor->no_hp }}', '{{ $formattedDate }}', '{{ $jamClean }}', '{{ addslashes($visitor->asal_instansi ?? '-') }}', '{{ $jmlOrang }}')" 
                                                title="Terima Kunjungan" 
                                                class="w-full px-2.5 py-1 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white shadow-2xs rounded-lg text-[11px] font-semibold transition-all inline-flex items-center justify-center gap-1">
                                            <i class="fas fa-user-check text-[9px]"></i> Terima
                                        </button>
                                        <button type="button" 
                                                onclick="openCancelModal('{{ route('admin.visitors.cancel', $visitor->id) }}', '{{ addslashes($visitor->nama_lengkap) }}', '{{ addslashes($visitor->divisi_tujuan) }}', '{{ addslashes($visitor->keperluan) }}', '{{ $visitor->no_hp }}', '{{ $formattedDate }}', '{{ $jamClean }}', '{{ addslashes($visitor->asal_instansi ?? '-') }}', '{{ $jmlOrang }}')" 
                                                title="Batalkan Kunjungan" 
                                                class="w-full px-2.5 py-1 bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200/80 active:scale-95 rounded-lg text-[11px] font-semibold transition-all inline-flex items-center justify-center gap-1">
                                            <i class="fas fa-times text-[9px]"></i> Batal
                                        </button>
                                    @elseif($visitor->status == 'Diterima')
                                        <button type="button" 
                                                onclick="openCompleteModal('{{ route('admin.visitors.complete', $visitor->id) }}', '{{ addslashes($visitor->nama_lengkap) }}', '{{ addslashes($visitor->divisi_tujuan) }}', '{{ addslashes($visitor->keperluan) }}', '{{ $visitor->no_hp }}', '{{ $formattedDate }}', '{{ $jamClean }}', '{{ addslashes($visitor->asal_instansi ?? '-') }}', '{{ $jmlOrang }}')" 
                                                title="Tandai Kunjungan Selesai" 
                                                class="w-full px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white shadow-2xs rounded-lg text-[11px] font-semibold transition-all inline-flex items-center justify-center gap-1">
                                            <i class="fas fa-check text-[9px]"></i> Selesaikan Kunjungan
                                        </button>
                                        <button type="button" 
                                                onclick="openCancelModal('{{ route('admin.visitors.cancel', $visitor->id) }}', '{{ addslashes($visitor->nama_lengkap) }}', '{{ addslashes($visitor->divisi_tujuan) }}', '{{ addslashes($visitor->keperluan) }}', '{{ $visitor->no_hp }}', '{{ $formattedDate }}', '{{ $jamClean }}', '{{ addslashes($visitor->asal_instansi ?? '-') }}', '{{ $jmlOrang }}')" 
                                                title="Batalkan Kunjungan" 
                                                class="w-full px-2.5 py-1 bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200/80 active:scale-95 rounded-lg text-[11px] font-semibold transition-all inline-flex items-center justify-center gap-1">
                                            <i class="fas fa-times text-[9px]"></i> Batal
                                        </button>
                                    @elseif($visitor->status == 'Selesai')
                                        <span class="w-full px-2 py-1 rounded-lg bg-slate-100 text-slate-500 border border-slate-200/80 text-[10px] font-semibold inline-flex items-center justify-center gap-1">
                                            <i class="fas fa-check-double text-[9px] text-emerald-600"></i> Tuntas
                                        </span>
                                    @else
                                        <span class="w-full px-2 py-1 rounded-lg bg-slate-100 text-slate-500 border border-slate-200/80 text-[10px] font-semibold inline-flex items-center justify-center gap-1" title="Alasan: {{ $visitor->alasan_batal ?? 'Tidak tercatat' }}">
                                            <i class="fas fa-ban text-[9px] text-rose-500"></i> Dibatalkan
                                        </span>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-slate-400">
                                <i class="fas fa-inbox text-3xl mb-2 block"></i>
                                <p class="text-xs font-bold uppercase tracking-wider">Belum Ada Data Kunjungan Tamu</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($visitors->hasPages())
            <div class="p-3.5 border-t border-slate-100">
                {{ $visitors->withQueryString()->links() }}
            </div>
        @endif

    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL GLOBAL & NOTIFIKASI SYSTEM -->
<!-- ========================================================================= -->

<!-- 1. NOTIFIKASI SUKSES -->
@if (session('success'))
    @php
        $successMsg = session('success');
        $isAccept = str_contains(strtolower($successMsg), 'diterima');
        $isComplete = str_contains(strtolower($successMsg), 'selesai');
        $isCancel = str_contains(strtolower($successMsg), 'dibatalkan') || str_contains(strtolower($successMsg), 'batal');
        
        $themeColor = $isAccept ? 'blue' : ($isComplete ? 'emerald' : ($isCancel ? 'rose' : 'emerald'));
        $iconClass = $isAccept ? 'fa-user-check' : ($isComplete ? 'fa-check-circle' : ($isCancel ? 'fa-ban' : 'fa-check'));
        $actionTitle = $isAccept ? 'Kunjungan Diterima!' : ($isComplete ? 'Kunjungan Selesai!' : ($isCancel ? 'Kunjungan Dibatalkan' : 'Status Diperbarui'));
    @endphp

<div id="successPopupModal" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 9999999;" class="flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-[2.5rem] max-w-md w-[92%] sm:w-full p-7 sm:p-8 shadow-2xl border border-{{ $themeColor }}-100 relative transform scale-100 animate-fade-in-up duration-200 text-center">
        
        <button type="button" onclick="closeSuccessModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 font-bold w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
            &times;
        </button>

        <div class="relative w-16 h-16 mx-auto mb-4">
            <div class="absolute inset-0 rounded-2xl bg-{{ $themeColor }}-500/20 animate-ping"></div>
            <div class="relative w-full h-full rounded-2xl bg-gradient-to-br from-{{ $themeColor }}-600 to-{{ $themeColor }}-700 text-white flex items-center justify-center shadow-lg shadow-{{ $themeColor }}-500/30">
                <i class="fas {{ $iconClass }} text-2xl"></i>
            </div>
        </div>

        <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-{{ $themeColor }}-50 border border-{{ $themeColor }}-200 text-{{ $themeColor }}-800 text-[11px] font-extrabold uppercase tracking-wider mb-2.5">
            <i class="fas fa-circle-check text-{{ $themeColor }}-600 text-xs"></i>
            <span>Notifikasi Sistem</span>
        </div>
        
        <h3 class="text-xl font-black text-slate-900 tracking-tight mb-3">{{ $actionTitle }}</h3>
        
        <div class="bg-{{ $themeColor }}-50/60 border border-{{ $themeColor }}-200/70 rounded-2xl p-4 mb-6 text-left shadow-inner">
            <p class="text-xs sm:text-sm font-semibold text-slate-700 text-center leading-relaxed">
                {{ $successMsg }}
            </p>
        </div>

        <button type="button" onclick="closeSuccessModal()" class="w-full py-3.5 px-5 rounded-2xl bg-{{ $themeColor }}-600 hover:bg-{{ $themeColor }}-700 active:scale-[0.98] text-white font-extrabold text-xs sm:text-sm shadow-xl shadow-{{ $themeColor }}-600/25 transition-all flex items-center justify-center gap-2">
            <i class="fas fa-check text-white/90 text-sm"></i>
            <span>Saya Mengerti, Tutup</span>
        </button>

    </div>
</div>
@endif

<!-- 2. MODAL WARNING VALIDASI SOP -->
<div id="sopWarningModal" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 9999999;" class="{{ session('error') ? 'flex' : 'hidden' }} items-center justify-center p-4 bg-slate-950/60 backdrop-blur-[2px] transition-all duration-300">
    <div class="bg-white rounded-[2.5rem] max-w-md w-[92%] sm:w-full p-6 sm:p-8 shadow-2xl border border-rose-100 relative transform scale-100 animate-fade-in-up duration-200 text-center">
        
        <button type="button" onclick="closeSopModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 font-bold w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
            &times;
        </button>

        <div class="relative w-16 h-16 mx-auto mb-4">
            <div class="absolute inset-0 rounded-2xl bg-rose-500/20 animate-pulse"></div>
            <div class="relative w-full h-full rounded-2xl bg-gradient-to-br from-rose-600 to-red-700 text-white flex items-center justify-center shadow-lg shadow-rose-500/30">
                <i class="fas fa-shield-cat text-2xl"></i>
            </div>
        </div>

        <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-rose-50 border border-rose-200/80 text-rose-800 text-[11px] font-extrabold uppercase tracking-wider mb-2.5">
            <i class="fas fa-triangle-exclamation text-rose-600 text-xs"></i>
            <span>Prosedur SOP Layanan Tamu</span>
        </div>
        
        <h3 id="sopTitleText" class="text-xl font-black text-slate-900 tracking-tight mb-4">Aksi Belum Dapat Diproses</h3>
        
        <div class="bg-rose-50/60 border border-rose-200/70 rounded-2xl p-4 mb-6 text-left space-y-3 shadow-inner">
            <div class="flex items-start gap-2.5 text-xs sm:text-sm font-semibold text-slate-800 leading-relaxed">
                <i class="fas fa-circle-exclamation text-rose-600 text-sm mt-0.5 shrink-0"></i>
                <p id="sopMessageText" class="text-slate-700">
                    {{ session('error', 'Silakan selesaikan alur verifikasi dengan menekan tombol [Terima] terlebih dahulu.') }}
                </p>
            </div>
        </div>

        <button type="button" onclick="closeSopModal()" class="w-full py-3.5 px-5 rounded-2xl bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-extrabold text-xs sm:text-sm shadow-xl shadow-rose-600/25 transition-all flex items-center justify-center gap-2">
            <i class="fas fa-arrow-left text-white/90 text-sm"></i>
            <span>Saya Mengerti, Kembali ke Panel</span>
        </button>

    </div>
</div>

<!-- 3. MODAL KONFIRMASI TERIMA KUNJUNGAN -->
<div id="confirmAcceptModal" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 9999999;" class="hidden flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-[1px]">
    <div class="bg-white rounded-[2.2rem] max-w-md w-[92%] sm:w-full p-6 sm:p-7 shadow-2xl border border-slate-100 relative animate-fade-in-up duration-200">
        
        <button type="button" onclick="closeConfirmModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 font-bold w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
            &times;
        </button>

        <div class="flex items-center gap-3.5 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold shrink-0 border border-blue-100">
                <i class="fas fa-user-check text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-slate-900 leading-snug">Konfirmasi Penerimaan</h3>
                <p class="text-xs text-slate-500">Kunjungan Tamu LPP RRI Bukittinggi</p>
            </div>
        </div>

        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 mb-4 space-y-2.5 text-xs">
            <div class="flex justify-between items-center pb-2 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Nama Tamu:</span>
                <span id="acceptGuestName" class="font-extrabold text-slate-900"></span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Divisi Tujuan:</span>
                <span id="acceptDivisiName" class="font-bold text-blue-700"></span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Asal Instansi:</span>
                <span id="acceptAsalInstansi" class="font-bold text-slate-800"></span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Jumlah Pengunjung:</span>
                <span id="acceptJumlahPengunjung" class="font-bold text-slate-800"></span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Jadwal Kunjungan:</span>
                <span id="acceptJadwal" class="font-bold text-slate-800 font-mono"></span>
            </div>
            <div>
                <span class="text-slate-500 font-medium block mb-1">Keperluan / Agenda:</span>
                <p id="acceptKeperluan" class="font-semibold text-slate-800 italic bg-white p-2.5 rounded-xl border border-slate-200/60 line-clamp-3"></p>
            </div>
        </div>

        <form id="acceptForm" method="POST" class="space-y-4">
            @csrf

            <div class="flex flex-col sm:flex-row items-center gap-2.5 pt-1">
                <a id="acceptWaBtn" href="#" target="_blank" class="w-full sm:w-auto px-4 py-3 rounded-2xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fab fa-whatsapp text-emerald-600 text-base"></i>
                    <span>Kirim WA Konfirmasi</span>
                </a>

                <button type="submit" class="w-full flex-1 py-3 px-4 rounded-2xl bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-extrabold text-xs shadow-lg shadow-blue-600/25 transition-all flex items-center justify-center gap-1.5">
                    <i class="fas fa-user-check"></i>
                    <span>Ya, Terima Kunjungan</span>
                </button>
            </div>
        </form>

    </div>
</div>

<!-- 4. MODAL KONFIRMASI SELESAI KUNJUNGAN -->
<div id="confirmCompleteModal" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 9999999;" class="hidden flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-[1px]">
    <div class="bg-white rounded-[2.2rem] max-w-md w-[92%] sm:w-full p-6 sm:p-7 shadow-2xl border border-slate-100 relative animate-fade-in-up duration-200">
        
        <button type="button" onclick="closeCompleteModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 font-bold w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
            &times;
        </button>

        <div class="flex items-center gap-3.5 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shrink-0 border border-emerald-100">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-slate-900 leading-snug">Konfirmasi Penyelesaian</h3>
                <p class="text-xs text-slate-500">Tandai kunjungan tuntas & kirim survei ulasan</p>
            </div>
        </div>

        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3.5 mb-4 space-y-2 text-xs">
            <div class="flex justify-between items-center pb-1.5 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Nama Tamu:</span>
                <span id="completeGuestName" class="font-extrabold text-slate-900"></span>
            </div>
            <div class="flex justify-between items-center pb-1.5 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Divisi Tujuan:</span>
                <span id="completeDivisiName" class="font-bold text-slate-700"></span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Asal Instansi:</span>
                <span id="completeAsalInstansi" class="font-bold text-slate-800"></span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Jumlah Pengunjung:</span>
                <span id="completeJumlahPengunjung" class="font-bold text-slate-800"></span>
            </div>
            <div class="flex justify-between items-center pb-1.5 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Jadwal Kunjungan:</span>
                <span id="completeJadwal" class="font-bold text-slate-800 font-mono"></span>
            </div>
            <div>
                <span class="text-slate-500 font-medium block mb-0.5">Keperluan / Agenda:</span>
                <p id="completeKeperluan" class="font-semibold text-slate-700 italic line-clamp-2"></p>
            </div>
        </div>

        <form id="completeForm" method="POST" class="space-y-4">
            @csrf

            <div class="flex flex-col sm:flex-row items-center gap-2.5 pt-1">
                <a id="completeWaBtn" href="#" target="_blank" class="w-full sm:w-auto px-4 py-3 rounded-2xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fab fa-whatsapp text-emerald-600 text-base"></i>
                    <span>Kirim WA Ulasan</span>
                </a>

                <button type="submit" class="w-full flex-1 py-3 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-extrabold text-xs shadow-lg shadow-emerald-600/25 transition-all flex items-center justify-center gap-1.5">
                    <i class="fas fa-check-circle"></i>
                    <span>Ya, Tandai Selesai</span>
                </button>
            </div>
        </form>

    </div>
</div>

<!-- 5. MODAL KONFIRMASI PEMBATALAN KUNJUNGAN -->
<div id="confirmCancelModal" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 9999999;" class="hidden flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-[1px]">
    <div class="bg-white rounded-[2.2rem] max-w-md w-[92%] sm:w-full p-6 sm:p-7 shadow-2xl border border-slate-100 relative animate-fade-in-up duration-200">
        
        <button type="button" onclick="closeCancelModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 font-bold w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
            &times;
        </button>

        <div class="flex items-center gap-3.5 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold shrink-0 border border-rose-100">
                <i class="fas fa-times-circle text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-slate-900 leading-snug">Konfirmasi Pembatalan</h3>
                <p class="text-xs text-slate-500">Batalkan agenda kunjungan tamu</p>
            </div>
        </div>

        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3.5 mb-4 space-y-2 text-xs">
            <div class="flex justify-between items-center pb-1.5 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Nama Tamu:</span>
                <span id="cancelGuestName" class="font-extrabold text-slate-900"></span>
            </div>
            <div class="flex justify-between items-center pb-1.5 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Divisi Tujuan:</span>
                <span id="cancelDivisiName" class="font-bold text-slate-700"></span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Asal Instansi:</span>
                <span id="cancelAsalInstansi" class="font-bold text-slate-800"></span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Jumlah Pengunjung:</span>
                <span id="cancelJumlahPengunjung" class="font-bold text-slate-800"></span>
            </div>
            <div class="flex justify-between items-center pb-1.5 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Jadwal Kunjungan:</span>
                <span id="cancelJadwal" class="font-bold text-slate-800 font-mono"></span>
            </div>
            <div>
                <span class="text-slate-500 font-medium block mb-0.5">Keperluan / Agenda:</span>
                <p id="cancelKeperluan" class="font-semibold text-slate-700 italic line-clamp-2"></p>
            </div>
        </div>

        <form id="cancelForm" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">ALASAN PEMBATALAN (OPSIONAL)</label>
                <textarea name="alasan_batal" id="alasanBatalInput" oninput="updateCancelWaLink()" rows="2" placeholder="Catatan alasan pembatalan..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-600/30 transition-all"></textarea>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-2.5 pt-1">
                <a id="cancelWaBtn" href="#" target="_blank" class="w-full sm:w-auto px-4 py-3 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fab fa-whatsapp text-rose-600 text-base"></i>
                    <span>Kirim WA Batal</span>
                </a>

                <button type="submit" class="w-full flex-1 py-3 px-4 rounded-2xl bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-extrabold text-xs shadow-lg shadow-rose-600/25 transition-all flex items-center justify-center gap-1.5">
                    <i class="fas fa-times-circle"></i>
                    <span>Ya, Batalkan Kunjungan</span>
                </button>
            </div>
        </form>

    </div>
</div>

<!-- 6. MODAL DETAIL LENGKAP KUNJUNGAN (DIOPTIMALKAN AGAR TIDAK TERPOTONG & TANPA LINK ATAS) -->
<div id="visitorDetailModal" class="hidden fixed inset-0 z-[999999] flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-[2rem] max-w-md w-full p-5 sm:p-6 shadow-2xl relative animate-fade-in-up max-h-[88vh] overflow-y-auto">
        
        <button type="button" onclick="closeDetailModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 font-bold w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
            &times;
        </button>

        <div class="flex items-center gap-2.5 mb-3.5 pb-2.5 border-b border-slate-100">
            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <i class="fas fa-id-card text-base"></i>
            </div>
            <div>
                <h3 class="text-sm sm:text-base font-black text-slate-900 leading-tight">Detail Lengkap Kunjungan</h3>
                <p class="text-[11px] text-slate-400 font-medium">Informasi Rincian Agenda Tamu</p>
            </div>
        </div>

        <div class="space-y-2.5 text-xs">
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                <span class="text-slate-400 font-bold text-[9px] uppercase block">Nama Lengkap</span>
                <span id="detNama" class="font-black text-slate-900 text-xs sm:text-sm"></span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                <div>
                    <span class="text-slate-400 font-bold text-[9px] uppercase block mb-0.5">Kontak WhatsApp</span>
                    <span id="detHp" class="font-bold text-slate-700 font-mono text-xs"></span>
                </div>
                <div>
                    <span class="text-slate-400 font-bold text-[9px] uppercase block mb-0.5">Alamat Email</span>
                    <span id="detEmail" class="font-semibold text-slate-700 font-mono text-xs"></span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2.5 bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                <div>
                    <span class="text-slate-400 font-bold text-[9px] uppercase block">Kategori Asal Instansi</span>
                    <span id="detAsalInstansi" class="font-bold text-slate-800 text-xs"></span>
                </div>
                <div>
                    <span class="text-slate-400 font-bold text-[9px] uppercase block">Jumlah Pengunjung</span>
                    <span class="font-black text-slate-800 text-xs"><i class="fas fa-users text-[10px] text-slate-400 mr-1"></i><span id="detJumlahPengunjung"></span> Orang</span>
                </div>
            </div>

            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                <span class="text-slate-400 font-bold text-[9px] uppercase block">Alamat / Nama Instansi</span>
                <span id="detAlamat" class="font-semibold text-slate-800 text-xs"></span>
            </div>

            <div class="grid grid-cols-2 gap-2.5 bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                <div>
                    <span class="text-slate-400 font-bold text-[9px] uppercase block">Divisi Tujuan</span>
                    <span id="detDivisi" class="font-bold text-blue-700 text-xs"></span>
                </div>
                <div>
                    <span class="text-slate-400 font-bold text-[9px] uppercase block">Status Janji Temu</span>
                    <span id="detJanji" class="font-bold text-indigo-600 text-xs"></span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2.5 bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                <div>
                    <span class="text-slate-400 font-bold text-[9px] uppercase block">Jadwal Tanggal</span>
                    <span id="detTanggal" class="font-bold text-slate-800 text-xs"></span>
                </div>
                <div>
                    <span class="text-slate-400 font-bold text-[9px] uppercase block">Waktu Kunjungan</span>
                    <span id="detJam" class="font-bold text-slate-800 font-mono text-xs"></span>
                </div>
            </div>

            <div class="bg-blue-50/50 p-3 rounded-xl border border-blue-100">
                <span class="text-blue-600 font-extrabold text-[9px] uppercase block mb-0.5">Keperluan / Agenda Kunjungan:</span>
                <p id="detKeperluan" class="font-medium text-slate-800 italic leading-relaxed text-xs break-words break-all"></p>
            </div>
        </div>

        <!-- TOMBOL AKSI DIRECT & TUTUP DI MODAL DASHBOARD -->
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-2">
            <!-- Tombol Direct WhatsApp Tamu -->
            <a id="modalDashboardBtnWa" href="#" target="_blank" class="hidden flex-1 py-2.5 px-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-extrabold text-xs tracking-wide transition-all shadow-md shadow-emerald-600/20 items-center justify-center gap-1.5">
                <i class="fab fa-whatsapp text-sm"></i>
                <span>Hubungi WA</span>
            </a>

            <!-- Tombol Direct Email Tamu -->
            <a id="modalDashboardBtnEmail" href="#" target="_blank" class="hidden flex-1 py-2.5 px-2 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-extrabold text-xs tracking-wide transition-all shadow-md shadow-blue-600/20 items-center justify-center gap-1.5">
                <i class="far fa-envelope text-sm"></i>
                <span>Kirim Email</span>
            </a>

            <!-- Tombol Tutup Detail -->
            <button type="button" onclick="closeDetailModal()" class="flex-1 py-2.5 px-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs tracking-wide transition-all shadow-md">
                Tutup Detail
            </button>
        </div>

    </div>
</div>

<!-- 7. MODAL CETAK / EKSPOR LAPORAN KUNJUNGAN -->
<div id="exportModal" class="hidden fixed inset-0 z-[999999] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-[2.5rem] max-w-lg w-full p-6 sm:p-8 shadow-2xl relative animate-fade-in-up max-h-[90vh] overflow-y-auto">
        
        <button type="button" onclick="closeExportModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 font-bold w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
            &times;
        </button>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <i class="fas fa-file-export text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900">Cetak & Ekspor Laporan</h3>
                <p class="text-xs text-slate-500">Pilih format dan filter periode laporan kunjungan</p>
            </div>
        </div>

        <form action="{{ route('admin.export.pdf') }}" method="GET" id="exportForm" target="_blank" class="space-y-4">
            
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1.5">Format Berkas Laporan</label>
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

            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1.5">Divisi Tujuan</label>
                <select name="divisi" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition-all">
                    <option value="">Semua Divisi (Keseluruhan)</option>
                    <option value="Kepala Stasiun (Kepsta)">Kepala Stasiun (Kepsta)</option>
                    <option value="Tata Usaha (TU)">Tata Usaha (TU)</option>
                    <option value="Siaran">Siaran</option>
                    <option value="Pemberitaan">Pemberitaan</option>
                    <option value="Teknologi dan Media Baru (TMB)">Teknologi dan Media Baru (TMB)</option>
                    <option value="Layanan Pengembangan Usaha (LPU)">Layanan Pengembangan Usaha (LPU)</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1.5">Asal Instansi</label>
                <select name="asal_instansi" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition-all">
                    <option value="">Semua Asal Instansi</option>
                    <option value="Instansi Pemerintah">Pemerintah</option>
                    <option value="Swasta">Swasta</option>
                    <option value="Masyarakat Umum">Umum</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1.5">Per Tanggal</label>
                    <input type="date" name="tanggal" id="exportTanggal" onchange="resetBulanTahun()" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1.5">Per Bulan</label>
                    <select name="bulan" id="exportBulan" onchange="resetTanggalSpesifik()" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition-all">
                        <option value="">Pilih Bulan</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ sprintf('%02d', $m) }}">{{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1.5">Per Tahun</label>
                    <input type="number" name="tahun" id="exportTahun" onchange="resetTanggalSpesifik()" min="2000" max="{{ date('Y') }}" placeholder="Cth: {{ date('Y') }}" value=""
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition-all">
                </div>
            </div>

            <div class="pt-2 flex items-center gap-3">
                <button type="button" onclick="closeExportModal()" class="w-1/3 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold text-xs transition-all">
                    Batal
                </button>
                <button type="button" onclick="submitExportForm(event)" class="w-2/3 py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 active:scale-95 text-white font-extrabold text-xs tracking-wide shadow-lg shadow-blue-600/25 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-cloud-download-alt text-sm"></i>
                    <span>Unduh Berkas Laporan</span>
                </button>
            </div>

        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let currentCancelData = { guestName: '', divisiName: '', keperluan: '', phone: '', tanggal: '', jam: '' };

    function updateRealtimeClock() {
        const headerClockEl = document.getElementById('header-realtime-clock');
        if (!headerClockEl) return;

        const now = new Date();
        const opsiTanggal = { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' };
        const tanggalFormatted = now.toLocaleDateString('id-ID', opsiTanggal);
        const jam = String(now.getHours()).padStart(2, '0');
        const menit = String(now.getMinutes()).padStart(2, '0');

        headerClockEl.textContent = `${tanggalFormatted} • ${jam}:${menit} WIB`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateRealtimeClock();
        setInterval(updateRealtimeClock, 1000);
    });

    function smoothRefreshDashboard(url) {
        window.location.href = url || window.location.href;
    }

    function closeSuccessModal() {
        const modal = document.getElementById('successPopupModal');
        if (modal) modal.style.display = 'none';
    }

    function closeSopModal() {
        const modal = document.getElementById('sopWarningModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function openSopModalClient(titleText, customMessage) {
        const modal = document.getElementById('sopWarningModal');
        const titleEl = document.getElementById('sopTitleText');
        const textMsg = document.getElementById('sopMessageText');
        
        if (titleEl && titleText) titleEl.innerText = titleText;
        if (textMsg && customMessage) textMsg.innerText = customMessage;
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function openDetailModal(nama, alamat, hp, email, divisi, janji, tanggal, jam, keperluan, status, asalInstansi, jumlahPengunjung) {
        document.getElementById('detNama').innerText = nama;
        document.getElementById('detAlamat').innerText = alamat;
        document.getElementById('detAsalInstansi').innerText = asalInstansi && asalInstansi.trim() !== '' ? asalInstansi : '-';
        document.getElementById('detJumlahPengunjung').innerText = jumlahPengunjung && jumlahPengunjung.toString().trim() !== '' ? jumlahPengunjung : '1';
        
        // Menampilkan teks nomor HP dan Email biasa tanpa link di atas
        document.getElementById('detHp').innerText = hp && hp.trim() !== '' ? hp : '-';
        document.getElementById('detEmail').innerText = (email && email.trim() !== '' && email !== 'null' && email !== '-') ? email : 'Tidak menyertakan email';

        document.getElementById('detDivisi').innerText = divisi;
        document.getElementById('detJanji').innerText = janji;
        document.getElementById('detTanggal').innerText = tanggal;
        document.getElementById('detJam').innerText = jam + ' WIB';
        document.getElementById('detKeperluan').innerText = keperluan;

        let cleanPhone = hp ? hp.replace(/[^0-9]/g, '') : '';
        if (cleanPhone.startsWith('0')) cleanPhone = '62' + cleanPhone.substring(1);

        // Pengaturan Tombol Direct WhatsApp di Bawah Modal
        const btnWa = document.getElementById('modalDashboardBtnWa');
        if (hp && hp !== '-' && hp.trim() !== '') {
            const textWa = encodeURIComponent(`Halo Bpk/Ibu ${nama}, terkait rencana kunjungan Anda ke LPP RRI Bukittinggi pada tanggal ${tanggal}...`);
            btnWa.href = `https://wa.me/${cleanPhone}?text=${textWa}`;
            btnWa.classList.remove('hidden');
            btnWa.classList.add('flex');
        } else {
            btnWa.classList.add('hidden');
            btnWa.classList.remove('flex');
        }

        // Pengaturan Tombol Direct Email di Bawah Modal
        const btnEmail = document.getElementById('modalDashboardBtnEmail');
        if (email && email !== '-' && email.trim() !== '' && email !== 'null') {
            const subject = encodeURIComponent(`Konfirmasi Jadwal Kunjungan - LPP RRI Bukittinggi`);
            btnEmail.href = `mailto:${email}?subject=${subject}`;
            btnEmail.classList.remove('hidden');
            btnEmail.classList.add('flex');
        } else {
            btnEmail.classList.add('hidden');
            btnEmail.classList.remove('flex');
        }

        const modal = document.getElementById('visitorDetailModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeDetailModal() {
        const modal = document.getElementById('visitorDetailModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function openConfirmModal(actionUrl, status, guestName, divisiName, keperluan, rawPhone, tanggal, jam, asalInstansi, jumlahPengunjung) {
        const form = document.getElementById('acceptForm');
        form.action = actionUrl;
        
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();

        document.getElementById('acceptGuestName').innerText = guestName;
        document.getElementById('acceptDivisiName').innerText = divisiName;
        document.getElementById('acceptAsalInstansi').innerText = asalInstansi || '-';
        document.getElementById('acceptJumlahPengunjung').innerText = (jumlahPengunjung || '1') + ' orang';
        document.getElementById('acceptJadwal').innerText = `${tanggal}, ${jam} WIB`;
        document.getElementById('acceptKeperluan').innerText = keperluan;

        let cleanPhone = rawPhone.replace(/[^0-9]/g, '');
        if (cleanPhone.startsWith('0')) cleanPhone = '62' + cleanPhone.substring(1);

        const message = encodeURIComponent(
`*LPP RRI BUKITTINGGI*
*PEMBERITAHUAN KONFIRMASI KUNJUNGAN TAMU*

Yth. Bapak/Ibu *${guestName}*,

Terima kasih telah melakukan pendaftaran melalui *Sistem Buku Tamu Digital LPP RRI Bukittinggi*.

Melalui pesan ini, kami mengonfirmasi bahwa pengajuan agenda kunjungan Anda telah *DITERIMA* dan dijadwalkan dengan rincian sebagai berikut:

[1] RINCIAN AGENDA KUNJUNGAN:
- *Nama Tamu:* ${guestName}
- *Divisi Tujuan:* ${divisiName}
- *Asal Instansi:* ${asalInstansi}
- *Jumlah Pengunjung:* ${jumlahPengunjung} orang
- *Tanggal Kunjungan:* ${tanggal}
- *Waktu:* ${jam} WIB
- *Keperluan / Agenda:* ${keperluan}
- *Status Kunjungan:* Diterima / Terjadwal

[2] PETUNJUK & SOP KUNJUNGAN:
1. Harap hadir tepat waktu 10 menit sebelum jadwal yang telah ditentukan.
2. Saat tiba di kantor LPP RRI Bukittinggi, silakan melapor ke Petugas Layanan / Pos Keamanan.
3. Apabila terdapat perubahan jadwal atau kendala mendadak, mohon konfirmasi kembali melalui balasan pesan ini.

Terima kasih atas perhatian dan kerja sama Anda. Kami siap menyambut kedatangan Anda.

Salam hangat,
*Tim Pengelola Layanan Tamu*
*LPP RRI Bukittinggi*
_Sekali di Udara, Tetap di Udara_`
        );
        
        document.getElementById('acceptWaBtn').href = `https://wa.me/${cleanPhone}?text=${message}`;
        document.getElementById('confirmAcceptModal').classList.remove('hidden');
    }

    function closeConfirmModal() {
        document.getElementById('confirmAcceptModal').classList.add('hidden');
    }

    function openCompleteModal(actionUrl, guestName, divisiName, keperluan, rawPhone, tanggal, jam, asalInstansi, jumlahPengunjung) {
        const form = document.getElementById('completeForm');
        form.action = actionUrl;
        
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();

        document.getElementById('completeGuestName').innerText = guestName;
        document.getElementById('completeDivisiName').innerText = divisiName;
        document.getElementById('completeAsalInstansi').innerText = asalInstansi || '-';
        document.getElementById('completeJumlahPengunjung').innerText = (jumlahPengunjung || '1') + ' orang';
        document.getElementById('completeJadwal').innerText = `${tanggal}, ${jam} WIB`;
        document.getElementById('completeKeperluan').innerText = keperluan;

        let cleanPhone = rawPhone ? rawPhone.replace(/[^0-9]/g, '') : '';
        if (cleanPhone.startsWith('0')) {
            cleanPhone = '62' + cleanPhone.substring(1);
        }

        const surveyLink = "{{ route('home') }}?sumber=whatsapp#penilaian-publik";

        const message = encodeURIComponent(
`*LPP RRI BUKITTINGGI*
*UCAPAN TERIMA KASIH & EVALUASI LAYANAN*

Yth. Bapak/Ibu *${guestName}*,

Terima kasih telah berkunjung ke LPP RRI Bukittinggi pada ${tanggal} (${jam} WIB) untuk keperluan: _${keperluan}_ (${divisiName}).

Agenda kunjungan Anda telah selesai dilaksanakan. Mohon kesediaan Anda memberikan penilaian layanan melalui tautan berikut:

${surveyLink}

Atas waktu dan kerja sama Anda, kami ucapkan terima kasih.

Salam hangat,
*Tim Layanan Tamu LPP RRI Bukittinggi*
_Sekali di Udara, Tetap di Udara_`
        );

        const waBtn = document.getElementById('completeWaBtn');
        if (waBtn) {
            waBtn.href = `https://wa.me/${cleanPhone}?text=${message}`;
        }

        const modal = document.getElementById('confirmCompleteModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeCompleteModal() {
        const modal = document.getElementById('confirmCompleteModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function openCancelModal(actionUrl, guestName, divisiName, keperluan, rawPhone, tanggal, jam, asalInstansi, jumlahPengunjung) {
        const form = document.getElementById('cancelForm');
        form.action = actionUrl;
        
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();

        document.getElementById('cancelGuestName').innerText = guestName;
        document.getElementById('cancelDivisiName').innerText = divisiName;
        document.getElementById('cancelAsalInstansi').innerText = asalInstansi || '-';
        document.getElementById('cancelJumlahPengunjung').innerText = (jumlahPengunjung || '1') + ' orang';
        document.getElementById('cancelJadwal').innerText = `${tanggal}, ${jam} WIB`;
        document.getElementById('cancelKeperluan').innerText = keperluan;
        document.getElementById('alasanBatalInput').value = '';

        let cleanPhone = rawPhone.replace(/[^0-9]/g, '');
        if (cleanPhone.startsWith('0')) cleanPhone = '62' + cleanPhone.substring(1);

        currentCancelData = { 
            guestName: guestName, 
            divisiName: divisiName, 
            keperluan: keperluan, 
            phone: cleanPhone,
            tanggal: tanggal,
            jam: jam,
            asalInstansi: asalInstansi,
            jumlahPengunjung: jumlahPengunjung
        };

        updateCancelWaLink();
        document.getElementById('confirmCancelModal').classList.remove('hidden');
    }

    function updateCancelWaLink() {
        const alasan = document.getElementById('alasanBatalInput').value.trim();
        const alasanFormatted = alasan ? `_${alasan}_` : '_Tidak ada catatan spesifik_';

        const message = encodeURIComponent(
`*LPP RRI BUKITTINGGI*
*PEMBERITAHUAN PEMBATALAN AGENDA KUNJUNGAN*

Yth. Bapak/Ibu *${currentCancelData.guestName}*,

Terima kasih telah melakukan pendaftaran melalui *Sistem Buku Tamu Digital LPP RRI Bukittinggi*.

Mohon maaf, kami menginformasikan bahwa pengajuan agenda kunjungan Anda untuk jadwal: *${currentCancelData.tanggal} (${currentCancelData.jam} WIB)* untuk sementara waktu *DIBATALKAN* / *BELUM DAPAT DIPROSES*.

[1] RINCIAN KUNJUNGAN:
- *Nama Tamu:* ${currentCancelData.guestName}
- *Divisi Tujuan:* ${currentCancelData.divisiName}
- *Asal Instansi:* ${currentCancelData.asalInstansi}
- *Jumlah Pengunjung:* ${currentCancelData.jumlahPengunjung} orang
- *Jadwal:* ${currentCancelData.tanggal} (${currentCancelData.jam} WIB)
- *Catatan / Alasan Pembatalan:* ${alasanFormatted}
- *Status:* Dibatalkan

[2] INFORMASI LANJUTAN:
Apabila Anda membutuhkan penjadwalan ulang (*reschedule*), silakan hubungi unit layanan kami.

Atas perhatian Bapak/Ibu, kami ucapkan terima kasih.

Salam hangat,
*Tim Pengelola Layanan Tamu*
*LPP RRI Bukittinggi*
_Sekali di Udara, Tetap di Udara_`
        );
        
        document.getElementById('cancelWaBtn').href = `https://wa.me/${currentCancelData.phone}?text=${message}`;
    }

    function closeCancelModal() {
        document.getElementById('confirmCancelModal').classList.add('hidden');
    }

    function openExportModal() {
        const modal = document.getElementById('exportModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeExportModal() {
        const modal = document.getElementById('exportModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function resetBulanTahun() {
        const tgl = document.getElementById('exportTanggal');
        if (tgl && tgl.value) {
            document.getElementById('exportBulan').value = '';
            document.getElementById('exportTahun').value = '';
        }
    }

    function resetTanggalSpesifik() {
        const bln = document.getElementById('exportBulan');
        const thn = document.getElementById('exportTahun');
        if ((bln && bln.value) || (thn && thn.value)) {
            document.getElementById('exportTanggal').value = '';
        }
    }

    function submitExportForm(event) {
        if (event) event.preventDefault();
        
        const form = document.getElementById('exportForm');
        const formData = new FormData(form);
        
        const formatFileRadio = document.querySelector('input[name="format_file"]:checked');
        const formatFile = formatFileRadio ? formatFileRadio.value : 'pdf';
        
        const targetActionUrl = (formatFile === 'excel') 
            ? "{{ route('admin.export.excel') }}" 
            : "{{ route('admin.export.pdf') }}";

        const cleanParams = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            if (key !== 'format_file' && value !== null && value.toString().trim() !== '') {
                cleanParams.append(key, value);
            }
        }

        const fullUrl = targetActionUrl + (cleanParams.toString() ? '?' + cleanParams.toString() : '');

        const btnSubmit = event.target.closest('button') || document.querySelector('#exportForm button[type="button"]:last-child');
        const oldBtnText = btnSubmit ? btnSubmit.innerHTML : '';
        if (btnSubmit) {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Memeriksa Data...</span>';
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
                throw new Error('Data tidak ditemukan');
            }
            closeExportModal();
            window.open(fullUrl, '_blank');
        })
        .catch(error => {
            closeExportModal();

            Swal.fire({
                html: `
                    <div class="flex flex-col items-center text-center p-2">
                        <div class="w-20 h-20 rounded-3xl bg-amber-50 border border-amber-200/60 text-amber-500 flex items-center justify-center mb-5 shadow-xl shadow-amber-500/10 transition-transform hover:scale-105 duration-300">
                            <i class="fas fa-folder-open text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight mb-2">
                            Data Kunjungan Tidak Diselesaikan / Ditemukan
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed max-w-xs mb-2">
                            Maaf, belum ada catatan agenda kunjungan tamu untuk periode atau filter divisi yang Anda pilih.
                        </p>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: '<span class="flex items-center justify-center gap-2"><i class="fas fa-check-circle text-xs"></i> Saya Mengerti</span>',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-[2.5rem] shadow-2xl border border-slate-100/80 p-6 sm:p-8 bg-white/95 backdrop-blur-md max-w-md w-full',
                    confirmButton: 'w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-extrabold text-xs tracking-wide shadow-lg shadow-blue-600/30 active:scale-[0.98] transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-blue-600/20'
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