@extends('errors.layout')
@section('title', '404 - Page Not Found')

@section('glow_color_1', '#6366f1')
@section('glow_color_2', '#06b6d4')
@section('glow_color_3', '#8b5cf6')
@section('status_label', '404 Missing Route')
@section('status_dot', 'bg-indigo-500')
@section('status_ping', 'bg-indigo-400')

@section('content')
    <!-- Animated Illustration / Radar Badge -->
    <div class="mb-6 relative flex items-center justify-center">
        <!-- Outer Glowing Ring -->
        <div class="w-32 h-32 sm:w-36 sm:h-36 rounded-full bg-gradient-to-tr from-indigo-500/20 via-sky-500/15 to-purple-500/10 dark:from-indigo-500/30 dark:via-sky-500/20 dark:to-purple-500/10 flex items-center justify-center relative shadow-inner p-2">
            <!-- Ripple Effect -->
            <div class="absolute inset-0 rounded-full border border-indigo-400/30 animate-ripple pointer-events-none"></div>
            <div class="absolute inset-3 rounded-full border border-sky-400/20 pointer-events-none"></div>
            
            <!-- Rotating Radar Sweep Line -->
            <div class="absolute inset-0 rounded-full animate-radar pointer-events-none" style="background: conic-gradient(from 0deg, transparent 0deg, rgba(99, 102, 241, 0.25) 60deg, transparent 90deg);"></div>

            <!-- Inner Core Sphere -->
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-tr from-white to-slate-100 dark:from-slate-800 dark:to-slate-900 border border-slate-200/80 dark:border-indigo-500/30 shadow-lg flex items-center justify-center relative z-10 animate-float">
                <!-- Compass / Lost Search SVG -->
                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM13.5 10.5h-3m3 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                    <circle cx="12" cy="10" r="1.5" fill="currentColor"></circle>
                </svg>
            </div>

            <!-- Floating Mini Badges -->
            <div class="absolute -top-1 -right-1 w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center shadow-md text-xs font-black">
                ?
            </div>
        </div>
    </div>

    <!-- 404 Gradient Number -->
    <div class="relative inline-block mb-2">
        <h1 class="font-heading text-7xl sm:text-8xl font-extrabold tracking-tighter leading-none bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-indigo-500 to-cyan-500 dark:from-indigo-400 dark:via-sky-400 dark:to-cyan-300">
            404
        </h1>
    </div>

    <!-- Title & Description -->
    <h2 class="font-heading text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight mb-3">
        Page Lost in Transit
    </h2>

    <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-relaxed max-w-md mx-auto mb-8">
        @if($exception && $exception->getMessage())
            <span class="font-medium text-slate-800 dark:text-slate-200 block mb-2">{{ $exception->getMessage() }}</span>
        @else
            We couldn't locate the factory record, order, or page you requested. It may have been moved, deleted, or never existed in the database.
        @endif
    </p>

    <!-- Interactive Action Buttons -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="{{ route('tyro-dashboard.index') }}" class="btn-modern-primary w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Return to Dashboard
        </a>

        <button type="button" onclick="window.history.back()" class="btn-modern-secondary w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Go Back
        </button>
    </div>
@endsection