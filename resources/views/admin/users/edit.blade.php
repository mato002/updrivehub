@extends('layouts.admin')

@section('title', 'Edit Team Member')
@section('page-title', 'Edit Team Member')

@section('content')
    <div class="admin-stat-card max-w-2xl">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
            @csrf
            @method('PATCH')
            @include('admin.users._form', ['user' => $user])
            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
        @if($user->id !== auth()->id())
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="mt-4" onsubmit="return confirm('Deactivate this user?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-secondary">Deactivate User</button>
            </form>
        @endif
    </div>
@endsection
