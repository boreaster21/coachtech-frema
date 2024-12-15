<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function mypage(Request $request)
    {
        $user = Auth::user();
        $tab = $request->input('tab', 'listed');

        $listedProducts = $user->products()->get();

        $purchasedProducts = Purchase::where('user_id', $user->id)
            ->with('product')
            ->get()
            ->map(function ($purchase) {
                $product = $purchase->product;
                $product->purchased_at = $purchase->purchased_at;
                return $product;
            });

        return view('mypage', compact('user', 'listedProducts', 'purchasedProducts', 'tab'));
    }

    public function getPurchasedItems()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('status', '購入履歴を表示するにはログインが必要です。');
        }

        $purchasedProducts = $user->purchases()->with('categories', 'conditions')->get();

        return view('mypage.purchased-items', compact('purchasedProducts'));
    }

}
