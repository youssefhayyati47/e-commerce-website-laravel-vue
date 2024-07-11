<?php

namespace App\Http\Controllers\User;

use App\Helper\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\UserAdress;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{

    public function view(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $cartItems = CartItem::where('user_id', $user->id)->get();
            $userAddress = UserAdress::where('user_id', $user->id)->first();

            if ($cartItems->count() <= 0) {
                return Inertia::render('User/CartList', [
                    'cartItems' => $cartItems,
                    'userAddress' => $userAddress
                ]);
            } else {
                $cartItems = Cart::getCookieCartItems();

                if (count($cartItems) <= 0) {

                    $cartItems = new CartResource(Cart::getProductsAndCartItems());

                    return Inertia::render('User/CartList', [
                        'cartItems' => $cartItems,
                    ]);
                } else {
                    return back()->with('info', 'Cart is empty');
                }
            }
        }
        return Inertia::render('User/CartList');
    }

    public function store(Request $request, Product $product)
    {
        $quantity = $request->post('quantity', 1); // default quantity is 1

        $user = $request->user();

        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();

            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => 1
                ]);
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            $isProductExist = false;
            foreach ($cartItems as $key => $cartItem) {
                if ($cartItem['product_id'] == $product->id) {
                    info($cartItem);
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
            if ($cartItem) {
                $cartItem->update(['quantity' => $quantity]);
            }
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
        info($product);
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
