@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-700 mb-4">
                <i class="fas fa-envelope-open-text text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Reset Password</h2>
            <p class="text-sm text-gray-600 mt-2">Masukkan email terdaftar untuk menerima link reset password.</p>
        </div>

        @if (session('status'))
            <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-md text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-md text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500" placeholder="admin@rri.co.id">
                </div>
            </div>

            <button type="submit" class="w-full flex justify-center items-center bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-300">
                <i class="fas fa-paper-plane mr-2"></i> Kirim Link Reset
            </button>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-blue-700 hover:underline">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
                </a>
            </div>
        </form>

    </div>
</div>
@endsection