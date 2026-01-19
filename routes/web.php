<?php

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Route de test pour afficher la vue PDF dans le navigateur (sans DomPDF)
    Route::get('/mental/pdf-test', function (\Illuminate\Http\Request $request) {
        $nb = intval($request->input('nb', 50));
        $nb = ($nb > 0 && $nb <= 200) ? $nb : 50;
        $a_min = intval($request->input('a_min', 1));
        $a_max = intval($request->input('a_max', 10));
        $b_min = intval($request->input('b_min', 1));
        $b_max = intval($request->input('b_max', 10));
        $a_min = max(1, min($a_min, 99));
        $a_max = max($a_min, min($a_max, 99));
        $b_min = max(1, min($b_min, 99));
        $b_max = max($b_min, min($b_max, 99));
        $calculs = collect();
        for ($i = 0; $i < $nb; $i++) {
            $a = rand($a_min, $a_max);
            $b = rand($b_min, $b_max);
            $calculs->push(['a' => $a, 'b' => $b]);
        }
        return view('pages.mental_pdf', [
            'calculs' => $calculs,
        ]);
    });

    Route::match(['get', 'post'], '/mental', function (\Illuminate\Http\Request $request) {
        $nb = intval($request->input('nb', 50));
        $nb = ($nb > 0 && $nb <= 200) ? $nb : 50;
        $a_min = intval($request->input('a_min', 1));
        $a_max = intval($request->input('a_max', 10));
        $b_min = intval($request->input('b_min', 1));
        $b_max = intval($request->input('b_max', 10));
        $a_min = max(1, min($a_min, 99));
        $a_max = max($a_min, min($a_max, 99));
        $b_min = max(1, min($b_min, 99));
        $b_max = max($b_min, min($b_max, 99));
        if ($request->isMethod('post')) {
            $calculs = collect();
            $aList = $request->input('a', []);
            $bList = $request->input('b', []);
            foreach ($aList as $i => $a) {
                $b = $bList[$i] ?? 1;
                $calculs->push(['a' => (int)$a, 'b' => (int)$b]);
            }
            $answers = $request->input('answer', []);
            $results = [];
            foreach ($calculs as $idx => $calc) {
                $expected = $calc['a'] * $calc['b'];
                $user = isset($answers[$idx]) ? $answers[$idx] : null;
                $results[$idx] = ($user !== null && $user !== '' && intval($user) === $expected);
            }
        } else {
            $calculs = collect();
            for ($i = 0; $i < $nb; $i++) {
                $a = rand($a_min, $a_max);
                $b = rand($b_min, $b_max);
                $calculs->push(['a' => $a, 'b' => $b]);
            }
            $results = null;
        }
        return view('pages.mental', [
            'calculs' => $calculs,
            'results' => $results,
            'nb' => $nb,
            'a_min' => $a_min,
            'a_max' => $a_max,
            'b_min' => $b_min,
            'b_max' => $b_max,
        ]);
    })->name('mental');

    Route::get('/mental/pdf', function (\Illuminate\Http\Request $request) {
        $nb = intval($request->input('nb', 50));
        $nb = ($nb > 0 && $nb <= 200) ? $nb : 50;
        $a_min = intval($request->input('a_min', 1));
        $a_max = intval($request->input('a_max', 10));
        $b_min = intval($request->input('b_min', 1));
        $b_max = intval($request->input('b_max', 10));
        $a_min = max(1, min($a_min, 99));
        $a_max = max($a_min, min($a_max, 99));
        $b_min = max(1, min($b_min, 99));
        $b_max = max($b_min, min($b_max, 99));
        $calculs = collect();
        for ($i = 0; $i < $nb; $i++) {
            $a = rand($a_min, $a_max);
            $b = rand($b_min, $b_max);
            $calculs->push(['a' => $a, 'b' => $b]);
        }
        $pdf = Pdf::loadView('pages.mental_pdf', [
            'calculs' => $calculs,
        ]);
        return $pdf->download('calcul_mental_'.$nb.'_exercices.pdf');
    })->name('mental.pdf');

    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');
    Route::get('/profile-page', function () {
        return view('pages.profile');
    })->name('profile.page');
    Route::get('/settings', function () {
        return view('pages.settings');
    })->name('settings');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
