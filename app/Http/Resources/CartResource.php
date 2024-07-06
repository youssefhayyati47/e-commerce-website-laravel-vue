<?php

namespace App\Http\Resources;

use App\Helper\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    public function toArray(Request $request): array
    {
        // info($request);
        [$products, $cartItems] = $this->resource;
        return [
            'count' => Cart::getCount(),
            'total' => $products->reduce(function ($total, $product) use ($cartItems) {
                return $total + $product->price * $cartItems[$product->id]['quantity'];
            }),
            'items' => $cartItems,
            'products' => ProductResource::collection($products)
        ];
    }
}
