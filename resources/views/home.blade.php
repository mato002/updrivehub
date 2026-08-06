@extends('layouts.app')

@section('title', 'Join Our Driving Team')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-brand-700 via-brand-600 to-brand-800 text-white">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 50%, white 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="relative mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:py-28">
            <div class="max-w-2xl">
                <p class="mb-4 inline-flex rounded-full bg-white/10 px-4 py-1 text-sm font-medium backdrop-blur">Now Hiring Professional Drivers</p>
                <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">Join Our Driving Team</h1>
                <p class="mt-6 text-lg text-brand-100">
                    Build a rewarding career with {{ $companyName }}. We are looking for skilled, reliable, and safety-conscious drivers to join our growing fleet across Kenya.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center rounded-xl bg-white px-6 py-3 text-sm font-semibold text-brand-700 shadow-lg transition hover:bg-brand-50">
                        Apply Now
                    </a>
                    <a href="#process" class="inline-flex items-center rounded-xl border border-white/30 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Benefits --}}
    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-slate-900">Why Drive With Us</h2>
            <p class="mt-3 text-slate-600">Competitive benefits and a supportive work environment.</p>
        </div>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach([
                ['title' => 'Competitive Pay', 'desc' => 'Attractive remuneration packages with performance incentives.', 'icon' => '💰'],
                ['title' => 'Modern Fleet', 'desc' => 'Well-maintained vehicles with regular servicing and safety checks.', 'icon' => '🚛'],
                ['title' => 'Career Growth', 'desc' => 'Training programs and clear paths for advancement.', 'icon' => '📈'],
                ['title' => 'Health & Safety', 'desc' => 'Comprehensive safety protocols and medical support.', 'icon' => '🛡️'],
                ['title' => 'Flexible Routes', 'desc' => 'Local, regional, and long-distance driving opportunities.', 'icon' => '🗺️'],
                ['title' => 'Supportive Team', 'desc' => 'Dedicated dispatch and HR support when you need it.', 'icon' => '🤝'],
            ] as $benefit)
                <div class="card text-center">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-2xl">{{ $benefit['icon'] }}</div>
                    <h3 class="text-lg font-semibold text-slate-900">{{ $benefit['title'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $benefit['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Qualifications --}}
    <section class="bg-white py-16">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="grid gap-12 lg:grid-cols-2">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900">Minimum Qualifications</h2>
                    <ul class="mt-6 space-y-4">
                        @foreach([
                            'Valid Kenyan driving licence (minimum Class B or relevant class)',
                            'At least 2 years of professional driving experience',
                            'Clean driving record with no major traffic violations',
                            'Certificate of Good Conduct (preferred)',
                            'Minimum age of 25 years',
                            'Physically fit with valid medical certificate',
                            'Excellent knowledge of Kenyan roads and traffic regulations',
                        ] as $qualification)
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand-100 text-xs font-bold text-brand-700">✓</span>
                                <span class="text-slate-700">{{ $qualification }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div id="process">
                    <h2 class="text-3xl font-bold text-slate-900">Recruitment Process</h2>
                    <ol class="mt-6 space-y-6">
                        @foreach([
                            ['step' => '1', 'title' => 'Submit Application', 'desc' => 'Complete the online form and upload required documents.'],
                            ['step' => '2', 'title' => 'Document Review', 'desc' => 'Our HR team reviews your application and credentials.'],
                            ['step' => '3', 'title' => 'Interview & Assessment', 'desc' => 'Shortlisted candidates are invited for an interview and driving assessment.'],
                            ['step' => '4', 'title' => 'Background Check', 'desc' => 'Verification of documents, references, and driving history.'],
                            ['step' => '5', 'title' => 'Offer & Onboarding', 'desc' => 'Successful candidates receive an offer and begin orientation.'],
                        ] as $process)
                            <li class="flex gap-4">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-600 text-sm font-bold text-white">{{ $process['step'] }}</span>
                                <div>
                                    <h3 class="font-semibold text-slate-900">{{ $process['title'] }}</h3>
                                    <p class="mt-1 text-sm text-slate-600">{{ $process['desc'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="mx-auto max-w-3xl px-4 py-16 sm:px-6">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-slate-900">Frequently Asked Questions</h2>
        </div>
        <div class="mt-10 space-y-4">
            @foreach([
                ['q' => 'How long does the application process take?', 'a' => 'We typically review applications within 5–10 business days. Shortlisted candidates will be contacted for the next steps.'],
                ['q' => 'What documents do I need?', 'a' => 'You will need your National ID (front and back), a passport-style selfie photo, and your driving licence. Optional documents include CV, certificate of good conduct, medical certificate, and recommendation letters.'],
                ['q' => 'Can I apply if my licence is about to expire?', 'a' => 'Your driving licence must be valid at the time of application. Please renew your licence before applying if it has expired or is expiring soon.'],
                ['q' => 'Will I receive confirmation after applying?', 'a' => 'Yes. You will receive a confirmation email with your unique reference number immediately after submitting your application.'],
            ] as $faq)
                <details class="group card !p-0 overflow-hidden">
                    <summary class="flex cursor-pointer items-center justify-between px-6 py-4 font-semibold text-slate-900 marker:content-none">
                        {{ $faq['q'] }}
                        <span class="ml-4 text-brand-600 transition group-open:rotate-180">▼</span>
                    </summary>
                    <div class="border-t border-slate-100 px-6 py-4 text-sm text-slate-600">{{ $faq['a'] }}</div>
                </details>
            @endforeach
        </div>
    </section>

    {{-- Contact & CTA --}}
    <section class="bg-brand-600 py-16 text-white">
        <div class="mx-auto max-w-6xl px-4 text-center sm:px-6">
            <h2 class="text-3xl font-bold">Ready to Hit the Road?</h2>
            <p class="mx-auto mt-4 max-w-xl text-brand-100">
                Start your application today. Have questions? Contact our recruitment team.
            </p>
            <div class="mt-8 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                <a href="{{ route('home') }}" class="inline-flex items-center rounded-xl bg-white px-8 py-3 text-sm font-semibold text-brand-700 shadow-lg transition hover:bg-brand-50">
                    Apply Now
                </a>
            </div>
            <div class="mt-10 flex flex-col items-center gap-2 text-sm text-brand-100 sm:flex-row sm:justify-center sm:gap-8">
                <span>📞 {{ config('recruitment.phone') }}</span>
                <span>✉️ {{ config('recruitment.email') }}</span>
                <span>📍 {{ config('recruitment.address') }}</span>
            </div>
        </div>
    </section>
@endsection
