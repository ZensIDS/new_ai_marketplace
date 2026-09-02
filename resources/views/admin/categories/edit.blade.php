@extends('layouts.admin')
@section('title', 'Edit Kategori')

@section('content')
<div class="card card-stat p-4" style="max-width:600px">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Gambar</label>
            @if($category->image)
                <img src="{{ asset('storage/'.$category->image) }}" width="60" class="d-block mb-2 rounded">
            @endif
            <input type="file" name="image" class="form-control">
        </div>
        <button class="btn text-white" style="background:#C9A227;color:#0B0B0C">Update</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-light">Batal</a>
    </form>
</div>
@endsection
