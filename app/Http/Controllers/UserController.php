<?php

namespace App\Http\Controllers;

use App\DataTables\AuthenticationLogDataTable;
use App\DataTables\UserDataTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(UserDataTable $datatable)
    {
        $roleOption = ['' => 'Semua'] + Role::orderBy('name')->pluck('name', 'id')->all();
        $breadcrumbs = [['url' => '#', 'title' => 'Setting'], ['url' => '', 'title' => 'User']];

        return $datatable->render('user.index', compact('roleOption', 'breadcrumbs'));
    }

    public function create()
    {
        $breadcrumbs = [['url' => '#', 'title' => 'Setting'], ['url' => route('user.index'), 'title' => 'User'], ['title' => 'Tambah User']];

        $roleOption = Role::orderBy('name')->pluck('name', 'id')->all();

        return view('user.create', compact('roleOption', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:30',
            'role' => 'required',
            'password' => 'required|confirmed',
            'email' => 'required|email|unique:users',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('user.index');
    }

    public function show(User $user, AuthenticationLogDataTable $authenticationLogDataTable)
    {
        checkUuid($user->uuid);

        $breadcrumbs = [['url' => '#', 'title' => 'Setting'], ['url' => route('user.index'), 'title' => 'User'], ['title' => 'Detail '.$user->name]];

        return $authenticationLogDataTable->render('user.show', compact('user', 'breadcrumbs'));
    }

    public function edit(User $user)
    {
        checkUuid($user->uuid);

        $breadcrumbs = [['url' => '#', 'title' => 'Setting'], ['url' => route('user.index'), 'title' => 'User'], ['title' => 'Edit '.$user->name]];

        $roleOption = Role::orderBy('name')->pluck('name', 'id')->all();

        return view('user.edit', compact('user', 'roleOption', 'breadcrumbs'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|max:30',
            'role' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $user->update($validated);

        return redirect()->route('user.index');
    }

    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|confirmed',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('user.index');
    }

    public function destroy(User $user)
    {
        checkUuid($user->uuid);

        if ($user->isSuperadmin()) {
            return response()->json(['message' => 'Superadmin tidak dapat dihapus'], 400);
        }

        if ($user->delete()) {
            return response()->json(['success' => true, 'redirect' => route('user.index')]);
        }

        return response()->json(['message' => 'Data sedang digunakan'], 400);
    }

    public function banned(User $user, $status)
    {
        checkUuid($user->uuid);

        if ($status == 'banned') {
            $user->banned();
            $message = 'Data berhasil dinonaktifkan';
        }

        if ($status == 'unbanned') {
            $user->unBanned();
            $message = 'Data berhasil diaktifkan';
        }

        return response()->json(['success' => true, 'message' => $message, 'redirect' => route('user.index')]);
    }
}
