@extends('layouts.app')
@section('title', 'Daftar - MarketKu')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
        <h1 class="text-2xl font-bold text-center mb-1">Buat Akun Baru</h1>
        <p class="text-gray-400 text-sm text-center mb-6">Daftar gratis dan mulai belanja sekarang</p>

        @if($errors->any())
            <div class="bg-red-50 text-red-600 text-sm px-4 py-3 rounded-lg mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm font-medium">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="text-sm font-medium">No. WhatsApp</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="628xxxxxxxxxx"
                       class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="text-sm font-medium">Password</label>
                <input type="password" name="password" required
                       class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="text-sm font-medium">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 rounded-xl transition">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center text-sm text-gray-400 mt-6">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Masuk</a>
        </p>
    </div>
</div>
@endsection
