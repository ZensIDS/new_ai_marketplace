@extends('layouts.admin')
@section('title', 'Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold mb-0">Daftar Kategori</h6>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-sm text-white" style="background:#C9A227;color:#0B0B0C">
        <i class="bi bi-plus-lg"></i> Tambah Kategori
    </a>
</div>

<div class="card card-stat p-3">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Gambar</th><th>Nama</th><th>Jumlah Produk</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse($categories as $cat)
                <tr>
                    <td><img src="{{ $cat->image ? asset('storage/'.$cat->image) : 'https://placehold.co/50x50' }}" class="rounded" width="45" height="45" style="object-fit:cover"></td>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->products_count }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada kategori</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $categories->links() }}
</div>
@endsection
