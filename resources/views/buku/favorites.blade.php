<body>
    <x-app-layout>

        <x-slot name="header">
            <h2 class="font-semibold text-center text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Buku Favorite') }}
            </h2>
        </x-slot>

        <form action="{{ route('buku.search') }}" method="get">
            @csrf
            <input type="text" name="kata" class="form-control sm:rounded-lg mr-6 mt-3" 
                placeholder="Cari ..." style="display: inline; margin-bottom: 10px; float: right;">
        </form>
        
        <div class="px-6">
            <table class="text-left w-full border-collapse text-gray-200">
                <thead class="border-b border-gray-700 ">
                    <tr>
                        <th class="py-3 px-5 font-medium uppercase text-sm text-gray-800">Thumbnail</th>
                        <th class="py-3 px-5 font-medium uppercase text-sm text-gray-800">Judul Buku</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($favoriteBooks as $favorite)
                        <tr class="hover:bg-rose-300">
                            @if($favorite->buku->filepath)
                                <td class="py-4 px-6 border-b border-gray-700 text-gray-700 text-sm">
                                    <img src="{{ asset($favorite->buku->filepath) }}" width="100">
                                </td>
                            @else
                                <td class="py-4 px-6 border-b border-gray-700 text-gray-400 text-sm">Image not found</td>
                            @endif
                            <td class="py-4 px-6 border-b border-gray-700 text-gray-700 text-sm">
                                <a href="{{ route('buku.detailbuku', $favorite->buku->id) }}">{{ $favorite->buku->judul }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
