<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        $customers = Customer::when(request('search'), function ($query) {
            return $query->where('name', 'like', '%'.request('search').'%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return view('customer.index', compact('customers'));
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|min:2|max:200',
            'alamat' => 'required',
        ]);

        if ($request->id) {
            $customer = Customer::find($request->id);
            $customer->update($request->all());
        } else {
            Customer::create([
                'nama' => $request->nama,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
            ]);
        }

        return redirect()->route('customer.index')->with('success', 'Berhasil disimpan');
    }

    public function edit($id)
    {
        $customer = Customer::find($id);

        return view('customer.edit', compact('customer'));
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $customer = Customer::find($id);
            $customer->delete();

            DB::commit();

            return redirect()->route('customer.index')->with('success', 'Customer berhasil dihapus');
        } catch (\Exeception $e) {
            DB::rollback();

            return redirect()->route('customer.index')->with('error', $e);
        }
    }
}
