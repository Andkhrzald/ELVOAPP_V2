<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockMutation;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Exports\ProductExport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'hidden') {
                $query->where('is_active', false);
            }
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $hiddenProducts = Product::where('is_active', false)->count();
        $lowStockProducts = Product::where('stock', '<', 5)->count();
        $categories = Category::orderBy('name')->get();

        return view('admin.products', compact(
            'products', 'totalProducts', 'activeProducts',
            'hiddenProducts', 'lowStockProducts', 'categories'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'weight' => 'nullable',
            'color' => 'nullable|string|max:50',
            'material' => 'nullable|string|max:255',
            'diameter' => 'nullable|numeric',
            'panjang_kalung' => 'nullable|numeric',
            'kapasitas' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'uploads');
        }

        $product = Product::create([
            'name'          => $request->name,
            'slug'          => Str::slug($request->name),
            'category_id'   => $request->category_id,
            'description'   => $request->description,
            'color'         => $request->color,
            'material'      => $request->material,
            'price'         => $request->price,
            'stock'         => $request->stock,
            'weight'        => $request->weight,
            'diameter'      => $request->diameter,
            'panjang_kalung' => $request->panjang_kalung,
            'kapasitas'     => $request->kapasitas,
            'image'         => $imagePath,
            'is_active'     => $request->has('is_active') ? $request->is_active : true,
        ]);

        // Gallery images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $img) {
                $path = $img->store('products', 'uploads');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => $path,
                    'sort_order' => $i,
                    'is_primary' => $i === 0 && !$imagePath,
                ]);
            }
        }

        // Variants
        if ($request->has('variants')) {
            foreach ($request->variants as $v) {
                $vPath = null;
                if (isset($v['image']) && $v['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $vPath = $v['image']->store('products/variants', 'uploads');
                }
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size'       => $v['size'] ?? null,
                    'color'      => $v['color'] ?? null,
                    'color_hex'  => $v['color_hex'] ?? null,
                    'stock'      => $v['stock'] ?? 0,
                    'price'      => $v['price'] ?? null,
                    'image'      => $vPath,
                    'is_active'  => true,
                ]);
            }
            // Recalculate product stock from variants
            $totalStock = ProductVariant::where('product_id', $product->id)->sum('stock');
            $product->update(['stock' => $totalStock]);

            StockMutation::log($product, 'in', $totalStock, 'Stok awal dari varian');
        } else {
            StockMutation::log($product, 'in', $request->stock ?? 0, 'Stok awal produk baru');
        }

        return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = Product::with(['images', 'variants'])->findOrFail($id);
        $categories = Category::all();
        return view('admin.products_edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'weight' => 'nullable',
            'color' => 'nullable|string|max:50',
            'material' => 'nullable|string|max:255',
            'diameter' => 'nullable|numeric',
            'panjang_kalung' => 'nullable|numeric',
            'kapasitas' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('uploads')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'uploads');
        }

        if ($request->has('is_active')) {
            $data['is_active'] = $request->is_active;
        }

        $oldStock = $product->stock;
        $product->update($data);

        if ((int) $request->stock !== $oldStock) {
            StockMutation::log($product, 'adjustment', (int) $request->stock, 'Penyesuaian stok oleh admin');
        }

        // Handle gallery
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $img) {
                $path = $img->store('products', 'uploads');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => $path,
                    'sort_order' => $product->images->count() + $i,
                    'is_primary' => false,
                ]);
            }
        }

        // Handle variants
        if ($request->has('variants')) {
            $existingVariants = $product->variants()->get()->keyBy(function($v) {
                return ($v->color ?? 'Default') . '_' . ($v->size ?? '');
            });
            $product->variants()->delete();
            foreach ($request->variants as $v) {
                $vPath = null;
                if (isset($v['image']) && $v['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $vPath = $v['image']->store('products/variants', 'uploads');
                } else {
                    $colorSizeKey = ($v['color'] ?? 'Default') . '_' . ($v['size'] ?? '');
                    if ($existingVariants->has($colorSizeKey) && $existingVariants[$colorSizeKey]->image) {
                        $vPath = $existingVariants[$colorSizeKey]->image;
                    }
                }
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size'       => $v['size'] ?? null,
                    'color'      => $v['color'] ?? null,
                    'color_hex'  => $v['color_hex'] ?? null,
                    'stock'      => $v['stock'] ?? 0,
                    'price'      => $v['price'] ?? null,
                    'image'      => $vPath,
                    'is_active'  => true,
                ]);
            }
            // Recalculate product stock from variants
            $totalStock = ProductVariant::where('product_id', $product->id)->sum('stock');
            $product->update(['stock' => $totalStock]);
        }

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

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::disk('uploads')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus!');
    }

    public function destroyImage($id)
    {
        $image = ProductImage::findOrFail($id);
        Storage::disk('uploads')->delete($image->image);
        $image->delete();
        return response()->json(['success' => true]);
    }

    public function setPrimaryImage($id)
    {
        $image = ProductImage::with('product')->findOrFail($id);
        $image->product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
        return response()->json(['success' => true]);
    }

    public function destroyVariant($id)
    {
        $variant = ProductVariant::findOrFail($id);
        if ($variant->image) {
            Storage::disk('uploads')->delete($variant->image);
        }
        $variant->delete();
        return response()->json(['success' => true]);
    }

    public function export()
    {
        return ProductExport::export();
    }
}
