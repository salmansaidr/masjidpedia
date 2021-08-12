<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        \Validator::make($input, [
            'name' => 'required',
            'stock' => 'required'
        ],[
            'name.required' => 'Nama produk wajib diisi',
            'stock.required' => 'stok produk wajib diisi'
        ])->validate();

        $product = new Product();
        $product->name = $input['name'];
        $product->stock = $input['stock'];
        $product->user_id = Auth::user()->id;
        $product->save();

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    public function edit(Request $request, $id)
    {
        if($request->ajax()) {
            $product = Product::select('id', 'name', 'stock')->whereKey($id)->first();
            return $product;
        } else {
            return redirect('/');
        }
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        \Validator::make($input, [
            'name' => 'required',
            'stock' => 'required'
        ],[
            'name.required' => 'Nama produk wajib diisi',
            'stock.required' => 'stok produk wajib diisi'
        ])->validate();

        $product = Product::findOrFail($id);
        $product->name = $input['name'];
        $product->stock = $input['stock'];
        $product->user_id = Auth::user()->id;
        $product->save();

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    public function show($id) {
        $decID = \Crypt::decrypt($id);
        $product = Product::findOrFail($decID);
        return view('product.detail', compact('product'));
    }

    public function delete($id) {
        Product::destroy($id);
        return 'success';
    }

    public function datatable(Request $request) {
        $base_code = Product::where('user_id', Auth::user()->id);
        
        $columns = [
            'id',
            'name',
            'stock',
            'options'
        ];

        $totalData = $base_code->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        if(empty($request->input('search.value'))) {
            $products = $base_code->offset($start)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $search = $request->input('search.value');
            $base_code = Product::where([['user_id', Auth::user()->id],['name', 'LIKE',  '%' .$search . '%']]);
            $products = $base_code->offset($start)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->get();
            $totalFiltered = $base_code->count();
        }

        $data = array();
        if(!empty($products)) {
            foreach($products as $p) {

                $nestedData[$columns[0]] = $p->id;
                $nestedData[$columns[1]] = $p->name;
                $nestedData[$columns[2]] = $p->stock;
                $nestedData[$columns[3]] = '
                    <button class="btn btn-danger btn-sm delete-btn" idproduct="'.$p->id.'">hapus</button>
                    <button class="btn btn-warning btn-sm edit-btn" idproduct="'.$p->id.'">ubah</button>
                    <a href="'. route('product.show', ['id' => \Crypt::encrypt($p->id)]) .'" class="btn btn-success btn-sm">detail</a>

                ';

                $data[] = $nestedData;
            }

        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);

    }
}
