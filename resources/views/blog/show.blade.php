<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Post Header -->
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
                        
                        <div class="flex items-center text-sm text-gray-500 space-x-4">
                            @if($post->author)
                                <span>By {{ $post->author }}</span>
                            @endif
                            @if($post->category)
                                <span>Category: {{ $post->category->name }}</span>
                            @endif
                            @if($post->published_at)
                                <span>Published: {{ $post->published_at->format('F j, Y') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Post Image -->
                    @if($post->image)
                        <div class="mb-8">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-96 object-cover rounded-lg">
                        </div>
                    @endif

                    <!-- Post Content -->
                    <div class="prose max-w-none">
                        {!! $post->body !!}
                    </div>

                    <!-- Post Footer -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                @if($post->tags)
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-500">{{ str_replace(',', ' ', $post->tags) }}</span>
                                    </div>
                                @endif
                                @if($post->source)
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                        </svg>
                                        <a href="{{ $post->source }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800">Source</a>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center space-x-4">
                                <a href="{{ route('posts.edit', $post) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">Edit</a>
                                <a href="{{ route('posts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">Back to Posts</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 