<?php

namespace App\Http\Controllers;

use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if(Auth::user()->role == 'supplier') {
            return $this->supplierDashboard();
        } else {
            return $this->adminDashboard();
        }
    }

    private function supplierDashboard() {
        return view('supplier.products');
    }

    private function adminDashboard() {
        $supplier = User::where('role', 'supplier')->count();
        $store = User::where('role', 'store')->count();
        return view('admin-dashboard', compact('supplier', 'store'));
    }
}
