<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Tableau</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <x-app-layout>
        <div class="container mx-auto my-10 p-5 bg-white rounded shadow-lg">
            <h1 class="text-2xl font-bold mb-5 text-center">Créer un nouveau tableau</h1>
            <form method="POST" action="{{ route('boards.store') }}">
                @csrf
                <div class="mb-4">
                    <input name="title" placeholder="{{ __('Titre du tableau') }}"
                        class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                </div>
                <div class="mb-4">
                    <input name="description" placeholder="{{ __('Description') }}"
                        class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                </div>
                <div class="text-center">
                    <x-primary-button
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">{{ __('Créer un tableau') }}</x-primary-button>
                </div>
            </form>
        </div>

        <div class="container mx-auto my-10 p-5">
            <h2 class="text-xl font-bold mb-5 text-center">Mes Tableaux</h2>
            <div class="flex flex-wrap justify-center">
                @foreach ($userboards as $board)
                    <div class="w-full sm:w-1/3 p-4">
                        <div class="bg-white rounded-lg shadow p-5">
                            <a href="#">
                                <h5 class="mb-2 text-xl font-bold text-gray-900">{{ $board->title }}</h5>
                            </a>
                            <p class="mb-3 text-gray-700">{{ $board->description }}</p>
                            <div class="flex justify-between items-center">
                                <a href="{{ route('boards.show', $board) }}"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                    Aller sur le tableau
                                    <svg class="w-4 h-4 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                    </svg>
                                </a>
                                <form action="{{ route('boards.destroy', $board->id) }}" method="post" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-app-layout>
</body>

</html>
