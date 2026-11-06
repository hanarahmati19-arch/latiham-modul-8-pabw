@extends('layouts.app')

@section('title', 'Masuk | Ruang Daftar')

@section('content')
    <section class="auth-layout">
        <div class="auth-intro">
            <p class="eyebrow">RUANG DAFTAR / AKUN</p>
            <h1>Masuk untuk<br><em>melanjutkan.</em></h1>
            <p>Kelola pendaftaran dan pantau statusnya dari satu tempat.</p>
        </div>
        <form class="form-panel" method="POST" action="{{ url('/login') }}">
            @csrf
            <h2>Masuk</h2>
            <label class="field">
                <span>Email</span>
                <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                @error('email')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label class="field">
                <span>Kata sandi</span>
                <input type="password" name="password" autocomplete="current-password" required>
                @error('password')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label class="check-field"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
            <button class="button button-primary button-wide" type="submit">Masuk ke akun</button>
            <p class="form-footnote">Belum memiliki akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
        </form>
    </section>
@endsection