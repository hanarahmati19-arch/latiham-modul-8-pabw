<?php

namespace Modules\Pendaftaran\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Pendaftaran\Models\Registration;

class PendaftaranController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $registrations = $this->registrationsVisibleTo($request->user())
            ->with('user:id,name')
            ->latest()
            ->paginate(15);

        if ($request->expectsJson()) {
            return response()->json(['registrations' => $registrations->items()]);
        }

        return view('pendaftaran::index', [
            'registrations' => $registrations,
            'isAdmin' => $request->user()->isAdmin(),
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $registration = $request->user()->registrations()->create($validated)->load('user:id,name');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Pendaftaran berhasil ditambahkan.',
                'registration' => $registration,
            ], 201);
        }

        return redirect()->route('pendaftaran.index')->with('status', 'Pendaftaran berhasil ditambahkan.');
    }

    public function update(Request $request, int $registration): JsonResponse|RedirectResponse
    {
        $record = $this->findVisibleRegistration($request->user(), $registration);
        $validated = $request->validate($this->rules($request->user()->isAdmin()));
        $record->update($validated);
        $record->load('user:id,name');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Pendaftaran berhasil diperbarui.',
                'registration' => $record,
            ]);
        }

        return redirect()->route('pendaftaran.index')->with('status', 'Pendaftaran berhasil diperbarui.');
    }

    public function destroy(Request $request, int $registration): JsonResponse|RedirectResponse
    {
        $this->findVisibleRegistration($request->user(), $registration)->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Pendaftaran berhasil dihapus.']);
        }

        return redirect()->route('pendaftaran.index')->with('status', 'Pendaftaran berhasil dihapus.');
    }

    private function rules(bool $includeStatus = false): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:2000'],
            'program' => ['required', 'string', 'max:160'],
        ];

        if ($includeStatus) {
            $rules['status'] = ['required', Rule::in(['pending', 'approved', 'rejected'])];
        }

        return $rules;
    }

    private function registrationsVisibleTo(User $user): Builder
    {
        return Registration::query()->when(
            ! $user->isAdmin(),
            fn (Builder $query) => $query->whereBelongsTo($user),
        );
    }

    private function findVisibleRegistration(User $user, int $registrationId): Registration
    {
        return $this->registrationsVisibleTo($user)->findOrFail($registrationId);
    }
}
