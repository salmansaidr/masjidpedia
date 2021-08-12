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
        $new_request = ProductRequest::where('is_approve', 0)->whereHas('product', function($q) {
            $q->where('user_id', Auth::user()->id);
        })->count();

        return $new_request > 0 ? true : false;
    }

    public function approve($id) {
        $productRequest = ProductRequest::findOrFail($id);
        $productRequest->is_approve = 1;
        $productRequest->save();

        $product = Product::findOrFail($productRequest->product_id);
        $new_stock = $product->stock - $productRequest->amount;
        $product->stock = $new_stock;
        $product->save();
        return 'success';
    }

    public function datatable(Request $request) {
        $base_code = DB::table('product_requests')
        ->join('products', 'products.id', '=', 'product_requests.product_id')
        ->join('users', 'users.id', '=', 'product_requests.user_id')
        ->select('product_requests.id', 'product_requests.amount', 'product_requests.is_approve', 'users.name as username', 'products.name', 'products.user_id')
        ->where('products.user_id', Auth::user()->id);
        
        $columns = [
            'id',
            'toko',
            'product',
            'amount',
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
            ->join('products', 'products.id', '=', 'product_requests.product_id')
            ->join('users', 'users.id', '=', 'product_requests.user_id')
            ->select('product_requests.id', 'product_requests.amount', 'product_requests.is_approve', 'users.name as username', 'products.name', 'products.user_id')
            ->where([['products.user_id', Auth::user()->id], ['products.name', 'LIKE',  '%' .$search . '%']])
            ->orWhere([['products.user_id', Auth::user()->id], ['users.name', 'LIKE',  '%' .$search . '%']]);
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
                $nestedData[$columns[1]] = $pr->username;
                $nestedData[$columns[2]] = $pr->name;
                $nestedData[$columns[3]] = $pr->amount;
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
