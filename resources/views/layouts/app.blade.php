<!doctype html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AutoSlud</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-white selection:bg-amber-400/70 selection:text-slate-950">

    <div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10 overflow-hidden bg-slate-950">
        <div class="absolute -top-32 left-1/2 h-[560px] w-[560px] -translate-x-1/2 rounded-full bg-amber-400/10 blur-3xl"></div>
        <div class="absolute top-28 -left-28 h-[460px] w-[460px] rounded-full bg-blue-700/20 blur-3xl"></div>
        <div class="absolute -bottom-40 right-0 h-[560px] w-[560px] rounded-full bg-slate-700/25 blur-3xl"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950 via-slate-950 to-slate-900"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,.025)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,.025)_1px,transparent_1px)] bg-[size:48px_48px]"></div>
    </div>

    @include('partials.header')

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <style>
        @keyframes shimmer {
            0%   { transform: translateX(-140%) rotate(12deg); }
            60%  { transform: translateX(260%) rotate(12deg); }
            100% { transform: translateX(260%) rotate(12deg); }
        }
    </style>

</body>
</html>