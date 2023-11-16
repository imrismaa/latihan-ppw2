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
        <div class="container p-4 shadow-xl dark:bg-rose-300 rounded-lg mt-4 mx-auto max-w-xl mb-6">
            @if (count($errors) > 0)
                <ul class="alert alert-danger text-red-500">
                    @foreach ($errors->all() as $error)
                        <li> {{ $error }} </li>
                    @endforeach
                </ul>
            @endif
            <form action="{{route('buku.update',$buku->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
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
                <div class="flex flex-row space-x-4">
                    @foreach($buku->galeri()->get() as $gallery)
                        <div class="relative group">
                            <a href="{{ asset($gallery->path) }}" data-lightbox="image-1">
                            <img class="rounded-sm object-cover object-center" src="{{ asset($gallery->path) }}" alt="" width="200"/></a>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
    </x-app-layout>
    <script src="{{ asset('dist/js/lightbox-plus-jquery.min.js') }}"></script>
</body>
</html>