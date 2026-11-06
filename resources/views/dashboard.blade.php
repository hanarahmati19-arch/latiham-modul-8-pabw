@extends('layouts.app')

@section('title', 'Beranda | Ruang Daftar')

@section('content')
    <section class="page-heading">
        <div>
            <p class="eyebrow">RUANG DAFTAR / BERANDA</p>
            <h1>Selamat datang, {{ auth()->user()->name }}.</h1>
            <p class="muted">Akses fitur sesuai peran akun Anda.</p>
        </div>
        <span class="role-tag">{{ auth()->user()->isAdmin() ? 'ADMIN' : 'PENGGUNA' }}</span>
    </section>
    <section class="workspace-links" aria-label="Modul aplikasi">
        <a class="workspace-link" href="{{ route('pendaftaran.index') }}">
            <span class="link-index">01</span>
            <span><strong>Pendaftaran</strong><small>Kelola pengajuan pendaftaran Anda</small></span>
            <span class="link-arrow" aria-hidden="true">↗</span>
        </a>
        @if (auth()->user()->isAdmin())
            <a class="workspace-link" href="{{ route('usermanagement.index') }}">
                <span class="link-index">02</span>
                <span><strong>Manajemen Pengguna</strong><small>Kelola akun dan peran pengguna</small></span>
                <span class="link-arrow" aria-hidden="true">↗</span>
            </a>
        @endif
    </section>
@endsection