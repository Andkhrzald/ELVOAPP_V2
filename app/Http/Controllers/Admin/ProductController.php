<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // Mengambil semua produk beserta kategorinya agar tidak error saat dipanggil di view
        $products = Product::with('category')->get(); 
        return view('admin.products', compact('products')); 
    }

    public function store(Request $request)
{
    // 1. Validasi Input (Tambahkan description di sini)
    $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required',
        'description' => 'required', // Tambahkan validasi ini
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'weight' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    // 2. Handle Upload Foto
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
    }

    // 3. Simpan ke Database (Pastikan 'description' ikut disimpan)
    Product::create([
        'name'        => $request->name,
        'slug'        => \Illuminate\Support\Str::slug($request->name),
        'category_id' => $request->category_id,
        'description' => $request->description, // WAJIB ADA INI
        'color'       => $request->color,
        'price'       => $request->price,
        'stock'       => $request->stock,
        'weight'      => $request->weight,
        'image'       => $imagePath,
    ]);

    return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan!');
}
}