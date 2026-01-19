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
                    <div class="flex items-center gap-2">
                        <span class="font-medium">Opérations :</span>
                        <label class="flex items-center gap-1">
                            <input type="checkbox" name="operations[]" value="add" {{ in_array('add', (array)request('operations', ['mul'])) ? 'checked' : '' }}> +
                        </label>
                        <label class="flex items-center gap-1">
                            <input type="checkbox" name="operations[]" value="sub" {{ in_array('sub', (array)request('operations', [])) ? 'checked' : '' }}> -
                        </label>
                        <label class="flex items-center gap-1">
                            <input type="checkbox" name="operations[]" value="mul" {{ in_array('mul', (array)request('operations', ['mul'])) ? 'checked' : '' }}> ×
                        </label>
                        <label class="flex items-center gap-1">
                            <input type="checkbox" name="operations[]" value="div" {{ in_array('div', (array)request('operations', [])) ? 'checked' : '' }}> ÷
                        </label>
                    </div>
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
                    @php
                        $op = $calc['op'] ?? 'mul';
                        $opSymbol = match($op) {
                            'add' => '+',
                            'sub' => '-',
                            'div' => '÷',
                            default => '×',
                        };
                    @endphp
                    <div class="text-lg flex items-center gap-2 p-3">
                        <span ondblclick="editNumber(this, 'a', {{ $i }})" class="editable-number cursor-pointer select-none" title="Double-cliquez pour modifier">{{ rtrim(rtrim(number_format($calc['a'], 2, ',', ' '), '0'), ',') }}</span>
                        <span>{{ $opSymbol }}</span>
                        <span ondblclick="editNumber(this, 'b', {{ $i }})" class="editable-number cursor-pointer select-none" title="Double-cliquez pour modifier">{{ rtrim(rtrim(number_format($calc['b'], 2, ',', ' '), '0'), ',') }}</span>
                        =
                        <input type="hidden" name="a[]" id="a_input_{{ $i }}" value="{{ $calc['a'] }}" />
                        <input type="hidden" name="b[]" id="b_input_{{ $i }}" value="{{ $calc['b'] }}" />
                        <input type="hidden" name="op[]" value="{{ $op }}" />
                        <input type="number" class="border rounded px-2 py-1 w-20 focus:outline-none focus:ring focus:border-blue-300" name="answer[]" autocomplete="off"
                            value="{{ isset($results) ? (request('answer')[$i] ?? '') : old('answer.' . $i) }}" />
                        @if(isset($results))
                            @php
                                switch($op) {
                                    case 'add': $expected = $calc['a'] + $calc['b']; break;
                                    case 'sub': $expected = $calc['a'] - $calc['b']; break;
                                    case 'div': $expected = ($calc['b'] != 0) ? $calc['a'] / $calc['b'] : null; break;
                                    default: $expected = $calc['a'] * $calc['b'];
                                }
                                $user = request('answer')[$i] ?? '';
                            @endphp
                            @if($results[$i])
                                <span class="ml-2 text-green-600 font-bold">✔</span>
                            @else
                                <span class="ml-2 text-red-600 font-bold">✖ {{ $expected }}</span>
                            @endif
                        @endif
                    </div>
                @endforeach
                <script>
                function editNumber(span, type, idx) {
                    const current = span.innerText.replace(',', '.').replace(/\s/g, '');
                    const input = document.createElement('input');
                    input.type = 'number';
                    input.step = 'any';
                    input.value = current;
                    input.className = 'border rounded px-1 py-0 w-16 text-center';
                    input.onblur = function() {
                        let val = input.value.replace(',', '.');
                        if (val === '' || isNaN(val)) val = current;
                        span.innerText = val.replace('.', ',');
                        document.getElementById(type+'_input_'+idx).value = val;
                        span.style.display = '';
                        input.remove();
                    };
                    input.onkeydown = function(e) {
                        if (e.key === 'Enter' || e.key === 'Escape') input.blur();
                    };
                    span.parentNode.insertBefore(input, span);
                    span.style.display = 'none';
                    input.focus();
                    input.select();
                }
                </script>
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
                @php
                    // On récupère les opérations depuis la requête POST (si on vient de valider), sinon GET, sinon backend
                    $ops = request()->isMethod('post') ? (array)request('operations', $operations ?? ['mul']) : (array)request('operations', $operations ?? ['mul']);
                    if (!is_array($ops)) $ops = [$ops];
                @endphp
                @foreach($ops as $op)
                    <input type="hidden" name="operations[]" value="{{ $op }}" />
                @endforeach
                <button type="submit" class="bg-gray-300 text-gray-800 px-6 py-2 rounded shadow hover:bg-gray-400 transition">Nouvel exercice</button>
            </form>
            <form method="POST" action="{{ route('mental.pdf') }}" id="pdfForm">
                @csrf
                @foreach ($calculs ?? [] as $i => $calc)
                    <input type="hidden" name="a[]" id="pdf_a_{{ $i }}" value="{{ $calc['a'] }}" />
                    <input type="hidden" name="b[]" id="pdf_b_{{ $i }}" value="{{ $calc['b'] }}" />
                    <input type="hidden" name="op[]" value="{{ $calc['op'] ?? 'mul' }}" />
                @endforeach
                <input type="hidden" name="nb" value="{{ $nb ?? 50 }}" />
                <input type="hidden" name="a_min" value="{{ request('a_min', 1) }}" />
                <input type="hidden" name="a_max" value="{{ request('a_max', 10) }}" />
                <input type="hidden" name="b_min" value="{{ request('b_min', 1) }}" />
                <input type="hidden" name="b_max" value="{{ request('b_max', 10) }}" />
                <input type="hidden" name="decimal_rate" value="{{ request('decimal_rate', 0) }}" />
                <input type="hidden" name="decimal_places" value="{{ request('decimal_places', 1) }}" />
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">Télécharger en PDF</button>
            </form>
            <script>
            document.getElementById('pdfForm').addEventListener('submit', function(e) {
                // Synchronise les valeurs éditées dans le DOM vers le formulaire PDF
                @foreach ($calculs ?? [] as $i => $calc)
                    var aVal = document.getElementById('a_input_{{ $i }}').value;
                    var bVal = document.getElementById('b_input_{{ $i }}').value;
                    document.getElementById('pdf_a_{{ $i }}').value = aVal;
                    document.getElementById('pdf_b_{{ $i }}').value = bVal;
                @endforeach
            });
            </script>
        </div>
    </div>
@endsection
