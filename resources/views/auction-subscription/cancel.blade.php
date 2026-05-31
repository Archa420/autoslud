@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="rounded-3xl border border-amber-200 bg-amber-50 p-8">
        <h1 class="text-3xl font-semibold text-amber-900">
            Apmaksa atcelta
        </h1>

        <p class="mt-3 text-amber-800">
            Maksājums netika pabeigts. Vari mēģināt vēlreiz, kad vēlies piedalīties izsolēs.
        </p>

        <a
            href="{{ route('auction-subscription.index') }}"
            class="mt-6 inline-flex rounded-xl bg-amber-600 px-5 py-3 font-semibold text-white hover:bg-amber-700"
        >
            Mēģināt vēlreiz
        </a>
    </div>
</div>
@endsection