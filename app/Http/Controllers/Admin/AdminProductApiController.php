<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProductApiController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search', '');

        $products = DB::table('products')
            ->select([
                'products.id',
                'products.name',
                'products.sku',
                'products.price',
                'products.discount_price',
                'products.stock',
                'product_media.file_path as image'
            ])
            ->leftJoin('product_media', function($join) {
                $join->on('products.id', '=', 'product_media.product_id')
                     ->where('product_media.is_primary', '=', 1);
            })
            ->where('products.status', 'active')
            ->where(function($query) use ($search) {
                if (!empty($search)) {
                    $query->where('products.name', 'like', '%' . $search . '%')
                          ->orWhere('products.sku', 'like', '%' . $search . '%');
                }
            })
            ->limit(20)
            ->get();

        // Decode JSON name field and format response
        $formattedProducts = $products->map(function($product) {
            $name = json_decode($product->name, true);
            $displayName = is_array($name) ? ($name['en'] ?? $name[array_key_first($name)] ?? 'Unknown') : $product->name;

            // Use discount price if available, otherwise regular price
            $finalPrice = $product->discount_price ?? $product->price;

            return [
                'id' => $product->id,
                'name' => $displayName,
                'sku' => $product->sku,
                'price' => $finalPrice,
                'original_price' => $product->price,
                'discount_price' => $product->discount_price,
                'stock' => $product->stock,
                'image' => $product->image ? asset('storage/' . $product->image) : null,
            ];
        });

        return response()->json($formattedProducts);
    }
}
