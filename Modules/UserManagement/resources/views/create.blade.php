@extends('layouts.app')

@section('title', 'Tambah Pengguna | Ruang Daftar')

@section('content')
    <section class="page-heading">
        <div><p class="eyebrow">MODUL ADMIN / USER MANAGEMENT</p><h1>Tambah pengguna</h1></div>
        <a class="text-action" href="{{ route('usermanagement.index') }}">Kembali ke daftar</a>
    </section>
    @include('usermanagement::form', ['user' => $user, 'action' => route('usermanagement.store'), 'method' => 'POST'])
@endsection