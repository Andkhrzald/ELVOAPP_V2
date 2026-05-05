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

// 1. Fungsi untuk menampilkan halaman Edit
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        // Kita butuh kategori juga supaya bisa pilih kategori saat edit
        $categories = \App\Models\Category::all(); 
        return view('admin.products_edit', compact('product', 'categories'));
    }

    // 2. Fungsi untuk memproses Update data
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'weight' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Jika ada upload foto baru
        if ($request->hasFile('image')) {
            // Hapus foto lama dari storage agar tidak menumpuk sampah
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }
            // Simpan foto baru
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diupdate!');
    }

    // 3. Fungsi untuk Hapus data
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus foto dari folder storage sebelum data dihapus
        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }

        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus!');
    }
}