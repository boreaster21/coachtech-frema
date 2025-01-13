<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggleFavorite($id)
    {
        $user = Auth::user();
        $user->favorites()->toggle($id); 
        return back();
    }

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }

 
    public function store(Request $request)
    {
        //
    }


    public function show(Favorite $favorite)
    {
        //
    }

    public function edit(Favorite $favorite)
    {
        //
    }


    public function update(Request $request, Favorite $favorite)
    {
        //
    }

    public function destroy(Favorite $favorite)
    {
        //
    }
}
