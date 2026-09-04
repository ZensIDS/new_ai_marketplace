@extends('layouts.admin')
@section('title', 'Tambah Tag')

@section('content')
<div class="card card-stat p-4" style="max-width:750px">
    <form action="{{ route('admin.tags.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama Tag</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Contoh: Music" required>
            <small class="text-muted">Gunakan satu konsep utama per tag.</small>
        </div>
        <div class="mb-4">
            <label class="form-label">Kata Terkait</label>
            <textarea name="related_keywords" rows="4" class="form-control" placeholder="audio, sound, song, melody, music generation">{{ old('related_keywords') }}</textarea>
            <small class="text-muted">Pisahkan dengan koma. Contoh tag <strong>Music</strong>: audio, sound, song, melody.</small>
        </div>
        <button class="btn text-white" style="background:#C9A227;color:#0B0B0C">Simpan</button>
        <a href="{{ route('admin.tags.index') }}" class="btn btn-light">Batal</a>
    </form>
</div>
@endsection
