@props(['message'])

@if (session('success'))
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
</div>
@endif 