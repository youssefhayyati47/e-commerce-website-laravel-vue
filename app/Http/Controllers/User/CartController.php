<?php

namespace App\Http\Controllers\User;

use App\Helper\Cart;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{

    public function index()
    {
        return Inertia::render('User/Cart');
    }

    public function store(Request $request, Product $product)
    {
        $quantity = $request->post('quantity', 1); // default quantity is 1

        $user = $request->user();

        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();

            if ($cartItem) {
                $cartItem->increament('quantity');
            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => 1
                ]);
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            info($cartItems);
            $isProductExist = false;
            foreach ($cartItems as $key => $cartItem) {
                if ($cartItem['product_id'] == $product->id) {
                    $cartItems[$key]['quantity'] += $quantity;
                    $isProductExist = true;
                    break;
                }
            }
            if (!$isProductExist) {
                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price
                ];
            }
            Cart::setCookieCartItems($cartItems);
        }

        return redirect()->back()->with('success', 'Item added successfully');
    }


    public function update(Request $request, Product $product)
    {
        $quantity = $request->integer('quantity');
        $user = $request->user();

        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as $key => $cartItem) {
                if ($cartItem['product_id'] == $product->id) {
                    $cartItems[$key]['quantity'] += $quantity;
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);
        }

        return redirect()->back();
    }


    public function destroy(Request $request, Product $product)
    {
        $user = $request->user();
        if ($user) {
            CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first()?->delete();
            if (CartItem::count() <= 0) {
                return redirect()->route('home')->with('info', 'Cart is empty');
            } else {
                return redirect()->back()->with('success', 'Item removed successfully');
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as $key => $cartItem) {
                if ($cartItem['product_id'] == $product->id) {
                    unset($cartItems[$key]);
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);
            if (count($cartItems) <= 0) {
                return redirect()->route('home')->with('info', 'Cart is empty');
            } else {
                return redirect()->back()->with('success', 'Item removed successfully');
            }
        }
    }
}
