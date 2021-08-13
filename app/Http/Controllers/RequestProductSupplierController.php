<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestProductSupplierController extends Controller
{
    public function index() {
        return view('supplier.request');
    }

    public static function countNewrequest() {
        $new_request = ProductRequest::where([['is_approve', 0],['supplier_id', Auth::user()->id]])->count();

        return $new_request > 0 ? true : false;
    }

    public function approve($id) {
        $productRequest = ProductRequest::findOrFail($id);
        $productRequest->is_approve = 1;
        $productRequest->save();

        $products = $productRequest->product()->get();

        foreach($products as $p) {
            $product = Product::findOrFail($p->id);
            if($product->stock != 0) {
                $new_stock = $product->stock - $p->pivot->amount;
                $product->stock = $new_stock;
                $product->save();

                if($productRequest->user->store()->where('product_id', $p->id)->exists()) {
                    $exist_stock = $productRequest->user->store()->where('product_id', $p->id)->first()->pivot->stock;
                    $productRequest->user->store()->updateExistingPivot($p->id, ['stock' => $exist_stock + $p->pivot->amount]);
                } else {
                    $productRequest->user->store()->attach($p->id, ['stock' => $p->pivot->amount]);
                }
            }
        }

        return 'success';
    }

    public function detailProduct($id) {
        $products = ProductRequest::findOrFail($id)->product()->get();
        return view('supplier.request_product', compact('products'));
    }

    public function datatable(Request $request) {
        $base_code = DB::table('product_requests')
        ->join('users', 'users.id', '=', 'product_requests.user_id')
        ->select('product_requests.id', 'product_requests.is_approve', 'product_requests.supplier_id', 'product_requests.created_at', 'users.name as username')
        ->where('product_requests.supplier_id', Auth::user()->id);
        
        $columns = [
            'id',
            'tanggal',
            'toko',
            'product',
            'options'
        ];

        $totalData = $base_code->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        if(empty($request->input('search.value'))) {
            $productRequest = $base_code->offset($start)
                ->limit($limit)
                ->orderBy('product_requests.id', 'desc')
                ->get();
        } else {
            $search = $request->input('search.value');
            $base_code =  DB::table('product_requests')
            ->join('users', 'users.id', '=', 'product_requests.user_id')
            ->select('product_requests.id', 'product_requests.is_approve', 'product_requests.created_at', 'users.name as username')
            ->where([['product_requests.supplier_id', Auth::user()->id], ['users.name', 'LIKE',  '%' .$search . '%']]);
            $productRequest = $base_code->offset($start)
                ->limit($limit)
                ->orderBy('product_requests.id', 'desc')
                ->get();
            $totalFiltered = $base_code->count();
        }

        $data = array();
        if(!empty($productRequest)) {
            foreach($productRequest as $pr) {
                if($pr->is_approve > 0) {
                    $btn_approve = '<span class="badge bg-success pt-2 pb-2">approved</span>';
                } else {
                    $btn_approve = '<button class="btn btn-warning btn-sm approve-btn" idrequest="'.$pr->id.'">approve</button>';
                }
                

                $nestedData[$columns[0]] = $pr->id;
                $nestedData[$columns[1]] = date('d-m-Y', strtotime($pr->created_at));
                $nestedData[$columns[2]] = $pr->username;
                $nestedData[$columns[3]] = '<a href="#" style="text-decoration: none;" idrequest="'.$pr->id.'" class="product-detail">Lihat List Produk</a>';
                $nestedData[$columns[4]] = $btn_approve;

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
