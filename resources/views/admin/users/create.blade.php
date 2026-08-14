@extends('layouts.admin')

@section('title', 'Add Team Member')
@section('page-title', 'Add Team Member')

@section('content')
    <div class="admin-stat-card max-w-2xl">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf
            @include('admin.users._form')
            <button type="submit" class="btn-primary">Create Team Member</button>
        </form>
    </div>
@endsection
