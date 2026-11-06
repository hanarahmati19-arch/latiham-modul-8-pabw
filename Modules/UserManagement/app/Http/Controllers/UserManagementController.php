<?php

namespace Modules\UserManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index(): View
    {
        return view('usermanagement::index', [
            'users' => User::query()->orderBy('name')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('usermanagement::create', ['user' => new User]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create($validated);

        return redirect()->route('usermanagement.index')->with('status', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $usermanagement): View
    {
        return view('usermanagement::edit', ['user' => $usermanagement]);
    }

    public function update(Request $request, User $usermanagement): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usermanagement)],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($request->user()->is($usermanagement) && $validated['role'] !== 'admin') {
            return back()->withErrors(['role' => 'Akun admin yang sedang digunakan tidak dapat menurunkan perannya.'])->withInput();
        }

        if (blank($validated['password'])) {
            unset($validated['password']);
        }

        $usermanagement->update($validated);

        return redirect()->route('usermanagement.index')->with('status', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $usermanagement): RedirectResponse
    {
        abort_if($request->user()->is($usermanagement), 403, 'Akun yang sedang digunakan tidak dapat dihapus.');

        $usermanagement->delete();

        return redirect()->route('usermanagement.index')->with('status', 'Pengguna berhasil dihapus.');
    }
}
