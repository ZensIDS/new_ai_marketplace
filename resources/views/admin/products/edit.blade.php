@extends('layouts.admin')
@section('title', 'Edit Produk')

@section('content')
<div class="card card-stat p-4" style="max-width:700px">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" rows="3" class="form-control">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" class="form-control" required min="0">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control" required min="0">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Gambar Produk</label>
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" width="70" class="d-block mb-2 rounded">
            @endif
            <input type="file" name="image" class="form-control">
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ $product->is_active ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Tampilkan di frontend (aktif)</label>
        </div>
        <button class="btn text-white" style="background:#FF5722">Update</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-light">Batal</a>
    </form>
</div>
@endsection
