@extends('layouts.admin')
@section('title', 'Tags & Kata Terkait')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h6 class="fw-bold mb-1">Tags & Kata Terkait</h6>
        <small class="text-muted">Kelola kata utama dan istilah yang dianggap masih relevan oleh mesin pencarian.</small>
    </div>
    <a href="{{ route('admin.tags.create') }}" class="btn btn-sm text-white" style="background:#C9A227;color:#0B0B0C">
        <i class="bi bi-plus-lg"></i> Tambah Tag
    </a>
</div>

<form class="mb-3" method="GET">
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" style="max-width:360px" placeholder="Cari tag atau kata terkait...">
</form>

<div class="card card-stat p-3">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Tag</th><th>Kata Terkait</th><th>Dipakai Produk</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse($tags as $tag)
                <tr>
                    <td><span class="badge text-bg-dark">#{{ $tag->name }}</span></td>
                    <td>{{ $tag->related_keywords ?: '-' }}</td>
                    <td>{{ $tag->products_count }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus tag ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada tag</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $tags->links() }}
</div>
@endsection
