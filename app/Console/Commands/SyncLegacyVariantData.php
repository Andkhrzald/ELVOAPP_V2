<?php

namespace App\Console\Commands;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('products:sync-legacy')]
#[Description('Create default variants for legacy products and link orphaned order items')]
class SyncLegacyVariantData extends Command
{
    public function handle()
    {
        $this->info('Syncing legacy variant data...');
        $updated = 0;
        $variantsCreated = 0;

        $products = Product::with('variants')->get();

        foreach ($products as $product) {
            $variant = null;

            if ($product->variants->isEmpty()) {
                // Create a default variant for products without variants
                $color = $product->color ?? 'Default';
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'size'       => null,
                    'color'      => $color,
                    'color_hex'  => '#cccccc',
                    'stock'      => $product->stock,
                    'price'      => $product->price,
                    'image'      => $product->image,
                    'is_active'  => true,
                ]);
                $variantsCreated++;
                $this->line("  Created default variant '{$color}' for Product #{$product->id} ({$product->name})");
            } else {
                $variant = $product->variants->first();
            }

            // Link orphaned order items
            $items = OrderItem::where('product_id', $product->id)
                ->whereNull('variant_id')
                ->get();

            foreach ($items as $item) {
                $label = $variant->color;
                if ($variant->size) {
                    $label .= ' / ' . $variant->size;
                }
                $item->update([
                    'variant_id'    => $variant->id,
                    'variant_label' => $label,
                ]);
                $updated++;
            }

            if ($items->isNotEmpty()) {
                $this->line("  Linked {$items->count()} order item(s) for Product #{$product->id}");
            }
        }

        $this->newLine();
        $this->info("Done! Created {$variantsCreated} variant(s), linked {$updated} order item(s).");
    }
}
