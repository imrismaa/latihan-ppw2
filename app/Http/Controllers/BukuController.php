<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BukuModel;
use App\Models\GaleriModel;
use App\Models\RatingModel;
use App\Models\FavoriteModel;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class BukuController extends Controller
{
    public function index(){
        $batas = 5;
        $data_buku = BukuModel::simplePaginate($batas);
        $jumlah_data = $data_buku->count('id');
        $total_harga = $data_buku->sum('harga');
        $no = $batas * ($data_buku->currentPage() - 1);

        $jumlahRating1 = BukuModel::sum('rating_1');
        $jumlahRating2 = BukuModel::sum('rating_2');
        $jumlahRating3 = BukuModel::sum('rating_3');
        $jumlahRating4 = BukuModel::sum('rating_4');
        $jumlahRating5 = BukuModel::sum('rating_5');
    
        $jumlahRating = $jumlahRating1 + $jumlahRating2 + $jumlahRating3 + $jumlahRating4 + $jumlahRating5;
    
        if ($jumlahRating > 0) {
            $avgRating = ($jumlahRating1 * 1 + $jumlahRating2 * 2 + $jumlahRating3 * 3 + $jumlahRating4 * 4 + $jumlahRating5 * 5) / $jumlahRating;
        } else {
            $avgRating = 0;
        }

        return view('buku.index', compact('data_buku', 'jumlah_data', 'total_harga', 'no', 'avgRating'));
    }

    public function create() {
        return view('buku.create');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'judul' => 'required|string',
            'penulis' => 'required|string',
            'harga' => 'required|numeric',
            'tgl_terbit' => 'required|date',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,svg|max:2048'
        ]);

        $fileName = null;

        if ($request->hasFile('thumbnail')) {
            $fileName = time() . '_' . $request->file('thumbnail')->getClientOriginalName();
            $filePath = $request->file('thumbnail')->storeAs('uploads', $fileName, 'public');
        }

        if ($fileName !== null) {
            Image::make(storage_path().'/app/public/uploads/'.$fileName)
                ->fit(240, 320)
                ->save();
        }

        $filePath = $fileName !== null ? '/storage/' . $filePath : null;

        BukuModel::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'harga' => $request->harga,
            'tgl_terbit' => $request->tgl_terbit,
            'filename' => $fileName,
            'filepath' => $filePath,
        ]);

        if ($request->file('gallery')) {
            foreach ($request->file('gallery') as $key => $file) {
                $fileName = time().'_'.$file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');

                $gallery = GaleriModel::create([
                    'nama_galeri' => $fileName,
                    'path' => '/storage/' . $filePath,
                    'foto' => $fileName,
                    'buku_id' => $id,
                ]);
            }
        }

        return redirect('/buku')->with('stored_message', 'Data buku berhasil ditambahkan');
    }

    public function edit($id) {
        $buku = BukuModel::find($id);
        return view('buku.edit', compact('buku'));
    }

    public function update(Request $request, $id) {
        $buku = BukuModel::find($id);

        $request->validate([
            'thumbnail' => 'image|mimes:jpeg,png,jpg,svg|max:2048'
        ]);

        $fileName = null;

        if ($request->hasFile('thumbnail')) {
            $fileName = time() . '_' . $request->file('thumbnail')->getClientOriginalName();
            $filePath = $request->file('thumbnail')->storeAs('uploads', $fileName, 'public');
        }

        if ($fileName !== null) {
            Image::make(storage_path().'/app/public/uploads/'.$fileName)
                ->fit(240, 320)
                ->save();
        }

        $filePath = $fileName !== null ? '/storage/' . $filePath : null;

        $buku->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'harga' => $request->harga,
            'tgl_terbit' => $request->tgl_terbit,
            'filename' => $fileName,
            'filepath' => $filePath,
        ]);

        if ($request->file('gallery')) {
            foreach ($request->file('gallery') as $key => $file) {
                $fileName = time().'_'.$file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');

                $gallery = GaleriModel::create([
                    'nama_galeri' => $fileName,
                    'path' => '/storage/' . $filePath,
                    'foto' => $fileName,
                    'buku_id' => $id,
                ]);
            }
        }

        return redirect('/buku')->with('edited_message', 'Data buku berhasil diubah');
    }

    public function destroy($id) {
        $buku = BukuModel::find($id);
        $buku->delete();
        return redirect('/buku')->with('deleted_message', 'Data buku berhasil dihapus');
    }

    public function destroyGallery($id) { 
        $gallery = GaleriModel::findOrFail($id);
        $gallery->delete();

        return redirect()->back();
    }

    public function search(Request $request) {
        $batas = 5;
        $cari = $request->kata;
        $data_buku = BukuModel::where('judul', 'like', "%".$cari."%")->orwhere('penulis', 'like', "%".$cari."%")->paginate($batas);
        $jumlah_data = $data_buku->count('id');
        $total_harga = $data_buku->sum('harga');
        $no = $batas * ($data_buku->currentPage() - 1);

        return view('buku.search', compact('data_buku', 'jumlah_data', 'cari', 'no', 'total_harga'));
    }

    public function _construct() {
        $this->middleware('auth');
    }

    public function galbuku($id) {
        $buku = BukuModel::find($id);
        $galeris = $buku->galeri()->orderBy('id', 'desc')->paginate(5);

        return view('buku.detailbuku', compact('buku', 'galeris'));
    }

    public function listbuku(){
        $batas = 5;
        $data_buku = BukuModel::simplePaginate($batas);
        $jumlah_data = $data_buku->count('id');
        $total_harga = $data_buku->sum('harga');
        $no = $batas * ($data_buku->currentPage() - 1);

        return view('buku.listbuku', compact('data_buku', 'jumlah_data', 'total_harga', 'no'));
    }

    
    public function rating (Request $request, $id) {
        $buku = BukuModel::find($id);
    
        $existingRating = RatingModel::where('user_id', auth()->user()->id)
            ->where('buku_id', $buku->id)
            ->first();
    
        if ($existingRating) {
            return redirect()->back()->with('error', 'Anda sudah memberikan rating untuk buku ini.');
        }
    
        $rating = new RatingModel([
            'user_id' => auth()->user()->id,
            'rating' => $request->rating,
        ]);
    
        $buku->rating()->save($rating);
    
        return redirect()->back()->with('success', 'Rating berhasil disimpan.');
    }

    public function favorite (Request $request, $id)
    {
        $buku = BukuModel::find($id);
    
        $existingFavorites = FavoriteModel::where('user_id', auth()->user()->id)
            ->where('buku_id', $buku->id)
            ->first();

        if ($existingFavorites) {
            return redirect()->back()->with('error', 'Buku sudah ada di favorit Anda.');
        }

        $buku->favoritedBy()->attach(auth()->user()->id);
        return redirect("/buku/myfavorite")->with('success', 'Buku ditambahkan ke favorit.');
    }

    public function storeRating(Request $request, $id)
    {
        $this->validate($request, [
            'rating' => 'required|numeric|between:1,5',
        ]);

        $buku = BukuModel::findOrFail($id);

        // Update rating pada buku
        $ratingField = 'rating_' . $request->rating;
        $buku->$ratingField += 1;
        $buku->save();

        return redirect()->route('galeri.buku', ['buku_seo' => $buku->buku_seo])
            ->with('success', 'Rating berhasil disimpan.');
    }


    //Menambahkan buku ke daftar favorit
    public function addToFavourite($id)
{
    $buku = BukuModel::findOrFail($id);

    // Cek apakah buku sudah ada di daftar favorit pengguna
    if (auth()->user()->favouriteBooks->contains($buku)) {
        return redirect()->back()->with('pesan', 'Buku sudah ada di daftar favorit Anda.');
    }

    // Tambahkan buku ke daftar favorit
    auth()->user()->favouriteBooks()->attach($buku);

    return redirect()->back()->with('pesan', 'Buku berhasil ditambahkan ke daftar favorit Anda.');
}

//Menampilkan buku favorit
public function myFavouriteBooks()
{
    $favouriteBooks = auth()->user()->favouriteBooks;

    return view('myfavouritebooks', compact('favouriteBooks'));
}

}
