@extends('layouts.app')

@section('content')
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-6">Calcul mental</h1>
        <form method="POST" action="{{ route('mental') }}">
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
            <div class="mt-8 flex justify-center">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">Valider</button>
            </div>
        </form>
    </div>
@endsection
