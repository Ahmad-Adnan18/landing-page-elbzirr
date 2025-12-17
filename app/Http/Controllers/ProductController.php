<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('variants')->where('is_active', true)->get();

        return Inertia::render('Menu/Index', [
            'products' => $products,
        ]);
    }

    public function show($slug)
    {
        // Assuming we might use slug later, but for now let's use ID or find a way.
        // The tech spec says categories have slugs, but products table doesn't have slug in the summary.
        // Let's check the migration or model again. 
        // Summary says: products: category_id, name, description, image, active.
        // So no slug for product. I should probably use ID or add slug.
        // For now, I'll use ID.
        
        $product = Product::with(['category', 'variants'])->findOrFail($slug);

        return Inertia::render('Product/Show', [
            'product' => $product,
        ]);
    }
}
