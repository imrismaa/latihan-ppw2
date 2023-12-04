<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BukuModel;

use App\Models\FavoriteModel;

class FavoriteController extends Controller
{
    public function index() {
        $favoriteBooks = FavoriteModel::where('user_id', auth()->user()->id)
        ->with(['buku' => function ($query) {
            $query->select('id', 'judul', 'filepath');
        }])
        ->get();

        return view('buku.favorites', compact('favoriteBooks'));
    }
}
