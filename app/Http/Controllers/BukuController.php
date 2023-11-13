<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BukuModel;
use App\Models\GaleriModel;
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
            'tgl_terbit' => 'required|date'
        ]);
        BukuModel::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'harga' => $request->harga,
            'tgl_terbit' => $request->tgl_terbit
        ]);
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

        $fileName = time().'_'.$request->thumbnail->getClientOriginalName();
        $filePath = $request->file('thumbnail')->storeAs('uploads', $fileName, 'public');

        Image::make(storage_path().'/app/public/uploads/'.$fileName)
            ->fit(240, 320)
            ->save();

        $buku->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'harga' => $request->harga,
            'tgl_terbit' => $request->tgl_terbit,
            'filename' => $fileName,
            'filepath' => '/storage/' . $filePath,
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

}
