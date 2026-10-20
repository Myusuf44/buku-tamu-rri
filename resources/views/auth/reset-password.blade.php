@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-700 mb-4">
                <i class="fas fa-key text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Buat Password Baru</h2>
            <p class="text-sm text-gray-600 mt-2">Silakan masukkan password baru Anda.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-md text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input id="email" name="email" type="email" value="{{ request()->email }}" required readonly class="pl-10 w-full bg-gray-100 border border-gray-300 rounded-lg px-4 py-2 text-gray-600 cursor-not-allowed">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input id="password" name="password" type="password" required class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Minimal 8 karakter">
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-check-circle text-gray-400"></i>
                    </div>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ulangi password baru">
                </div>
            </div>

            <button type="submit" class="w-full flex justify-center items-center bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-300">
                <i class="fas fa-save mr-2"></i> Simpan Password Baru
            </button>
        </form>

    </div>
</div>
@endsection