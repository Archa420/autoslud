@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-950 md:text-3xl">
                Admin panelis — Lietotāji
            </h1>

            <p class="mt-2 text-slate-600">
                Lietotāju pārvaldība, lomas un bloķēšanas statuss.
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.ads') }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Sludinājumi
            </a>

            <a href="{{ route('admin.auctions') }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Izsoles
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="mt-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold text-slate-700">ID</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-700">Vārds</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-700">E-pasts</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-700">Loma</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-700">Statuss</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-700">Izveidots</th>
                        <th class="px-5 py-3 text-right font-semibold text-slate-700">Darbības</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-5 py-4 text-slate-500">
                                {{ $user->id }}
                            </td>

                            <td class="px-5 py-4 font-semibold text-slate-950">
                                {{ $user->username }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $user->email }}
                            </td>

                            <td class="px-5 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $user->role }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                @if($user->is_blocked)
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                        Bloķēts
                                    </span>
                                @else
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                        Aktīvs
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-slate-500">
                                {{ $user->created_at?->format('d.m.Y H:i') }}
                            </td>

                            <td class="px-5 py-4 text-right">
                                @if(auth()->id() !== $user->id)
                                    <form method="POST"
                                          action="{{ route('admin.users.toggle-block', $user) }}"
                                          class="inline"
                                          onsubmit="return confirm('Mainīt lietotāja statusu?');">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="rounded-xl border px-4 py-2 text-xs font-semibold {{ $user->is_blocked ? 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'border-red-200 bg-red-50 text-red-700 hover:bg-red-100' }}">
                                            {{ $user->is_blocked ? 'Atbloķēt' : 'Bloķēt' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">Tavs konts</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-5 py-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection