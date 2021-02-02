@props(['user'])

<div>
    @if($user->games()->count())

        <div class="px-4 text-right max-w">
            <x-add-new-game-button/>
        </div>
        <div class="grid grid-cols-3 gap-4 justify-items-stretch">
            @foreach($user->games as $game)
                <div class=" flex max-w-md bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="w-1/3 max-h-40 bg-cover" style="background-image: url('{{ $game->image_url }}')">
                    </div>
                    <div class="w-2/3 p-4">
                        <h1 class="text-gray-900 font-bold text-2xl">{{ $game->name }}</h1>
                        <p class="mt-2 text-gray-600  text-sm">{{ Str::limit($game->description, 150, '...') }}</p>
                        <div class="flex item-center mt-2">
                            @for ($i = 0; $i < 5; $i++)
                                @if ($i < $game->rating)
                                    <svg class="w-5 h-5 fill-current text-gray-700" viewBox="0 0 24 24">
                                        <path
                                            d="M12 17.27L18.18 21L16.54 13.97L22 9.24L14.81 8.63L12 2L9.19 8.63L2 9.24L7.46 13.97L5.82 21L12 17.27Z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 fill-current text-gray-500" viewBox="0 0 24 24">
                                        <path
                                            d="M12 17.27L18.18 21L16.54 13.97L22 9.24L14.81 8.63L12 2L9.19 8.63L2 9.24L7.46 13.97L5.82 21L12 17.27Z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        @if($game->metacritic_rating)
                            <div class="mx-1">
                                Metacritic rating: {{ $game->metacritic_rating }} / 100
                            </div>
                        @endif
                        <div class="mt-3">
                            @if(isset($game->metadata['platforms']))
                                @foreach($game->metadata['platforms'] as $platform)
                                    <div
                                        class="inline-block px-3 py-2 my-1 bg-gray-800 text-white text-xs font-bold uppercase rounded">
                                        {{ $platform['name'] }}</div>
                                @endforeach
                            @endif

                        </div>
                        <div class="flex item-center justify-between mt-3">
                            <button class="px-3 py-2 bg-purple-800 text-white text-xs font-bold uppercase rounded">
                                Play game
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


    @else
        <div class="text-center">
            <h1 class="text-lg font-bold text-center">There are no games linked to your account</h1>

            <x-add-new-game-button/>

        </div>
    @endif

</div>
