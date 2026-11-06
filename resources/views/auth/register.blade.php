@extends('layouts.app')

@section('title', 'Daftar | Ruang Daftar')

@section('content')
    <section class="auth-layout">
        <div class="auth-intro">
            <p class="eyebrow">RUANG DAFTAR / AKUN</p>
            <h1>Buat akun,<br><em>mulai mendaftar.</em></h1>
            <p>Akun baru mendapatkan akses pengguna. Hak admin hanya diberikan melalui konfigurasi pengelola.</p>
        </div>
        <form class="form-panel" method="POST" action="{{ url('/register') }}">
            @csrf
            <h2>Daftar akun</h2>
            <label class="field">
                <span>Nama lengkap</span>
                <input type="text" name="name" value="{{ old('name') }}" autocomplete="name" required>
                @error('name')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label class="field">
                <span>Email</span>
                <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
                @error('email')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label class="field">
                <span>Kata sandi</span>
                <input type="password" name="password" autocomplete="new-password" required>
                @error('password')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label class="field">
                <span>Ulangi kata sandi</span>
                <input type="password" name="password_confirmation" autocomplete="new-password" required>
            </label>
            <button class="button button-primary button-wide" type="submit">Buat akun</button>
            <p class="form-footnote">Sudah memiliki akun? <a href="{{ route('login') }}">Masuk</a></p>
        </form>
    </section>
@endsection