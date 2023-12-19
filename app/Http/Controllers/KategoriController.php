<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriModel;

class KategoriController extends Controller
{
    public function attachBuku($kategoriId, $bukuId)
    {
        $kategori = KategoriModel::find($kategoriId);
        $kategori->buku()->attach($bukuId);

        return redirect()->back()->with('success', 'Buku ditambahkan ke kategori.');
    }

    public function detachBuku($kategoriId, $bukuId)
    {
        $kategori = KategoriModel::find($kategoriId);
        $kategori->buku()->detach($bukuId);

        return redirect()->back()->with('success', 'Buku dihapus dari kategori.');
    }
}
