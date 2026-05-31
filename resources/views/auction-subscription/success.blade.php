@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="rounded-3xl border border-green-200 bg-green-50 p-8">
        <h1 class="text-3xl font-semibold text-green-900">
            Abonements aktivizēts
        </h1>

        <p class="mt-3 text-green-800">
            Maksājums veiksmīgi apstrādāts. Tagad vari piedalīties auto izsolēs.
        </p>

        <a
            href="{{ route('ads.index') }}"
            class="mt-6 inline-flex rounded-xl bg-green-700 px-5 py-3 font-semibold text-white hover:bg-green-800"
        >
            Skatīt izsoles
        </a>
    </div>
</div>
@endsection