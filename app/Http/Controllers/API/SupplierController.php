<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = User::select('id', 'name', 'email')->where('role', 'supplier')->get();
        return response(
            [
                'data' => [
                    'supplier' => $suppliers
                ],
                'message' => 'success'
            ],
            200);
    }
}
