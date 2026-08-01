@extends('layouts.admin')

@section('title', 'Edit Role')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.roles.index') }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-200">
                        <i class="fa-solid fa-pen text-white text-sm"></i>
                    </span>
                    Edit Role: {{ $role->name }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">Update role name and permissions</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="p-6 space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Role Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition @error('name') border-red-500 @enderror"
                               required>
                        @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-h-80 overflow-y-auto p-3 bg-gray-50/50 rounded-lg border border-gray-200">
                            @forelse($permissions as $module => $perms)
                                <div class="space-y-1.5">
                                    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200 pb-1">{{ $module }}</p>
                                    @foreach($perms as $perm)
                                        <label class="flex items-center gap-2 text-sm text-gray-700 hover:text-gray-900 cursor-pointer">
                                            <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                                   class="w-3.5 h-3.5 text-amber-600 border-gray-300 rounded focus:ring-amber-500 focus:ring-2"
                                                   {{ in_array($perm->id, $rolePermissions ?? []) ? 'checked' : '' }}>
                                            <span>{{ $perm->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @empty
                                <p class="text-sm text-gray-400 col-span-3 text-center py-4">No permissions available</p>
                            @endforelse
                        </div>
                        @error('permissions') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.roles.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white text-sm font-medium rounded-lg shadow-lg shadow-amber-200 transition-all hover:shadow-xl">
                        <i class="fa-solid fa-check mr-1.5"></i> Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection