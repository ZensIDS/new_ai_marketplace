@extends('layouts.app')
@section('title', 'Masuk - MarketKu')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
        <h1 class="text-2xl font-bold text-center mb-1">Selamat Datang</h1>
        <p class="text-gray-400 text-sm text-center mb-6">Masuk untuk mulai belanja di MarketKu</p>

        @if($errors->any())
            <div class="bg-red-50 text-red-600 text-sm px-4 py-3 rounded-lg mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="text-sm font-medium">Password</label>
                <input type="password" name="password" required
                       class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-500">
                <input type="checkbox" name="remember" class="accent-orange-500 rounded"> Ingat saya
            </label>
            <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 rounded-xl transition">
                Masuk
            </button>
        </form>

        <p class="text-center text-sm text-gray-400 mt-6">
            Belum punya akun? <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection
