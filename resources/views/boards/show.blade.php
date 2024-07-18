<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $board->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .accordion-content {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.5s ease-out, opacity 0.5s ease-out;
            display: none;
        }

        .accordion-content.show {
            display: block;
            max-height: 500px;
            opacity: 1;
        }
    </style>
</head>

<body class="bg-gray-100">
    <x-app-layout>
        <div class="container mx-auto my-10 p-5 bg-white rounded shadow-lg">
            <h1 class="mb-5 text-2xl font-bold text-gray-800">Board: {{ $board->title }}</h1>
            <a href="{{ route('boards.edit', $board->id) }}"
                class="text-blue-500 hover:underline mb-5 inline-block">Modifier le board</a>
            <div class="flex justify-center">
                <div class="w-full max-w-lg">
                    <div class="accordion">
                        <button
                            class="accordion-toggle p-4 w-full text-left bg-green-900 text-white rounded-t focus:outline-none focus:ring-2 focus:ring-green-300">
                            {{ __('Créer une liste') }}
                        </button>
                        <div class="accordion-content bg-yellow-100 rounded-b p-4">
                            <form method="POST" action="{{ route('boardLists.store') }}">
                                @csrf
                                <input type="hidden" value="{{ $board->id }}" name="board_id">
                                <input name="title" placeholder="{{ __('Titre de la liste') }}"
                                    class="mb-2 block w-full border-green-300 focus:green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 rounded shadow-sm">
                                <input name="description" placeholder="{{ __('Description de la liste') }}"
                                    class="mb-2 block w-full border-green-300 focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 rounded shadow-sm">
                                <x-primary-button
                                    class="mt-4 bg-green-300 text-white px-4 py-2 rounded hover:bg-green-500">{{ __('créer une liste') }}</x-primary-button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mx-auto my-10 p-5">
            <div class="flex flex-wrap -m-3">
                @foreach ($listByBoardId as $list)
                    <div class="{{ $list->id }} listMove w-full sm:w-1/3 p-3">
                        <div class="bg-white rounded shadow p-4">
                            <h3 class="text-lg font-bold mb-3 text-gray-800">{{ $list->title }}</h3>
                            <form action="{{ route('boardLists.destroy', $list->id) }}" method="post" class="mb-3">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" value='{{ $board->id }}' name="board_id">
                                <button type="submit" class="text-red-500 hover:underline">X</button>
                            </form>
                            <ul class="{{ $list->id }} cardListMove mb-4">
                                @foreach ($cardsByListId[$list->id] as $card)
                                    <li data-id="{{ $card->id }}"
                                        class="{{ $card->id }} bg-gray-200 rounded shadow p-4 mb-2 hover:bg-gray-400  >
                                        <h4 class="font-bold
                                        text-md text-black">{{ $card->title }}
                                        </h4>
                                        <p class="text-sm text-black">{{ $card->description }}</p>
                                        <form action="{{ route('cards.destroy', $card->id) }}" method="post"
                                            class="mb-3">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" value='{{ $board->id }}' name="board_id">
                                            <button type="submit" class="text-red-500 hover:underline">X</button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="accordion">
                                <button
                                    class="accordion-toggle p-4 w-full text-left bg-red-900 text-white rounded-t focus:outline-none focus:ring-2 focus:ring-blue-300">
                                    {{ __('Créer une carte') }}
                                </button>
                                <div class="accordion-content bg-yellow-100 rounded-b p-4">
                                    <form method="POST" action="{{ route('card.store') }}">
                                        @csrf
                                        <input type="hidden" value="{{ $list->id }}" name="list_id">
                                        <input type="hidden" value="{{ $board->id }}" name="board_id">
                                        <input name="title" placeholder="{{ __('Titre de la carte') }}"
                                            class="mb-2 block w-full border-red-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 rounded shadow-sm">
                                        <input name="description" placeholder="{{ __('Description de la carte') }}"
                                            class="mb-2 block w-full border-red-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 rounded shadow-sm">
                                        <x-primary-button
                                            class="mt-4 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">{{ __('créer une carte') }}</x-primary-button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-app-layout>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"
        integrity="sha512-TelkP3PCMJv+viMWynjKcvLsQzx6dJHvIGhfqzFtZKgAjKM1YPqcwzzDEoTc/BHjf43PcPzTQOjuTr4YdE8lNQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/move.js') }}"></script>
    <script>
        document.querySelectorAll('.accordion-toggle').forEach((button) => {
            button.addEventListener('click', function() {
                var content = button.nextElementSibling;
                if (content.classList.contains('show')) {
                    content.style.maxHeight = content.scrollHeight + 'px'; // Set to current height
                    requestAnimationFrame(() => {
                        content.style.maxHeight = '0';
                        content.style.opacity = '0';
                    });
                    setTimeout(() => {
                        content.classList.remove('show');
                        content.style.display = 'none';
                    }, 500);
                } else {
                    content.style.display = 'block';
                    requestAnimationFrame(() => {
                        content.classList.add('show');
                        content.style.maxHeight = content.scrollHeight + 'px';
                        content.style.opacity = '1';
                    });
                }
            });
        });
    </script>
</body>

</html>
