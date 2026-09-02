@extends('layouts.admin')
@section('title', 'User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold mb-0">Daftar User</h6>
    <a href="{{ route('admin.users.create') }}" class="btn btn-sm text-white" style="background:#FF5722">
        <i class="bi bi-plus-lg"></i> Tambah User
    </a>
</div>

<div class="btn-group mb-3">
    <a href="{{ route('admin.users.index') }}" class="btn btn-sm {{ !request('role') ? 'btn-dark' : 'btn-outline-dark' }}">Semua</a>
    <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="btn btn-sm {{ request('role') == 'admin' ? 'btn-dark' : 'btn-outline-dark' }}">Admin</a>
    <a href="{{ route('admin.users.index', ['role' => 'customer']) }}" class="btn btn-sm {{ request('role') == 'customer' ? 'btn-dark' : 'btn-outline-dark' }}">Customer</a>
</div>

<div class="card card-stat p-3">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Nama</th><th>Email</th><th>No. HP</th><th>Role</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse($users as $u)
                <tr>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->phone ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $u->role == 'admin' ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary' }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada user</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
</div>
@endsection
