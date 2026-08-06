@extends('layouts.app')

@section('title', 'Application Submitted')
@section('hero', true)

@push('head')
<style>
    :root {
        --form-bg-image: url('{{ config('recruitment.backgrounds')[1] }}');
    }
</style>
@endpush

@section('content')
    <section class="mx-auto max-w-lg px-4 py-20 sm:px-6">
        <div class="glass-card text-center">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-green-100 shadow-inner">
                <i class="fa-solid fa-circle-check text-4xl text-green-600"></i>
            </div>
            <h1 class="mt-6 text-3xl font-bold text-slate-900">Thank You!</h1>
            <p class="mt-3 text-slate-600">Your application has been successfully submitted.</p>
            <div class="mt-6 rounded-xl bg-brand-50 px-6 py-4">
                <p class="flex items-center justify-center gap-2 text-sm font-medium text-brand-700">
                    <i class="fa-solid fa-hashtag"></i> Reference Number
                </p>
                <p class="mt-1 font-mono text-xl font-bold text-brand-900">{{ $referenceNumber }}</p>
            </div>
            <p class="mt-6 text-sm text-slate-500">
                <i class="fa-solid fa-envelope-circle-check mr-1 text-brand-400"></i>
                A confirmation email has been sent to your registered email address.
                We will contact you if you are shortlisted.
            </p>
            <a href="{{ route('home') }}" class="btn-primary mt-8">
                <i class="fa-solid fa-house mr-1.5"></i> Return Home
            </a>
        </div>
    </section>
@endsection
