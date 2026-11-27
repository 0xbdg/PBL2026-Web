<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $users = User::query()
            ->when($search !== '', fn($query) => $query->where(
                fn($q) => $q->whereLike('name', "%{$search}%")
                    ->orWhereLike('email', "%{$search}%")
            ))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('akun.index', compact('users', 'search'));
    }

    public function create(): View
    {
        return view('akun.create', ['user' => new User]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = new User($data);
        $user->is_admin = $request->boolean('is_admin');
        $user->save();

        return redirect()->route('akun.index')->with('success', 'User berhasil ditambahkan.');
    }



    public function edit(User $user): View
    {
        return view('akun.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        $isAdmin = $request->boolean('is_admin');

        if ($request->user()->is($user) && ! $isAdmin) {
            return back()->withInput()->with('error', 'Anda tidak bisa mencabut hak admin sendiri.');
        }

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->fill($data);
        $user->is_admin = $isAdmin;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('akun.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        // sessions.user_id tidak punya foreign key, jadi dibersihkan manual
        DB::transaction(function () use ($user) {
            DB::table('sessions')->where('user_id', $user->id)->delete();
            $user->delete();
        });

        return redirect()->route('akun.index')->with('success', 'User berhasil dihapus.');
    }
}
