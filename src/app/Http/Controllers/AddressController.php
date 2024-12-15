<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * 住所変更ページを表示
     */
    public function edit()
    {
        return view('address.edit');
    }
    /**
     * 住所を更新
     */
    public function update(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
        ]);

        // ユーザーの住所を更新
        $user = Auth::user();
        $user->address = $request->address;
        $user->save();

        // 住所が更新されたら、購入確認ページに戻る
        return redirect()->route('purchase', ['id' => 1]) // 'id' は購入確認ページの商品ID
            ->with('success', '住所が更新されました。');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        //
    }
}
