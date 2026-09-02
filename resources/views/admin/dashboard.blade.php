@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-stat p-3">
            <p class="text-muted small mb-1">Total Produk</p>
            <h3 class="fw-bold mb-0">{{ $totalProducts }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stat p-3">
            <p class="text-muted small mb-1">Total Kategori</p>
            <h3 class="fw-bold mb-0">{{ $totalCategories }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stat p-3">
            <p class="text-muted small mb-1">Customer</p>
            <h3 class="fw-bold mb-0">{{ $totalCustomers }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stat p-3">
            <p class="text-muted small mb-1">Admin</p>
            <h3 class="fw-bold mb-0">{{ $totalAdmins }}</h3>
        </div>
    </div>
</div>

<div class="card card-stat p-3">
    <h6 class="fw-bold mb-3">Produk Terbaru</h6>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th></tr></thead>
            <tbody>
            @forelse($latestProducts as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->category->name ?? '-' }}</td>
                    <td>{{ $p->formatted_price }}</td>
                    <td>{{ $p->stock }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada produk</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
