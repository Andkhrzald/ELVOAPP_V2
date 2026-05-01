<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; // JANGAN LUPA IMPORT INI

class ProductController extends Controller
{
    public function index()
    {
        // 1. Ambil data dari database
        $products = Product::all(); 

        // 2. Kirim variabel $products ke folder admin file products.blade.php
        return view('admin.products', compact('products')); 
    }
}