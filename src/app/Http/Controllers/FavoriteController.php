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
}
