@extends('layouts.admin')

@section('title', 'Team Members')
@section('page-title', 'Team Members')
@section('page-subtitle', 'Manage admin users, roles, and access')

@section('content')
    <div class="mb-4 flex justify-end sm:mb-6">
        @permission('users.manage')
            <a href="{{ route('admin.users.create') }}" class="btn-primary w-full sm:w-auto">
                <i class="fa-solid fa-user-plus mr-1.5"></i> Add Team Member
            </a>
        @endpermission
    </div>

    <div class="mb-4 space-y-3 lg:hidden">
        @foreach($users as $user)
            <article class="admin-mobile-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                        <p class="truncate text-sm text-slate-500">{{ $user->email }}</p>
                    </div>
                    <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="mt-3 flex items-center justify-between gap-3">
                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">{{ $user->roleLabel() }}</span>
                    @permission('users.manage')
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">Edit</a>
                    @endpermission
                </div>
            </article>
        @endforeach
        @if($users->hasPages())
            <div class="pt-2">{{ $users->links() }}</div>
        @endif
    </div>

    <div class="admin-table-wrap hidden lg:block">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Name</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Email</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Role</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Status</th>
                        <th class="px-5 py-3 text-right font-semibold text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3 font-medium text-slate-900">{{ $user->name }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $user->email }}</td>
                            <td class="px-5 py-3 text-slate-700">{{ $user->roleLabel() }}</td>
                            <td class="px-5 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                @permission('users.manage')
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-brand-600 hover:text-brand-700">Edit</a>
                                @endpermission
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">{{ $users->links() }}</div>
        @endif
    </div>
@endsection
