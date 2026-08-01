@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.index') }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-200">
                            <i class="fa-solid fa-user text-white text-sm"></i>
                        </span>
                        {{ $user->name }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block">
                    @csrf @method('DELETE')
                    <button class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition" onclick="return confirm('Delete this user?')">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- User Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                    <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-circle-info text-blue-500 mr-2"></i> User Information</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">ID</span>
                        <span class="text-sm font-medium text-gray-800">#{{ $user->id }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">Name</span>
                        <span class="text-sm font-medium text-gray-800">{{ $user->name }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">Email</span>
                        <span class="text-sm font-medium text-gray-800">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">Role</span>
                        <span class="text-sm font-medium text-gray-800">{{ $user->roles->first()->name ?? 'No Role' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">Organization</span>
                        <span class="text-sm font-medium text-gray-800">{{ $user->organization->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Joined</span>
                        <span class="text-sm font-medium text-gray-800">{{ $user->created_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Permissions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                    <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-shield-halved text-purple-500 mr-2"></i> Permissions</h3>
                </div>
                <div class="p-6">
                    @if($user->roles->first())
                        @php $role = $user->roles->first(); @endphp
                        <div class="mb-4">
                            <p class="text-sm text-gray-600">Role: <span class="font-medium text-gray-800">{{ ucfirst($role->name) }}</span></p>
                            <p class="text-xs text-gray-400 mt-1">{{ $role->permissions->count() }} permissions assigned</p>
                        </div>
                        @if($role->permissions->count())
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($role->permissions as $perm)
                                    <span class="inline-block px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-md">{{ $perm->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400 text-center py-2">No permissions assigned</p>
                        @endif
                    @else
                        <p class="text-sm text-gray-400 text-center py-2">No role assigned</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection