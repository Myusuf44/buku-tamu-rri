@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in-up">
    
    <!-- Header Halaman -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 border border-blue-200 text-blue-800 text-xs font-bold mb-2">
                <i class="fas fa-user-shield text-xs"></i>
                <span>Otoritas Sistem</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Akun Admin</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Sistem Buku Tamu Digital LPP RRI Bukittinggi</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 active:scale-95 text-white font-bold rounded-xl shadow-md transition-all text-xs sm:text-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi Sukses -->
    @if (session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3 text-xs sm:text-sm font-medium">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                    <i class="fas fa-check text-sm"></i>
                </div>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold text-lg px-2">&times;</button>
        </div>
    @endif

    <!-- Alert Notifikasi Error -->
    @if (session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3 text-xs sm:text-sm font-medium">
                <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                    <i class="fas fa-exclamation-triangle text-sm"></i>
                </div>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 font-bold text-lg px-2">&times;</button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Form Tambah Admin Baru -->
        <div class="bg-white p-6 sm:p-7 rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-200/80 h-fit">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold shadow-sm">
                    <i class="fas fa-user-plus text-base"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Tambah Admin Baru</h2>
                    <p class="text-xs text-slate-500">Daftarkan akun petugas pengelola baru</p>
                </div>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Contoh: Muhammad Yusuf, S.Kom" 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        NIP / NIK Admin <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                    </label>
                    <input type="text" name="nip" placeholder="Contoh: 19900101 202203 1 001 / NIK" 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Alamat Email Resmi</label>
                    <input type="email" name="email" required placeholder="petugas@rri.co.id" 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition">
                </div>

                <!-- Pilihan Peran / Role -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Peran / Hak Akses</label>
                    <select name="role" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition">
                        <option value="admin">Admin Biasa</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Password</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/30 transition">
                </div>

                <button type="submit" class="w-full mt-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-95 text-white font-bold py-3 px-4 rounded-xl shadow-md shadow-blue-600/20 transition-all flex items-center justify-center gap-2 text-xs sm:text-sm">
                    <i class="fas fa-save"></i>
                    <span>Simpan Account Admin</span>
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Admin -->
        <div class="lg:col-span-2 bg-white p-6 sm:p-7 rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-200/80">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold shadow-sm">
                    <i class="fas fa-users text-base"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Daftar Akun Admin Active</h2>
                    <p class="text-xs text-slate-500">Seluruh petugas yang berwenang mengakses panel kontrol</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] uppercase font-extrabold text-slate-600 tracking-wider">
                            <th class="py-3.5 px-4">Identitas Admin</th>
                            <th class="py-3.5 px-4">NIP / NIK</th>
                            <th class="py-3.5 px-4">Email</th>
                            <th class="py-3.5 px-4">Peran</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm text-slate-700 font-medium">
                        @foreach($admins as $admin)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $admin->name }}
                                @if(auth()->id() === $admin->id)
                                    <span class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-700 border border-blue-200">Saya</span>
                                @endif
                            </td>
                            
                            <td class="py-3.5 px-4 font-mono text-slate-600">
                                {{ $admin->nip ?? '-' }}
                            </td>

                            <td class="py-3.5 px-4 text-slate-600">
                                {{ $admin->email }}
                            </td>

                            <td class="py-3.5 px-4">
                                @if($admin->role === 'super_admin')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-purple-100 text-purple-700 border border-purple-200 inline-flex items-center gap-1">
                                        <i class="fas fa-shield-halved text-[9px]"></i> Super Admin
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200 inline-flex items-center gap-1">
                                        <i class="fas fa-user text-[9px]"></i> Admin Biasa
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Tombol Edit Profil / NIP / Role -->
                                    <button type="button" 
                                            onclick="openEditModal('{{ $admin->id }}', '{{ $admin->name }}', '{{ $admin->email }}', '{{ $admin->nip ?? '' }}', '{{ $admin->role }}')" 
                                            class="bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 px-2.5 py-1.5 rounded-lg text-xs font-bold inline-flex items-center gap-1 transition-all">
                                        <i class="fas fa-pen-to-square text-[11px]"></i> Edit
                                    </button>

                                    @if(auth()->id() !== $admin->id)
                                        <form action="{{ route('admin.users.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 px-2.5 py-1.5 rounded-lg text-xs font-bold inline-flex items-center gap-1 transition-all">
                                                <i class="fas fa-trash-alt text-[10px]"></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- MODAL POPUP EDIT ADMIN / ROLE -->
<div id="editModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100 relative animate-fade-in-up">
        
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold shadow-sm">
                    <i class="fas fa-user-pen text-base"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Edit Profile Admin</h3>
                    <p class="text-xs text-slate-500">Perbarui Nama, Email, NIP, atau Peran Admin</p>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-700 font-bold text-lg p-1">&times;</button>
        </div>

        <form id="editForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nama Lengkap</label>
                <input type="text" id="edit_name" name="name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-600/30">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">NIP / NIK Admin</label>
                <input type="text" id="edit_nip" name="nip" placeholder="Masukkan NIP atau NIK..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-600/30">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Alamat Email</label>
                <input type="email" id="edit_email" name="email" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-600/30">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Peran / Hak Akses</label>
                <select id="edit_role" name="role" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-600/30">
                    <option value="admin">Admin Biasa</option>
                    <option value="super_admin">Super Admin</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Password Baru <span class="text-slate-400 font-normal lowercase">(kosongkan jika tidak diubah)</span>
                </label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-600/30">
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs sm:text-sm hover:bg-slate-100 transition-all">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm shadow-md transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function openEditModal(id, name, email, nip, role) {
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_nip').value = nip;
        document.getElementById('edit_role').value = role;
        
        // Update Form Action URL
        document.getElementById('editForm').action = "/admin/users/" + id;
        
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection