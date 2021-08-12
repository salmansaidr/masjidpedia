<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return $this->supplierDashboard();
    }

    private function supplierDashboard() {
        return view('supplier.products');
    }
}
