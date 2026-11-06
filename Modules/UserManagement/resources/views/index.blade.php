@extends('layouts.app')

@section('title', 'Manajemen Pengguna | Ruang Daftar')

@section('content')
    <section class="page-heading">
        <div>
            <p class="eyebrow">MODUL ADMIN / USER MANAGEMENT</p>
            <h1>Manajemen pengguna</h1>
            <p class="muted">Atur akun dan hak akses sistem.</p>
        </div>
        <a class="button button-primary" href="{{ route('usermanagement.create') }}">Tambah pengguna</a>
    </section>

    <section class="data-panel">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr><th>Nama</th><th>Email</th><th>Peran</th><th class="table-actions">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td data-label="Nama">{{ $user->name }}</td>
                            <td data-label="Email">{{ $user->email }}</td>
                            <td data-label="Peran"><span class="role-tag">{{ strtoupper($user->role) }}</span></td>
                            <td class="table-actions" data-label="Aksi">
                                <a class="text-action" href="{{ route('usermanagement.edit', $user) }}">Ubah</a>
                                @unless (auth()->user()->is($user))
                                    <form method="POST" action="{{ route('usermanagement.destroy', $user) }}" onsubmit="return confirm('Hapus akun {{ $user->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-action text-danger" type="submit">Hapus</button>
                                    </form>
                                @endunless
                            </td>
                        </tr>
                    @empty
                        <tr><td class="empty-state" colspan="4">Belum ada pengguna.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $users->links() }}</div>
    </section>
@endsection
