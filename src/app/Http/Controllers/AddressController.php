<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function edit()
    {
        return view('address.edit');
    }
    public function update(Request $request)
    {
        $request->validate([
            'postal_code' => 'required|string|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);


        $user = Auth::user();
        $user->address = $request->address;
        $user->save();

        return redirect()->route('purchase', ['id' => 1]) 
            ->with('success', '住所が更新されました。');
    }
}