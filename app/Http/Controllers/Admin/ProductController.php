<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'hidden') {
                $query->where('is_active', false);
            }
        }

        $products = $query->latest()->get();
        return view('admin.products', compact('products')); 
    }

    public function store(Request $request)
{
    // 1. Validasi Input (Tambahkan description di sini)
    $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required',
        'description' => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'weight' => 'required',
        'color' => 'nullable|string|max:50',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    // 2. Handle Upload Foto
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'uploads');
    }

    // 3. Simpan ke Database (Pastikan 'description' ikut disimpan)
    Product::create([
        'name'        => $request->name,
        'slug'        => \Illuminate\Support\Str::slug($request->name),
        'category_id' => $request->category_id,
        'description' => $request->description,
        'color'       => $request->color,
        'price'       => $request->price,
        'stock'       => $request->stock,
        'weight'      => $request->weight,
        'image'       => $imagePath,
        'is_active'   => $request->has('is_active') ? $request->is_active : true,
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
            'color' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Jika ada upload foto baru
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('uploads')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'uploads');
        }

        if ($request->has('is_active')) {
            $data['is_active'] = $request->is_active;
        }

        $product->update($data);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diupdate!');
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();

        return response()->json([
            'success' => true,
            'is_active' => $product->is_active
        ]);
    }

    // 3. Fungsi untuk Hapus data
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus foto dari folder uploads sebelum data dihapus
        if ($product->image) {
            Storage::disk('uploads')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus!');
    }
}