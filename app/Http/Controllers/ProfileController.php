<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        return view('profile.index', compact('user'));
    }

    public function store(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);

        $request->validate([
            'name' => 'required|max:30',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $user->name = ucwords(strtolower($request->name));
        $user->email = $request->email;
        $user->save();

        return redirect()->route('profile.index')->with('message', 'Ubah Data Berhasil');
    }

    public function changePasswordStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password_old' => new MatchOldPassword,
            'password' => 'required',
            'password_confirmation' => 'same:password',
        ]);

        if ($validator->fails()) {
            return redirect()->route('profile.index')->withFragment('tabs-password')->withErrors($validator)->withInput();
        }

        $user = User::findOrFail(auth()->user()->id);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.index')->withFragment('tabs-password')->with('message', 'Ubah Password Berhasil');
    }
}
