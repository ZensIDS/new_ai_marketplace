@extends('layouts.admin')
@section('title', 'Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold mb-0">Daftar Produk</h6>
    <a href="{{ route('admin.products.create') }}" class="btn btn-sm text-white" style="background:#C9A227;color:#0B0B0C">
        <i class="bi bi-plus-lg"></i> Tambah Produk
    </a>
</div>

<form class="mb-3" method="GET">
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" style="max-width:300px" placeholder="Cari produk...">
</form>

<div class="card card-stat p-3">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse($products as $p)
                <tr>
                    <td><img src="{{ $p->image ? asset('storage/'.$p->image) : 'https://placehold.co/50x50' }}" width="45" height="45" class="rounded" style="object-fit:cover"></td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->category->name ?? '-' }}</td>
                    <td>{{ $p->formatted_price }}</td>
                    <td>{{ $p->stock }}</td>
                    <td>
                        @if($p->is_active)
                            <span class="badge bg-success-subtle text-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada produk</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $products->links() }}
</div>
@endsection
