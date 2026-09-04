@extends('layouts.admin')
@section('title', 'Edit Tag')

@section('content')
<div class="card card-stat p-4" style="max-width:750px">
    <form action="{{ route('admin.tags.update', $tag) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nama Tag</label>
            <input type="text" name="name" value="{{ old('name', $tag->name) }}" class="form-control" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Kata Terkait</label>
            <textarea name="related_keywords" rows="4" class="form-control">{{ old('related_keywords', $tag->related_keywords) }}</textarea>
            <small class="text-muted">Pisahkan dengan koma. Kata-kata ini akan membantu pencarian menemukan produk yang konsepnya berdekatan.</small>
        </div>
        <button class="btn text-white" style="background:#C9A227;color:#0B0B0C">Update</button>
        <a href="{{ route('admin.tags.index') }}" class="btn btn-light">Batal</a>
    </form>
</div>
@endsection
