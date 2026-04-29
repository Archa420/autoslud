<!doctype html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AutoSlud</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 selection:bg-amber-200/70 selection:text-slate-900">
    {{-- Subtle premium background --}}
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-28 left-1/2 h-[520px] w-[520px] -translate-x-1/2 rounded-full bg-amber-300/25 blur-3xl"></div>
        <div class="absolute top-24 -left-24 h-[420px] w-[420px] rounded-full bg-indigo-300/20 blur-3xl"></div>
        <div class="absolute -bottom-36 right-0 h-[520px] w-[520px] rounded-full bg-emerald-300/15 blur-3xl"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-white via-slate-50 to-slate-100"></div>
    </div>

    @include('partials.header')

    <main class="mx-auto max-w-6xl px-4 py-8">
        @yield('content')
    </main>

    {{-- Tiny custom animation keyframes (global) --}}
    <style>
        @keyframes shimmer {
            0% { transform: translateX(-140%) rotate(12deg); }
            60% { transform: translateX(260%) rotate(12deg); }
            100% { transform: translateX(260%) rotate(12deg); }
        }
    </style>
</body>
</html>
