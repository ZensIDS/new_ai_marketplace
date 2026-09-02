@extends('layouts.admin')
@section('title', 'Tambah Kategori')

@section('content')
<div class="card card-stat p-4" style="max-width:600px">
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Gambar (opsional)</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button class="btn text-white" style="background:#C9A227;color:#0B0B0C">Simpan</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-light">Batal</a>
    </form>
</div>
@endsection
