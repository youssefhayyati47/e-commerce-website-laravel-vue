<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Product/Index', [
            "products" => Product::with('category', 'brand', 'product_images')->get(),
            "categories" => Category::get(),
            "brands" => Brand::get()
        ]);
    }

    public function store(ProductRequest $request)
    {
        info($request->validated());
        $product = Product::create($request->validated());

        //if images exist

        if ($request->hasFile('product_images')) {
            $product_images = $request->file('product_images');

            foreach ($product_images as $image) {
                $name = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalName();

                $image->move(public_path('product_images'), $name);

                //create a product_image record for this product

                ProductImage::create([
                    "product_id" => $product->id,
                    "image" => 'product_images/' . $name
                ]);
            }
        }
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }
}
