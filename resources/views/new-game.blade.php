<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add new game to library') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="rounded-t-xl overflow-hidden bg-gradient-to-r from-purple-50 to-purple-100 p-10">
                        @if ($errors->any())
                            <div class="alert-box mb-4">
                                    @foreach ($errors->all() as $error)
                                        <div class="block text-sm text-left text-red-600 bg-red-200 border border-red-400 h-12 flex items-center p-4 rounded-lg" role="alert">
                                            {{ $error }}
                                        </div>
                                    @endforeach
                            </div>
                        @endif
                        <form class="flex w-full max-w-sm mx-auto space-x-3" action="{{ route('submit-game') }}" method="POST">
                            @csrf
                            <input name="name" class="flex-1 appearance-none border border-transparent w-full py-2 px-4 bg-white text-gray-700 placeholder-gray-400 shadow-md rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" type="text" placeholder="Game name (e.g. Grand Theft Auto V)">
                            <button type="submit" class="flex-shrink-0 bg-purple-600 text-white text-base font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-purple-200" type="button">
                                Add game
                            </button>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
