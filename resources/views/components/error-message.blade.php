@props(['errors'])

@if ($errors->any())
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif 