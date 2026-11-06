<form class="form-panel form-panel-wide" method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif
    <label class="field">
        <span>Nama lengkap</span>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        @error('name')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label class="field">
        <span>Email</span>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        @error('email')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label class="field">
        <span>Peran</span>
        <select name="role" required>
            <option value="user" @selected(old('role', $user->role ?: 'user') === 'user')>Pengguna</option>
            <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
        </select>
        @error('role')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label class="field">
        <span>Kata sandi {{ $method === 'POST' ? '' : '(kosongkan jika tidak diubah)' }}</span>
        <input type="password" name="password" @required($method === 'POST') autocomplete="new-password">
        @error('password')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label class="field">
        <span>Ulangi kata sandi</span>
        <input type="password" name="password_confirmation" @required($method === 'POST') autocomplete="new-password">
    </label>
    <div class="form-actions">
        <button class="button button-primary" type="submit">{{ $method === 'POST' ? 'Simpan pengguna' : 'Simpan perubahan' }}</button>
        <a class="text-action" href="{{ route('usermanagement.index') }}">Batal</a>
    </div>
</form>