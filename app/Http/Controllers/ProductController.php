<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        if(request()->ajax()){
            return datatables()->of(Product::select('*'))
            ->addColumn('action', 'product-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('index');
    }

    public function store(Request $request)
    {
        $productId = $request->id;

        $product = Product::updateOrCreate(
            [
                'id' => $productId
            ],
            [
                'name' => $request->name,
                'qty' => $request->qty,
                'price' => $request->price,
                'description' => $request->description
            ]
        );

        return Response()->json($product);
    }

    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $product = Product::where($where)->first();

        return Response()->json($product);
    }

    public function destroy(Request $request)
    {
        $product = Product::where('id', $request->id)->delete();

        return Response()->json($product);
    }
}
