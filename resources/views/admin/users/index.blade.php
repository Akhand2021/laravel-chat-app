<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 leading-tight">User Management</h2>
    </x-slot>
    <div class="py-12">
        <x-success-message />
        <x-error-message :errors="$errors" />
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-end">
                    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Add User</a>
                </div>
                <table class="w-full mt-4 border-collapse border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left text-sm font-semibold text-gray-900">Name</th>
                            <th class="p-3 text-left text-sm font-semibold text-gray-900">Email</th>
                            <th class="p-3 text-left text-sm font-semibold text-gray-900">Role</th>
                            <th class="p-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($users as $user)
                        <tr class="border-b border-gray-200">
                            <td class="p-3 text-sm text-gray-700">{{ $user->name }}</td>
                            <td class="p-3 text-sm text-gray-700">{{ $user->email }}</td>
                            <td class="p-3 text-sm text-gray-700">{{ $user->roles->pluck('name')->join(', ') }}</td>
                            <td class="p-3 text-sm">
                                <a href="{{ route('admin.users.edit', $user) }}" class="font-medium text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Are you sure you want to delete this user?')" class="font-medium text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>