<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="{{ asset('dist/css/lightbox.min.css') }}" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-app-layout>
        @if(Session::has('success'))
            <div class="alert alert-success text-green-500 mx-6">{{Session::get('success')}}</div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-warning text-red-500 mx-6 mt-2 mb-2">{{Session::get('error')}}</div>
        @endif

        <div class="container p-4 shadow-xl dark:bg-rose-300 rounded-lg mt-4 mx-auto max-w-xl mb-6">
            @if (count($errors) > 0)
                <ul class="alert alert-danger text-red-500">
                    @foreach ($errors->all() as $error)
                        <li> {{ $error }} </li>
                    @endforeach
                </ul>
            @endif
                <div class="justify-center flex flex-row space-x-4">
                    @if($buku->filepath)
                        <div class="relative group">
                            <a href="{{ asset($buku->filepath) }}" data-lightbox="image-1">
                            <img class="rounded-sm object-cover object-center" src="{{ asset($buku->filepath) }}" alt="" width="200"/></a>
                        </div>
                    @endif 
                </div>
                <div class="mb-4">
                    <label for="judul" class="text-gray-900">Judul</label>
                    <div class="w-full mt-1 px-2 py-2 rounded-lg border"><p>{{$buku->judul}}</p></div>
                </div>
                <div class="mb-4">
                    <label for="penulis" class="text-gray-900">Penulis</label>
                    <div class="w-full mt-1 px-2 py-2 rounded-lg border"><p>{{$buku->penulis}}</p></div>
                </div>
                <div class="mb-4">
                    <label for="harga" class="text-gray-900">Harga</label>
                    <div class="w-full mt-1 px-2 py-2 rounded-lg border"><p>{{$buku->harga}}</p></div>
                </div>
                <div class="mb-4">
                    <label for="tanggal_terbit" class="text-gray-900">Tanggal Terbit</label>
                    <div class="w-full mt-1 px-2 py-2 rounded-lg border"><p>{{$buku->tgl_terbit}}</p></div>
                </div>
                <div class="flex flex-row space-x-4 mb-4">
                    @foreach($buku->galeri()->get() as $gallery)
                        <div class="relative group">
                            <a href="{{ asset($gallery->path) }}" data-lightbox="image-1">
                            <img class="rounded-sm object-cover object-center" src="{{ asset($gallery->path) }}" alt="" width="200"/></a>
                        </div>
                    @endforeach
                </div>
                <div class="mb-4">
                    <label for="rating" class="text-gray-900">Rating</label>
                    <div class="w-full mt-1 px-2 py-2 rounded-lg border">
                        @if ($buku->ratings->count() > 0)
                            <p id="rating">{{ $buku->averageRating() }}</p>
                        @else
                            Rating is not available.
                        @endif
                    </div>
                </div>
                <div class="mb-4">
                    <label for="rate" class="text-gray-900">Rate</label>
                    <form action="{{ route('buku.rate', $buku->id) }}"" method="POST">
                        @csrf
                        <select name="rating" id="rating" class="block w-full mt-1 px-2 py-2 rounded-lg border">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                        <div class="flex justify-center mt-4">
                            <button class="bg-blue-500 text-white rounded-md py-2 px-4 mx-3" type="submit">Submit rating</button>
                        </div>
                    </form>
                </div>
                <div class="mb-4">
                    <label for="review" class="text-gray-900">Review</label>
                    <form action="{{ route('buku.rate', $buku->id) }}"" method="POST">
                        @csrf
                        <textarea class="w-full mt-1 px-2 py-2 rounded-lg border" name="review" id="review"></textarea>
                        <div class="flex justify-center mt-4">
                            <button class="bg-blue-500 text-white rounded-md py-2 px-4 mx-3" type="submit">Submit review</button>
                        </div>
                    </form>
                </div>
                <div class="flex justify-center mt-4">
                    <form method="POST" action="{{ route('buku.favorite', $buku->id) }}">
                        @csrf
                        <button class="bg-blue-500 text-white rounded-md py-2 px-4 mx-3" type="submit">Simpan ke daftar favorite</button>
                    </form>
                </div>
            </form>
        </div>
    </x-app-layout>
    <script src="{{ asset('dist/js/lightbox-plus-jquery.min.js') }}"></script>
</body>
</html>