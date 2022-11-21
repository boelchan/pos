<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\HistoryProduct;
use App\Models\Product;
use App\Models\ProductTranscation;
use App\Models\Transcation;
use Auth;
use Darryldecode\Cart\CartCondition;
use DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class TransactionController extends Controller
{
    public function index()
    {
        //product
        $products = Product::when(request('search'), function ($query) {
            return $query->where('name', 'like', '%'.request('search').'%');
        })
            ->orderBy('name', 'asc')
            ->simplePaginate(20);

        //cart item
        if (request()->discount) {
            $discount = request()->discount;
        } else {
            $discount = 0;
        }

        $condition = new CartCondition([
            'name' => 'diskon',
            'type' => 'tax', //tipenya apa
            'target' => 'total', //target kondisi ini apply ke mana (total, subtotal)
            'value' => '-'.$discount, //contoh -12% or -10 or +10 etc
            'order' => 1,
        ]);

        \Cart::session(auth()->id())->condition($condition);

        $items = \Cart::session(auth()->id())->getContent();

        if (\Cart::isEmpty()) {
            $cart_data = [];
        } else {
            foreach ($items as $row) {
                $cart[] = [
                    'rowId' => $row->id,
                    'name' => $row->name,
                    'qty' => $row->quantity,
                    'pricesingle' => $row->price,
                    'price' => $row->getPriceSum(),
                    'created_at' => $row->attributes['created_at'],
                ];
            }

            $cart_data = collect($cart)->sortBy('created_at');
        }

        $sub_total = \Cart::session(auth()->id())->getSubTotal();
        // diskon
        $new_condition = \Cart::session(auth()->id())->getCondition('diskon');
        $discount = $new_condition->getCalculatedValue($sub_total);

        //total
        $total = \Cart::session(auth()->id())->getTotal();

        $data_total = [
            'discount' => $discount,
            'sub_total' => $sub_total,
            'total' => $total,
        ];

        $customers = Customer::orderBy('nama', 'asc')->pluck('nama', 'id')->all();

        return view('pos.index', compact('products', 'cart_data', 'data_total', 'customers'));
    }

    public function add($id)
    {
        $product = Product::findOrfail($id);

        $cart = \Cart::session(auth()->id())->getContent();
        $cek_itemId = $cart->whereIn('id', $id);

        if ($cek_itemId->isNotEmpty()) {
            if ($product->qty == $cek_itemId[$id]->quantity) {
                return redirect()->back()->with('error', 'jumlah item kurang');
            } else {
                \Cart::session(auth()->id())->update($id, [
                    'quantity' => 1,
                ]);
            }
        } else {
            \Cart::session(auth()->id())->add([
                'id' => $id,
                'name' => $product->sku.' - '.$product->name,
                'price' => $product->price,
                'quantity' => 1,
                'attributes' => [
                    'created_at' => date('Y-m-d H:i:s'),
                ],
            ]);
        }

        return redirect()->back();
    }

    public function update($id, Request $request)
    {
        $product = Product::find($id);

        $cart = \Cart::session(auth()->id())->getContent();
        $cek_itemId = $cart->whereIn('id', $id);

        if ($product->qty == $cek_itemId[$id]->quantity) {
            return redirect()->back()->with('error', 'jumlah item kurang');
        } else {
            \Cart::session(auth()->id())->update($id, [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->qty,
                ],
            ]);

            return redirect()->back();
        }
    }

    public function decrease($id)
    {
        $product = Product::find($id);

        $cart = \Cart::session(auth()->id())->getContent();
        $cek_itemId = $cart->whereIn('id', $id);

        if ($cek_itemId[$id]->quantity == 1) {
            \Cart::session(auth()->id())->remove($id);
        } else {
            \Cart::session(auth()->id())->update($id, [
                'quantity' => [
                    'relative' => true,
                    'value' => -1,
                ],
            ]);
        }

        return redirect()->back();
    }

    public function increase($id)
    {
        $product = Product::find($id);

        $cart = \Cart::session(auth()->id())->getContent();
        $cek_itemId = $cart->whereIn('id', $id);

        if ($product->qty == $cek_itemId[$id]->quantity) {
            return redirect()->back()->with('error', 'jumlah item kurang');
        } else {
            \Cart::session(auth()->id())->update($id, [
                'quantity' => [
                    'relative' => true,
                    'value' => 1,
                ],
            ]);

            return redirect()->back();
        }
    }

    public function remove($id)
    {
        \Cart::session(auth()->id())->remove($id);

        return redirect()->back();
    }

    public function clear()
    {
        \Cart::session(auth()->id())->clear();

        return redirect()->back();
    }

    public function bayar()
    {
        $cart_total = \Cart::session(auth()->id())->getTotal();
        $sub_total = \Cart::session(auth()->id())->getSubTotal();
        $customer_id = request()->customer_id;
        $bayar = request()->bayar;
        $kembalian = (int) $bayar - (int) $cart_total;

        $diskon = \Cart::session(auth()->id())->getCondition('diskon');

        if ($kembalian >= 0) {
            DB::beginTransaction();

            try {
                $all_cart = \Cart::session(auth()->id())->getContent();

                $filterCart = $all_cart->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'quantity' => $item->quantity,
                    ];
                });

                foreach ($filterCart as $cart) {
                    $product = Product::find($cart['id']);

                    if ($product->qty == 0) {
                        return redirect()->back()->with('errorTransaksi', 'jumlah pembayaran gak valid');
                    }

                    HistoryProduct::create([
                        'product_id' => $cart['id'],
                        'user_id' => auth()->id(),
                        'qty' => $product->qty,
                        'qtyChange' => -$cart['quantity'],
                        'tipe' => 'decrease from transaction',
                    ]);

                    $product->decrement('qty', $cart['quantity']);
                }

                $id = IdGenerator::generate(['table' => 'transcations', 'length' => 10, 'prefix' => 'INV-', 'field' => 'invoices_number']);

                Transcation::create([
                    'invoices_number' => $id,
                    'user_id' => Auth::id(),
                    'pay' => request()->bayar,
                    'discount' => $diskon->getValue(),
                    'sub_total' => $sub_total,
                    'total' => $cart_total,
                    'customer_id' => $customer_id,
                ]);

                foreach ($filterCart as $cart) {
                    $product = Product::find($cart['id']);

                    ProductTranscation::create([
                        'product_id' => $cart['id'],
                        'invoices_number' => $id,
                        'qty' => $cart['quantity'],
                        'price' => $product->price,
                        'sub_total' => ($cart['quantity'] * $product->price),
                    ]);
                }

                \Cart::session(auth()->id())->clear();

                DB::commit();

                return redirect()->route('laporan', $id)->with('success', 'Transaksi Berhasil');
            } catch (\Exeception $e) {
                DB::rollback();

                return redirect()->back()->with('errorTransaksi', 'jumlah pembayaran gak valid');
            }
        }

        return redirect()->back()->with('errorTransaksi', 'jumlah pembayaran gak valid');
    }

    public function history(Request $request)
    {
        $history = Transcation::orderBy('created_at', 'desc')
            ->when($request->tanggal, function ($query) use ($request) {
                return $query->whereDate('created_at', $request->tanggal);
            })
            ->paginate(10);

        return view('pos.history', compact('history'));
    }

    public function edit($id)
    {
        $transaksi = Transcation::findOrfail($id);

        return view('pos.edit', compact('transaksi'));
    }

    public function updateTanggal(Request $request)
    {
        $this->validate($request, [
            'created_at' => 'required',
        ]);

        $transaksi = Transcation::findOrfail($request->id);

        $transaksi->created_at = $request->created_at;
        $transaksi->save();

        return redirect()->back();
    }

    public function laporan($id)
    {
        $transaksi = Transcation::with('productTranscation')->findOrfail($id);

        return view('laporan.transaksi', compact('transaksi'));
    }

    public function cetakLaporan($id)
    {
        $transaksi = Transcation::with('productTranscation')->findOrfail($id);
        $detail = ProductTranscation::where('invoices_number', $id)->get();

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('laporan.cetak-pdf', ['trx' => $transaksi, 'detail' => $detail]);
        // $pdf->loadHTML('laporan.cetak-pdf');
        return $pdf->stream();
    }
}
//© 2020 Copyright: Tahu Coding
