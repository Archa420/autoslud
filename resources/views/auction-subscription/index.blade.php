@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-semibold text-slate-900">
            Izsoļu piekļuve
        </h1>

        <p class="mt-3 text-slate-600">
            Lai piedalītos auto izsolēs un iesniegtu solījumus, nepieciešams aktīvs izsoļu abonements.
        </p>

        <div class="mt-6 rounded-2xl bg-slate-50 p-6">
            <p class="text-sm font-medium text-slate-500">
                Abonements
            </p>

            <p class="mt-2 text-4xl font-bold text-slate-900">
                50 € <span class="text-base font-medium text-slate-500">/ mēnesī</span>
            </p>

            <ul class="mt-5 space-y-2 text-sm text-slate-700">
                <li>Var piedalīties aktīvajās izsolēs</li>
                <li>Var iesniegt cenas solījumus</li>
                <li>Var iesniegt izpirkuma cenas piedāvājumu</li>
                <li>Auto netiek automātiski nopirkts</li>
            </ul>
        </div>

        <form action="{{ route('auction-subscription.checkout') }}" method="POST" class="mt-6">
            @csrf

            <button
                type="submit"
                class="w-full rounded-xl bg-slate-900 px-5 py-3 font-semibold text-white hover:bg-slate-800"
            >
                Doties uz Stripe apmaksu
            </button>
        </form>
    </div>
</div>
@endsection