<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OperatorController extends Controller
{
    public function index()
    {
        $operators = User::when(request('search'), function ($query) {
            return $query->where('name', 'like', '%'.request('search').'%');
        })
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->where('role_id', 2)
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return view('operator.index', compact('operators'));
    }

    public function create()
    {
        return view('operator.create');
    }

    public function store(Request $request)
    {
        if ($request->id) {
            $this->validate($request, [
                'name' => 'required|min:2|max:200',
                'email' => 'required|unique:users,email,'.$request->id,
                'password' => 'confirmed',
            ]);

            $customer = User::find($request->id);
            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
            if ($request->password) {
                $customer->password = Hash::make($request->password);
                $customer->save();
            }
        } else {
            $this->validate($request, [
                'name' => 'required|min:2|max:200',
                'email' => 'required|unique:users',
                'password' => 'confirmed|required',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->attachRole('kasir');
        }

        return redirect()->route('operators.index')->with('success', 'Berhasil disimpan');
    }

    public function edit($id)
    {
        $operator = User::find($id);

        return view('operator.edit', compact('operator'));
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $user = User::find($id);
            $user->delete();

            DB::commit();

            return redirect()->route('operators.index')->with('success', 'user berhasil dihapus');
        } catch (\Exeception $e) {
            DB::rollback();

            return redirect()->route('operators.index')->with('error', $e);
        }
    }
}
