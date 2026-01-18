@extends('layouts.app')

@section('content')
    <div class="p-8">
        <div class="mb-4">
            <button id="toggle-filters" type="button" class="flex items-center gap-2 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">
                <span>Filtres</span>
                <svg id="chevron" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div id="filters-panel" class="mt-2 bg-gray-50 border rounded p-4 hidden">
                <form method="GET" action="{{ route('mental') }}" class="flex items-center gap-4 flex-wrap">
                    <label for="nb" class="font-medium">Nombre de calculs :</label>
                    <input type="number" min="1" max="200" id="nb" name="nb" value="{{ $nb ?? 50 }}" class="border rounded px-2 py-1 w-24 focus:outline-none focus:ring focus:border-blue-300" />
                    <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600 transition">Appliquer</button>
                </form>
            </div>
        </div>
        <h1 class="text-2xl font-bold mb-6">Calcul mental</h1>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-filters');
            const panel = document.getElementById('filters-panel');
            const chevron = document.getElementById('chevron');
            if (toggleBtn && panel && chevron) {
                toggleBtn.addEventListener('click', () => {
                    panel.classList.toggle('hidden');
                    chevron.style.transform = panel.classList.contains('hidden') ? '' : 'rotate(180deg)';
                });
            }
        });
    </script>
        @if(isset($results))
            @php
                $total = count($results);
                $good = collect($results)->filter()->count();
                $percent = $total > 0 ? round($good / $total * 100) : 0;
            @endphp
            <div class="mb-6 text-center">
                <span class="text-xl font-semibold">Score : </span>
                <span class="text-green-700 font-bold">{{ $good }}</span>
                <span>/ {{ $total }} ({{ $percent }}%)</span>
            </div>
        @endif
        <form method="POST" action="{{ route('mental') }}">
            <input type="hidden" name="nb" value="{{ $nb ?? 50 }}" />
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($calculs ?? [] as $i => $calc)
                    <div class="text-lg flex items-center gap-2 p-3">
                        {{ $calc['a'] }} × {{ $calc['b'] }} =
                        <input type="hidden" name="a[]" value="{{ $calc['a'] }}" />
                        <input type="hidden" name="b[]" value="{{ $calc['b'] }}" />
                        <input type="number" class="border rounded px-2 py-1 w-20 focus:outline-none focus:ring focus:border-blue-300" name="answer[]" autocomplete="off"
                            value="{{ isset($results) ? (request('answer')[$i] ?? '') : old('answer.' . $i) }}" />
                        @if(isset($results))
                            @php $expected = $calc['a'] * $calc['b']; $user = request('answer')[$i] ?? ''; @endphp
                            @if($results[$i])
                                <span class="ml-2 text-green-600 font-bold">✔</span>
                            @else
                                <span class="ml-2 text-red-600 font-bold">✖ {{ $expected }}</span>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="mt-8 flex flex-col items-center gap-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">Valider</button>
            </div>
        </form>
        <div class="flex flex-col items-center gap-2 mt-2">
            <form method="GET" action="{{ route('mental') }}">
                <input type="hidden" name="nb" value="{{ $nb ?? 50 }}" />
                <button type="submit" class="bg-gray-300 text-gray-800 px-6 py-2 rounded shadow hover:bg-gray-400 transition">Nouvel exercice</button>
            </form>
            <form method="GET" action="{{ route('mental.pdf') }}">
                <input type="hidden" name="nb" value="{{ $nb ?? 50 }}" />
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">Télécharger en PDF</button>
            </form>
        </div>
        </form>
    </div>
@endsection
