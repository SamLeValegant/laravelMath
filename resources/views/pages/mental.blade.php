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
                    <label for="a_min" class="font-medium">a min :</label>
                    <input type="number" min="-99" max="99" step="any" id="a_min" name="a_min" value="{{ request('a_min', 1) }}" class="border rounded px-2 py-1 w-16 focus:outline-none focus:ring focus:border-blue-300" />
                    <label for="a_max" class="font-medium">a max :</label>
                    <input type="number" min="-99" max="99" step="any" id="a_max" name="a_max" value="{{ request('a_max', 10) }}" class="border rounded px-2 py-1 w-16 focus:outline-none focus:ring focus:border-blue-300" />
                    <label for="b_min" class="font-medium">b min :</label>
                    <input type="number" min="-99" max="99" step="any" id="b_min" name="b_min" value="{{ request('b_min', 1) }}" class="border rounded px-2 py-1 w-16 focus:outline-none focus:ring focus:border-blue-300" />
                    <label for="b_max" class="font-medium">b max :</label>
                    <input type="number" min="-99" max="99" step="any" id="b_max" name="b_max" value="{{ request('b_max', 10) }}" class="border rounded px-2 py-1 w-16 focus:outline-none focus:ring focus:border-blue-300" />
                    <label for="decimal_rate" class="font-medium">Décimaux :</label>
                    <input type="range" id="decimal_rate" name="decimal_rate" min="0" max="100" value="{{ request('decimal_rate', 0) }}" class="w-32 align-middle" oninput="document.getElementById('decimal_rate_val').innerText = this.value + '%'">
                    <span id="decimal_rate_val">{{ request('decimal_rate', 0) }}%</span>
                    <label for="decimal_places" class="font-medium ml-4">Chiffres après la virgule :</label>
                    <select id="decimal_places" name="decimal_places" class="border rounded px-2 py-1 w-16 focus:outline-none focus:ring focus:border-blue-300">
                        <option value="1" {{ request('decimal_places', 1) == 1 ? 'selected' : '' }}>1</option>
                        <option value="2" {{ request('decimal_places', 1) == 2 ? 'selected' : '' }}>2</option>
                    </select>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600 transition">Appliquer</button>
                </form>
            </div>
        </div>
        <h1 class="text-2xl font-bold mb-2">Calcul mental</h1>
        <div class="mb-6 text-center text-gray-700">
            <span class="mr-4">a : <span class="font-mono">{{ request('a_min', 1) }}</span> à <span class="font-mono">{{ request('a_max', 10) }}</span></span>
            <span>b : <span class="font-mono">{{ request('b_min', 1) }}</span> à <span class="font-mono">{{ request('b_max', 10) }}</span></span>
            <span class="ml-4">Décimaux : <span class="font-mono">{{ request('decimal_rate', 0) }}%</span>,
                <span>Chiffres après la virgule : <span class="font-mono">{{ request('decimal_places', 1) }}</span></span>
            </span>
        </div>
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
            <input type="hidden" name="a_min" value="{{ request('a_min', 1) }}" />
            <input type="hidden" name="a_max" value="{{ request('a_max', 10) }}" />
            <input type="hidden" name="b_min" value="{{ request('b_min', 1) }}" />
            <input type="hidden" name="b_max" value="{{ request('b_max', 10) }}" />
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($calculs ?? [] as $i => $calc)
                    <div class="text-lg flex items-center gap-2 p-3">
                        {{ rtrim(rtrim(number_format($calc['a'], 2, ',', ' '), '0'), ',') }} × {{ rtrim(rtrim(number_format($calc['b'], 2, ',', ' '), '0'), ',') }} =
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
            <div class="mt-8 mb-8 flex justify-center w-full max-w-3xl mx-auto">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">Valider</button>
            </div>
        </form>
        <div class="mt-8 mb-8 flex flex-row items-center justify-between gap-2 w-full max-w-3xl mx-auto">
            <form method="GET" action="{{ route('mental') }}">
                <input type="hidden" name="nb" value="{{ $nb ?? 50 }}" />
                <input type="hidden" name="a_min" value="{{ request('a_min', 1) }}" />
                <input type="hidden" name="a_max" value="{{ request('a_max', 10) }}" />
                <input type="hidden" name="b_min" value="{{ request('b_min', 1) }}" />
                <input type="hidden" name="b_max" value="{{ request('b_max', 10) }}" />
                <input type="hidden" name="decimal_rate" value="{{ request('decimal_rate', 0) }}" />
                <input type="hidden" name="decimal_places" value="{{ request('decimal_places', 1) }}" />
                <button type="submit" class="bg-gray-300 text-gray-800 px-6 py-2 rounded shadow hover:bg-gray-400 transition">Nouvel exercice</button>
            </form>
            <form method="GET" action="{{ route('mental.pdf') }}">
                <input type="hidden" name="nb" value="{{ $nb ?? 50 }}" />
                <input type="hidden" name="a_min" value="{{ request('a_min', 1) }}" />
                <input type="hidden" name="a_max" value="{{ request('a_max', 10) }}" />
                <input type="hidden" name="b_min" value="{{ request('b_min', 1) }}" />
                <input type="hidden" name="b_max" value="{{ request('b_max', 10) }}" />
                <input type="hidden" name="decimal_rate" value="{{ request('decimal_rate', 0) }}" />
                <input type="hidden" name="decimal_places" value="{{ request('decimal_places', 1) }}" />
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">Télécharger en PDF</button>
            </form>
        </div>
    </div>
@endsection
