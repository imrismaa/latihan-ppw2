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
                {{ __('Edit Buku') }}
            </h2>
        </x-slot>
        <div class="container p-4 shadow-xl dark:bg-rose-300 rounded-lg mt-4 mx-auto max-w-md mb-6">
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
                    <input class="w-full mt-1 px-2 py-2 rounded-lg border" type="text" name="judul" id="judul" value="{{$buku->judul}}">
                </div>
                <div class="mb-4">
                    <label for="penulis" class="text-gray-900">Penulis</label>
                    <input class="w-full mt-1 px-2 py-2 rounded-lg border" type="text" name="penulis" id="penulis" value="{{$buku->penulis}}">
                </div>
                <div class="mb-4">
                    <label for="harga" class="text-gray-900">Harga</label>
                    <input class="w-full mt-1 px-2 py-2 rounded-lg border" type="text" name="harga" id="harga" value="{{$buku->harga}}">
                </div>
                <div class="mb-4">
                    <label for="tanggal_terbit" class="text-gray-900">Tanggal Terbit</label>
                    <input class="w-full mt-1 px-2 py-2 rounded-lg border" type="date" name="tgl_terbit" id="tgl_terbit" value="{{$buku->tgl_terbit}}">
                </div>
                <div class="mb-4">
                    <label for="file_upload" class="text-gray-900">Thumbnail</label>
                    <input class="w-full mt-1 px-2 py-2 rounded-lg border" type="file" name="thumbnail" id="thumbnail" value="{{$buku->filename}}">
                </div>
                <div class="flex justify-center mt-4">
                    <button class="bg-blue-500 text-white rounded-md py-2 px-4 mx-3" type="submit">Simpan</button>
                    <button class="bg-red-500 text-white rounded-md py-2 px-4 mx-3"><a href="/buku"> Batal</a></button>
                </div>
            </form>
        </div>
    </x-app-layout>
</body>
</html>