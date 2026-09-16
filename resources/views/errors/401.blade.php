@extends('errors.layout')
@section('title', '401 - Unauthorized')

@section('glow_color_1', '#f59e0b')
@section('glow_color_2', '#6366f1')
@section('glow_color_3', '#d97706')
@section('status_label', '401 Auth Required')
@section('status_dot', 'bg-amber-500')
@section('status_ping', 'bg-amber-400')

@section('content')
    <!-- Animated Key / Biometric Badge -->
    <div class="mb-6 relative flex items-center justify-center">
        <!-- Outer Glowing Ring -->
        <div class="w-32 h-32 sm:w-36 sm:h-36 rounded-full bg-gradient-to-tr from-amber-500/20 via-orange-500/15 to-indigo-500/10 dark:from-amber-500/30 dark:via-orange-500/20 dark:to-indigo-500/10 flex items-center justify-center relative shadow-inner p-2">
            <!-- Ripple effect -->
            <div class="absolute inset-0 rounded-full border border-amber-500/30 animate-ripple pointer-events-none"></div>
            <div class="absolute inset-3 rounded-full border border-orange-400/20 pointer-events-none"></div>

            <!-- Inner Core Sphere -->
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-tr from-white to-amber-50 dark:from-slate-800 dark:to-slate-900 border border-amber-200 dark:border-amber-500/30 shadow-lg flex items-center justify-center relative z-10 animate-float">
                <!-- Key SVG -->
                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                </svg>
            </div>

            <!-- Floating Key Badge -->
            <div class="absolute -top-1 -right-1 w-7 h-7 rounded-lg bg-amber-600 text-white flex items-center justify-center shadow-md text-xs font-black">
                !
            </div>
        </div>
    </div>

    <!-- 401 Gradient Number -->
    <div class="relative inline-block mb-2">
        <h1 class="font-heading text-7xl sm:text-8xl font-extrabold tracking-tighter leading-none bg-clip-text text-transparent bg-gradient-to-r from-amber-600 via-orange-500 to-indigo-500 dark:from-amber-400 dark:via-orange-400 dark:to-indigo-400">
            401
        </h1>
    </div>

    <!-- Title & Description -->
    <h2 class="font-heading text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight mb-3">
        Authentication Required
    </h2>

    <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-relaxed max-w-md mx-auto mb-8">
        You must be signed in with an authorized user account to view this manufacturing workspace or execute this action.
    </p>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="{{ route('tyro-login.login') }}" class="btn-modern-primary w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
            Log In to Your Account
        </a>

        <button type="button" onclick="window.history.back()" class="btn-modern-secondary w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Go Back
        </button>
    </div>
@endsection
