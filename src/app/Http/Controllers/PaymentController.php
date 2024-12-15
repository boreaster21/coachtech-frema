<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function purchase(Product $product)
    {
        if ($product->is_sold) {
            abort(403, 'この商品は既に購入されています。');
        }

        $paymentMethod = session('payment_method', 'コンビニ払い');

        if ($paymentMethod === 'Stripe') {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $product->name,
                        ],
                        'unit_amount' => $product->price * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('purchase.success', ['product' => $product->id]),
                'cancel_url' => route('purchase', ['product' => $product->id]),
            ]);

            return view('purchase', [
                'product' => $product,
                'sessionId' => $session->id,
            ]);
        }

        return view('purchase', compact('product'));
    }

    public function stripePurchase(Request $request, Product $product)
    {
        if (Purchase::where('user_id', auth()->id())->where('product_id', $product->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'この商品は既に購入されています。'], 403);
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $product->name,
                        ],
                        'unit_amount' => $product->price * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('purchase.success.get', $product->id), // 新しいGET用ルート
                'cancel_url' => route('purchase', $product->id),
            ]);

            return response()->json([
                'success' => true,
                'sessionId' => $session->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function successGet(Request $request, Product $product)
    {
        if ($product->is_sold) {
            return redirect()->route('mypage', ['tab' => 'purchased'])
            ->with('status', 'この商品は既に購入されています。');
        }

        $product->update(['is_sold' => true]);

        Purchase::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'purchased_at' => now(),
            'status' => 'completed',
        ]);

        return redirect()->route('mypage', ['tab' => 'purchased'])
        ->with('status', '購入が完了しました。');
    }


    public function success(Request $request, Product $product)
    {
        if ($product->is_sold) {
            return redirect()->route('mypage', ['tab' => 'purchased'])
            ->with('status', 'この商品は既に購入されています。');
        }

        $product->update(['is_sold' => true]);

        $paymentMethod = session('payment_method', 'コンビニ払い');
        $payment = Payment::where('method_name', $paymentMethod)->first();

        Purchase::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'payment_id' => $payment ? $payment->id : null,
            'purchased_at' => now(),
            'status' => 'completed',
        ]);

        return redirect()->route('mypage', ['tab' => 'purchased'])
        ->with('status', '購入が完了しました。');
    }

    public function edit(Request $request)
    {
        if ($request->has('redirect_to')) {
            session(['redirect_to' => $request->input('redirect_to')]);
        }

        $payments = Payment::all(); 
        return view('payment.edit', compact('payments'));
    }


    public function update(Request $request)
    {
        $request->validate(['payment_method' => 'required|string']);
        session(['payment_method' => $request->payment_method]);

        return redirect(session('redirect_to', route('mypage')))
            ->with('success', '支払い方法が更新されました。');
    }

}
