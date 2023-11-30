<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-center text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Daftar Buku') }}
            </h2>
        </x-slot>

        @if(Auth::check() && Auth::user()->level == 'admin')
        <button class="mx-6 mt-3 bg-blue-500 text-white rounded-md py-2 px-4 hover:opacity-70">
            <a href="{{ route('buku.create') }}">Tambah Buku</a>
        </button>
        @endif

        <form action="{{ route('buku.search') }}" method="get">
            @csrf
            <input type="text" name="kata" class="form-control sm:rounded-lg mr-6 mt-3" 
                placeholder="Cari ..." style="display: inline; margin-bottom: 10px; float: right;">
        </form>
        
        @if(Session::has('stored_message'))
            <div class="alert alert-success text-green-500 mx-6">{{Session::get('stored_message')}}</div>
        @endif

        @if(Session::has('edited_message'))
            <div class="alert alert-success text-green-500 mx-6">{{Session::get('edited_message')}}</div>
        @endif

        @if(Session::has('deleted_message'))
            <div class="alert alert-success text-green-500 mx-6">{{Session::get('deleted_message')}}</div>
        @endif
        
        <div class="px-6">
            <table class="text-left w-full border-collapse text-gray-200">
                <thead class="border-b border-gray-700 ">
                    <tr>
                        <th class="py-3 px-5 font-medium uppercase text-sm text-gray-800">id</th>
                        <th class="py-3 px-5 font-medium uppercase text-sm text-gray-800">Thumbnail</th>
                        <th class="py-3 px-5 font-medium uppercase text-sm text-gray-800">Judul Buku</th>
                        <th class="py-3 px-5 font-medium uppercase text-sm text-gray-800">Rating</th>
                        <th class="py-3 px-5 font-medium uppercase text-sm text-gray-800">Penulis</th>
                        <th class="py-3 px-5 font-medium uppercase text-sm text-gray-800">Harga</th>
                        <th class="py-3 px-5 font-medium uppercase text-sm text-gray-800">Tanggal Terbit</th>
                        @if(Auth::check() && Auth::user()->level == 'admin')
                        <th class="py-3 px-5 font-medium uppercase text-sm text-gray-800" colspan="2" align="center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($data_buku as $buku)
                    <tr class="hover:bg-rose-300">
                        <td class="py-4 px-6 border-b border-gray-700 text-gray-700 text-sm">{{ $buku->id }}</td>
                        @if($buku->filepath)
                            <td class="py-4 px-6 border-b border-gray-700 text-gray-700 text-sm">
                                <img src="{{ asset ($buku->filepath) }}" width="100">
                            </td>
                        @elseif($buku->filepath == null)
                            <td class="py-4 px-6 border-b border-gray-700 text-red-700 text-sm">Image not found</td>
                        @endif
                        <td class="py-4 px-6 border-b border-gray-700 text-gray-700 text-sm"><a href="{{ route('buku.detailbuku', $buku->id)}}">{{ $buku->judul }}</a></td>
                        <td class="py-4 px-6 border-b border-gray-700 text-red-700 text-sm">Rating is not available</td>
                        <td class="py-4 px-6 border-b border-gray-700 text-gray-700 text-sm">{{ $buku->penulis }}</td>
                        <td class="py-4 px-6 border-b border-gray-700 text-gray-700 text-sm">{{ "Rp ".number_format($buku->harga, 2, ',', '.') }}</td>
                        <td class="py-4 px-6 border-b border-gray-700 text-gray-700 text-sm">{{ \Carbon\Carbon::parse($buku->tgl_terbit)->format('d/m/Y') }}</td>
                        @if(Auth::check() && Auth::user()->level == 'admin')
                        <td class="py-4 px-6 border-b border-gray-700 text-gray-700 text-sm">
                            <form action="{{ route('buku.destroy', $buku->id) }}" method="post">
                                @csrf
                                <button class="bg-red-500 text-white rounded-md py-2 px-4 hover:opacity-70" onclick="return confirm('yakin mau dihapus?')">Hapus</button>
                            </form>
                        </td>
                        <td class="py-4 px-6 border-b border-gray-700 text-gray-400 text-sm">
                            <button class="bg-blue-500 text-white rounded-md py-2 px-4 hover:opacity-70"><a href="{{ route('buku.edit', $buku->id) }}">Edit</a></button> 
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="py-3 px-5 font-medium uppercase text-sm text-gray-900" colspan="3">TOTAL HARGA</td>
                        <td class="py-3 px-5 font-medium uppercase text-sm text-gray-900" colspan="4">{{ "Rp ".number_format($total_harga, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-5 font-medium uppercase text-sm text-gray-900" colspan="3">JUMLAH BUKU</td>
                        <td class="py-3 px-5 font-medium uppercase text-sm text-gray-900" colspan="4">{{ $jumlah_data }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="pagination justify-conten-left mt-1 py-3 px-5">
            {{ $data_buku->links() }}
        </div>
    </x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
