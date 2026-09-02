@extends('layouts.admin')
@section('title', 'Edit Produk')

@section('content')
<div class="card card-stat p-4" style="max-width:750px">
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
                <label class="form-label">Harga Dasar (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" class="form-control" required min="0">
                <small class="text-muted">Dipakai jika produk tidak punya varian.</small>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Stok Dasar</label>
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
        <div class="form-check mb-4">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ $product->is_active ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Tampilkan di frontend (aktif)</label>
        </div>

        <!-- VARIAN PRODUK -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label mb-0 fw-bold">Varian Produk (opsional)</label>
                <button type="button" class="btn btn-sm btn-outline-dark" onclick="addVariantRow()">
                    <i class="bi bi-plus-lg"></i> Tambah Varian
                </button>
            </div>
            <small class="text-muted d-block mb-2">Menyimpan ulang seluruh varian setiap kali disimpan.</small>
            <div id="variant-rows">
                @foreach($product->variants as $variant)
                    <div class="row g-2 align-items-center mb-2 variant-row">
                        <div class="col-md-5">
                            <input type="text" name="variants[{{ $loop->index }}][name]" value="{{ $variant->name }}" class="form-control form-control-sm" placeholder="Nama varian">
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="variants[{{ $loop->index }}][price]" value="{{ $variant->price }}" class="form-control form-control-sm" placeholder="Harga" min="0">
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="variants[{{ $loop->index }}][stock]" value="{{ $variant->stock }}" class="form-control form-control-sm" placeholder="Stok" min="0">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.variant-row').remove()"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button class="btn text-white" style="background:#C9A227;color:#0B0B0C">Update</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-light">Batal</a>
    </form>
</div>

<template id="variant-row-template">
    <div class="row g-2 align-items-center mb-2 variant-row">
        <div class="col-md-5">
            <input type="text" name="variants[__i__][name]" class="form-control form-control-sm" placeholder="Nama varian (mis. 1 Bulan)">
        </div>
        <div class="col-md-3">
            <input type="number" name="variants[__i__][price]" class="form-control form-control-sm" placeholder="Harga" min="0">
        </div>
        <div class="col-md-3">
            <input type="number" name="variants[__i__][stock]" class="form-control form-control-sm" placeholder="Stok" min="0">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.variant-row').remove()"><i class="bi bi-trash"></i></button>
        </div>
    </div>
</template>

@push('scripts')
<script>
    let variantIndex = {{ $product->variants->count() }};
    function addVariantRow() {
        const tpl = document.getElementById('variant-row-template').innerHTML.replaceAll('__i__', variantIndex);
        document.getElementById('variant-rows').insertAdjacentHTML('beforeend', tpl);
        variantIndex++;
    }
</script>
@endpush
@endsection
