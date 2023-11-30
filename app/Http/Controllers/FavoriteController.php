<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Buku;

class FavoriteController extends Controller
{
    public function index() {
        $favoriteBooks = auth()->user()->favorites;

        return view('buku.favorites', compact('favoriteBooks'));
    }
}
