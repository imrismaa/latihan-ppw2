<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BukuModel;
use App\Models\GaleriModel;
use App\Models\RatingModel;
use App\Models\FavoriteModel;
use App\Models\ReviewModel;
use Intervention\Image\Facades\Image;

class BukuController extends Controller
{
    public function index(){
        $batas = 5;
        $data_buku = BukuModel::simplePaginate($batas);
        $jumlah_data = $data_buku->count('id');
        $total_harga = $data_buku->sum('harga');
        $no = $batas * ($data_buku->currentPage() - 1);

        return view('buku.index', compact('data_buku', 'jumlah_data', 'total_harga', 'no'));
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

        $reviews = $buku->reviews()->where('moderated', true)->get();

        return view('buku.detailbuku', compact('buku', 'galeris', 'reviews'));
    }

    public function listbuku(){
        $batas = 5;
        $data_buku = BukuModel::simplePaginate($batas);
        $jumlah_data = $data_buku->count('id');
        $total_harga = $data_buku->sum('harga');
        $no = $batas * ($data_buku->currentPage() - 1);

        return view('buku.listbuku', compact('data_buku', 'jumlah_data', 'total_harga', 'no'));
    }

    public function rate(Request $request, $id) {
        $buku = BukuModel::find($id);

        $existingRating = RatingModel::where('user_id', auth()->user()->id)
            ->where('buku_id', $buku->id)
            ->first();

        if ($existingRating) {
            return redirect()->back()->with('error', 'Anda sudah memberikan rating untuk buku ini.');
        }

        RatingModel::create([
            'buku_id' => $buku->id,
            'user_id' => auth()->user()->id,
            'rating' => $request->rating,
        ]);

        return redirect()->back()->with('success', 'Rating berhasil disimpan.');
    }

    public function addToFavorites(Request $request, $id)
    {
        $buku = BukuModel::find($id);

        $existingFavorites = FavoriteModel::where('user_id', auth()->user()->id)
            ->where('buku_id', $buku->id)
            ->first();

        if ($existingFavorites) {
            return redirect()->back()->with('error', 'Buku sudah ada di favorit Anda.');
        }

        $buku->favoritedBy()->attach(auth()->user()->id);
        return redirect()->back()->with('success', 'Buku ditambahkan ke favorit.');
    }

    public function populer() {
        $data_buku = BukuModel::withCount('ratings')->orderByDesc('ratings_count')->take(10)->get();
        $jumlah_data = $data_buku->count('id');
        $total_harga = $data_buku->sum('harga');
        
        return view('buku.populer', compact('data_buku', 'jumlah_data', 'total_harga'));
    }
    

    public function show($id)
    {
        $buku = BukuModel::find($id);
        $reviews = ReviewModel::where('buku_id', $id)->where('moderated', true)->get();

        return view('buku.show', compact('buku', 'reviews'));
    }

    public function addReview(Request $request, $bukuId)
    {
        $this->validate($request, [
            'review' => 'required|string',
        ]);

        $content = $request->input('review');
        $moderated = $this->isProfanity($content) ? false : true;

        ReviewModel::create([
            'user_id' => auth()->user()->id,
            'buku_id' => $bukuId,
            'content' => $content,
            'moderated' => $moderated,
        ]);

        if ($moderated) {
            return redirect()->back()->with('success', 'Review berhasil ditambahkan');
        } else {
            return redirect()->back()->with('error', 'Review mengandung kata-kata tidak sopan!!!');
        }
    }

    private function isProfanity($text) {
        $profanityList = ['jelek', 'shibal', 'bjir'];
        $lowercaseText = strtolower($text);

        foreach ($profanityList as $profanity) {
            if (strpos($lowercaseText, $profanity) !== false) {
                return true;
            }
        }

        return false;
    }
}
