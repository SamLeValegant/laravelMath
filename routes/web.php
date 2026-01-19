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
        $a_min = is_numeric($request->input('a_min')) ? floatval($request->input('a_min')) : 1;
        $a_max = is_numeric($request->input('a_max')) ? floatval($request->input('a_max')) : 10;
        $b_min = is_numeric($request->input('b_min')) ? floatval($request->input('b_min')) : 1;
        $b_max = is_numeric($request->input('b_max')) ? floatval($request->input('b_max')) : 10;
        $a_min = max(-99, min($a_min, 99));
        $a_max = max($a_min, min($a_max, 99));
        $b_min = max(-99, min($b_min, 99));
        $b_max = max($b_min, min($b_max, 99));
        $decimal_rate = is_numeric($request->input('decimal_rate')) ? intval($request->input('decimal_rate')) : 0;
        $decimal_places = in_array($request->input('decimal_places'), ['1','2']) ? intval($request->input('decimal_places')) : 1;
        $calculs = collect();
        for ($i = 0; $i < $nb; $i++) {
            // a
            if (mt_rand(1,100) <= $decimal_rate) {
                $a = $a_min + mt_rand() / mt_getrandmax() * ($a_max - $a_min);
                $a = round($a, $decimal_places);
            } else {
                $a = mt_rand((int)ceil($a_min), (int)floor($a_max));
            }
            // b
            if (mt_rand(1,100) <= $decimal_rate) {
                $b = $b_min + mt_rand() / mt_getrandmax() * ($b_max - $b_min);
                $b = round($b, $decimal_places);
            } else {
                $b = mt_rand((int)ceil($b_min), (int)floor($b_max));
            }
            $calculs->push(['a' => $a, 'b' => $b]);
        }
        return view('pages.mental_pdf', [
            'calculs' => $calculs,
        ]);
    });

    Route::match(['get', 'post'], '/mental', function (\Illuminate\Http\Request $request) {
        $nb = intval($request->input('nb', 50));
        $nb = ($nb > 0 && $nb <= 200) ? $nb : 50;
        $a_min = is_numeric($request->input('a_min')) ? floatval($request->input('a_min')) : 1;
        $a_max = is_numeric($request->input('a_max')) ? floatval($request->input('a_max')) : 10;
        $b_min = is_numeric($request->input('b_min')) ? floatval($request->input('b_min')) : 1;
        $b_max = is_numeric($request->input('b_max')) ? floatval($request->input('b_max')) : 10;
        $a_min = max(-99, min($a_min, 99));
        $a_max = max($a_min, min($a_max, 99));
        $b_min = max(-99, min($b_min, 99));
        $b_max = max($b_min, min($b_max, 99));
        $decimal_rate = is_numeric($request->input('decimal_rate')) ? intval($request->input('decimal_rate')) : 0;
        $decimal_places = in_array($request->input('decimal_places'), ['1','2']) ? intval($request->input('decimal_places')) : 1;
        $operations = $request->input('operations', ['mul']);
        if (!is_array($operations) || empty($operations)) {
            $operations = ['mul'];
        }
        $validOps = ['add', 'sub', 'mul', 'div'];
        $operations = array_values(array_intersect($operations, $validOps));
        if (empty($operations)) {
            $operations = ['mul'];
        }
        if ($request->isMethod('post')) {
            $calculs = collect();
            $aList = $request->input('a', []);
            $bList = $request->input('b', []);
            $opList = $request->input('op', []);
            foreach ($aList as $i => $a) {
                $b = $bList[$i] ?? 1;
                $op = $opList[$i] ?? 'mul';
                $calculs->push(['a' => (float)$a, 'b' => (float)$b, 'op' => $op]);
            }
            $answers = $request->input('answer', []);
            $results = [];
            foreach ($calculs as $idx => $calc) {
                switch ($calc['op']) {
                    case 'add': $expected = $calc['a'] + $calc['b']; break;
                    case 'sub': $expected = $calc['a'] - $calc['b']; break;
                    case 'div': $expected = ($calc['b'] != 0) ? $calc['a'] / $calc['b'] : null; break;
                    default: $expected = $calc['a'] * $calc['b'];
                }
                $user = isset($answers[$idx]) ? $answers[$idx] : null;
                $results[$idx] = ($user !== null && $user !== '' && floatval($user) == $expected);
            }
        } else {
            $calculs = collect();
            for ($i = 0; $i < $nb; $i++) {
                // Choix de l'opération
                $op = $operations[array_rand($operations)];
                // a
                if (mt_rand(1,100) <= $decimal_rate) {
                    $a = $a_min + mt_rand() / mt_getrandmax() * ($a_max - $a_min);
                    $a = round($a, $decimal_places);
                } else {
                    $a = mt_rand((int)ceil($a_min), (int)floor($a_max));
                }
                // b
                if (mt_rand(1,100) <= $decimal_rate) {
                    $b = $b_min + mt_rand() / mt_getrandmax() * ($b_max - $b_min);
                    $b = round($b, $decimal_places);
                } else {
                    $b = mt_rand((int)ceil($b_min), (int)floor($b_max));
                }
                $calculs->push(['a' => $a, 'b' => $b, 'op' => $op]);
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
            'operations' => $operations,
        ]);
    })->name('mental');

    Route::match(['get', 'post'], '/mental/pdf', function (\Illuminate\Http\Request $request) {
        $aList = $request->input('a');
        $bList = $request->input('b');
        if (is_array($aList) && is_array($bList) && count($aList) === count($bList) && count($aList) > 0) {
            $calculs = collect();
            foreach ($aList as $i => $a) {
                $b = $bList[$i] ?? 1;
                $calculs->push(['a' => $a, 'b' => $b]);
            }
        } else {
            $nb = intval($request->input('nb', 50));
            $nb = ($nb > 0 && $nb <= 200) ? $nb : 50;
            $a_min = is_numeric($request->input('a_min')) ? floatval($request->input('a_min')) : 1;
            $a_max = is_numeric($request->input('a_max')) ? floatval($request->input('a_max')) : 10;
            $b_min = is_numeric($request->input('b_min')) ? floatval($request->input('b_min')) : 1;
            $b_max = is_numeric($request->input('b_max')) ? floatval($request->input('b_max')) : 10;
            $a_min = max(-99, min($a_min, 99));
            $a_max = max($a_min, min($a_max, 99));
            $b_min = max(-99, min($b_min, 99));
            $b_max = max($b_min, min($b_max, 99));
            $decimal_rate = is_numeric($request->input('decimal_rate')) ? intval($request->input('decimal_rate')) : 0;
            $decimal_places = in_array($request->input('decimal_places'), ['1','2']) ? intval($request->input('decimal_places')) : 1;
            $calculs = collect();
            for ($i = 0; $i < $nb; $i++) {
                // a
                if (mt_rand(1,100) <= $decimal_rate) {
                    $a = $a_min + mt_rand() / mt_getrandmax() * ($a_max - $a_min);
                    $a = round($a, $decimal_places);
                } else {
                    $a = mt_rand((int)ceil($a_min), (int)floor($a_max));
                }
                // b
                if (mt_rand(1,100) <= $decimal_rate) {
                    $b = $b_min + mt_rand() / mt_getrandmax() * ($b_max - $b_min);
                    $b = round($b, $decimal_places);
                } else {
                    $b = mt_rand((int)ceil($b_min), (int)floor($b_max));
                }
                $calculs->push(['a' => $a, 'b' => $b]);
            }
        }
        $pdf = Pdf::loadView('pages.mental_pdf', [
            'calculs' => $calculs,
        ]);
        return $pdf->download('calcul_mental_'.count($calculs).'_exercices.pdf');
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
