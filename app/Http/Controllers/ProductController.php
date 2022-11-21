<?php

namespace App\Http\Controllers;

use App\DataTables\ProductDataTable;
use App\Models\HistoryProduct;
use App\Models\Product;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function index(ProductDataTable $productDataTable)
    {
        return $productDataTable->render('product.index');
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $id = $request->id;

            if ($id) {
                $this->validate($request, [
                    'name' => 'required|min:2|max:200',
                    'price' => 'required',
                    // 'description' => 'required',
                ]);

                if ($request->addQty) {
                    $qty = $request->qty + $request->addQty;
                    if ($qty < 0) {
                        return redirect()->back()->with('errorQty', 'Quantity cant be lower than zero');
                    }
                } else {
                    $qty = $request->qty;
                }

                $product_id = Product::find($id);
                if ($request->has('image')) {
                    $gambar = $request->image;
                    $new_gambar = time().$gambar->getClientOriginalName();
                    Image::make($gambar->getRealPath())->resize(null, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save(public_path('uploads/images/'.$new_gambar));

                    File::delete(public_path($product_id->image));

                    $product = [
                        'name' => $request->name,
                        'price' => $request->price,
                        'qty' => $qty,
                        'image' => 'uploads/images/'.$new_gambar,
                        'description' => $request->description,
                    ];
                } else {
                    $product = [
                        'name' => $request->name,
                        'price' => $request->price,
                        'qty' => $qty,
                        'description' => $request->description,
                    ];
                }
                $product_id->update($product);
                if ($request->addQty) {
                    HistoryProduct::create([
                        'product_id' => $product_id->id,
                        'user_id' => Auth::id(),
                        'qty' => $request->qty,
                        'qtyChange' => $request->addQty,
                        'tipe' => 'change product qty from admin',
                    ]);
                }

                $message = 'Data Berhasil di update';

                DB::commit();

                return redirect()->back()->with('success', $message);
            } else {
                $this->validate($request, [
                    'name' => 'required|min:2|max:200',
                    'price' => 'required',
                    'qty' => 'required',
                    // 'image' => 'mimes:jpeg,jpg,png,gif|max:25000',
                    // 'description' => 'required',
                ]);

                // $gambar = $request->image;
                // $new_gambar = time().$gambar->getClientOriginalName();

                $product = Product::create([
                    'name' => $request->name,
                    'price' => $request->price,
                    'qty' => $request->qty,
                    // 'image' => 'uploads/images/'.$new_gambar,
                    // 'description' => $request->description,
                    'user_id' => Auth::id(),
                ]);

                // Image::make($gambar->getRealPath())->resize(null, 200, function ($constraint) {
                //     $constraint->aspectRatio();
                // })->save(public_path('uploads/images/' . $new_gambar));

                HistoryProduct::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'qty' => $request->qty,
                    'qtyChange' => 0,
                    'tipe' => 'created product',
                ]);

                $message = 'Data Berhasil di simpan';

                DB::commit();

                return redirect()->route('products.index')->with('success', $message);
            }
        } catch (\Exeception $e) {
            DB::rollback();

            return redirect()->route('products.create')->with('error', $e);
        }
    }

    public function edit(Product $product)
    {
        $breadcrumbs = [['url' => route('product.index'), 'title' => 'Produk'], ['title' => 'Edit '.$product->name]];

        $history = HistoryProduct::where('product_id', $product->id)->orderBy('created_at', 'desc')->get();

        return view('product.edit', compact('product', 'history', 'breadcrumbs'));
    }

    public function destroy(Product $product)
    {
        if (! $product->history()->count()) {
            $product->delete();

            return response()->json(['message' => 'Berhasil dihapus']);
        }

        return response()->json(['message' => 'Data sedang digunakan'], 400);
    }
}
