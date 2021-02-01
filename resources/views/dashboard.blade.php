<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="alert-box mb-4">
                                <div class="block text-sm text-left text-green-600 bg-green-200 border border-green-400 h-12 flex items-center p-4 rounded-lg" role="alert">
                                    {{ session('success') }}
                                </div>
                        </div>
                    @endif
                    <x-user-games :user="Auth::user()"></x-user-games>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
