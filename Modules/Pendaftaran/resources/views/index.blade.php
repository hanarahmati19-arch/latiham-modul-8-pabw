@extends('layouts.app')

@section('title', 'Pendaftaran | Ruang Daftar')

@section('content')
    <section class="page-heading">
        <div>
            <p class="eyebrow">MODUL PENDAFTARAN / {{ $isAdmin ? 'ADMIN' : 'PENGGUNA' }}</p>
            <h1>{{ $isAdmin ? 'Semua pendaftaran' : 'Pendaftaran saya' }}</h1>
            <p class="muted">{{ $isAdmin ? 'Tinjau dan perbarui status pengajuan.' : 'Buat pengajuan dan pantau statusnya.' }}</p>
        </div>
        <span class="role-tag">{{ $registrations->total() }} DATA</span>
    </section>

    <section class="registration-layout">
        <form class="form-panel registration-form" method="POST" action="{{ route('pendaftaran.store') }}"
              data-registration-form data-base-url="{{ route('pendaftaran.index') }}" data-is-admin="{{ $isAdmin ? 'true' : 'false' }}">
            @csrf
            <div class="form-heading">
                <p class="eyebrow">FORMULIR</p>
                <h2 data-form-heading>Buat pendaftaran</h2>
            </div>
            <label class="field">
                <span>Nama peserta</span>
                <input name="name" type="text" maxlength="255" required>
            </label>
            <label class="field">
                <span>Email</span>
                <input name="email" type="email" maxlength="255" required>
            </label>
            <label class="field">
                <span>Nomor telepon</span>
                <input name="phone" type="tel" maxlength="40">
            </label>
            <label class="field">
                <span>Program</span>
                <input name="program" type="text" maxlength="160" placeholder="Nama program yang dipilih" required>
            </label>
            <label class="field">
                <span>Alamat</span>
                <textarea name="address" rows="3" maxlength="2000"></textarea>
            </label>
            @if ($isAdmin)
                <label class="field">
                    <span>Status</span>
                    <select name="status">
                        <option value="pending">Menunggu</option>
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </label>
            @endif
            <p class="ajax-feedback" data-form-feedback role="status" aria-live="polite"></p>
            <div class="form-actions">
                <button class="button button-primary" type="submit" data-submit-label>Simpan pendaftaran</button>
                <button class="text-action is-hidden" type="button" data-cancel-edit>Batal ubah</button>
            </div>
        </form>

        <section class="data-panel registration-list" aria-label="Daftar pendaftaran">
            <div class="list-heading">
                <div><p class="eyebrow">DATA TERBARU</p><h2>Daftar pengajuan</h2></div>
                <span class="muted">{{ $registrations->count() }} ditampilkan</span>
            </div>
            <div class="table-scroll">
                <table class="data-table registration-table">
                    <thead>
                        <tr>
                            @if ($isAdmin)<th>Pemohon</th>@endif
                            <th>Peserta</th><th>Email</th><th>Program</th><th>Status</th><th class="table-actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody data-registration-rows>
                        @forelse ($registrations as $registration)
                            <tr data-registration-row
                                data-registration-id="{{ $registration->id }}"
                                data-name="{{ $registration->name }}"
                                data-email="{{ $registration->email }}"
                                data-phone="{{ $registration->phone }}"
                                data-address="{{ $registration->address }}"
                                data-program="{{ $registration->program }}"
                                data-status="{{ $registration->status }}"
                                data-user-name="{{ $registration->user->name }}">
                                @if ($isAdmin)<td data-label="Pemohon">{{ $registration->user->name }}</td>@endif
                                <td data-label="Peserta">{{ $registration->name }}</td>
                                <td data-label="Email">{{ $registration->email }}</td>
                                <td data-label="Program">{{ $registration->program }}</td>
                                <td data-label="Status"><span class="status-pill status-{{ $registration->status }}">{{ ucfirst($registration->status) }}</span></td>
                                <td class="table-actions" data-label="Aksi">
                                    <button class="text-action" type="button" data-edit-registration>Ubah</button>
                                    <button class="text-action text-danger" type="button" data-delete-registration>Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr data-empty-row><td class="empty-state" colspan="{{ $isAdmin ? 6 : 5 }}">Belum ada pendaftaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">{{ $registrations->links() }}</div>
        </section>
    </section>
@endsection
