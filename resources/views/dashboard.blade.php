<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
        @php
        $cards = [
        [
        'title' => 'Users',
        'description' => 'All users (' . \App\Models\User::count() . ')',
        'link' => route('admin.users.index'),
        'icon' => 'users'
        ],
        [
        'title' => 'Posts', 
        'description' => 'All posts (' . \App\Models\Post::count() . ')',
        'link' => route('posts.index'),
        'icon' => 'document-text'
        ],
        [
        'title' => 'Categories', 
        'description' => 'All categories (' . \App\Models\Category::count() . ')',
        'link' => route('categories.index'),
        'icon' => 'folder'
        ],
        ];
        @endphp
        <div class="mt-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($cards as $card)
                <div class="transform transition duration-500 hover:scale-105">
                    <a href="{{ $card['link'] }}" class="block h-full p-8 bg-white border-2 border-gray-100 rounded-xl shadow-lg hover:shadow-2xl hover:border-blue-500 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <div class="inline-flex p-3 text-blue-500 bg-blue-100 rounded-lg dark:text-blue-400 dark:bg-gray-700">
                                @if($card['icon'] === 'users')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                @elseif($card['icon'] === 'folder')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                @endif
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        <h5 class="mb-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $card['title'] }}</h5>
                        <p class="font-normal text-gray-600 dark:text-gray-400">{{ $card['description'] }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>