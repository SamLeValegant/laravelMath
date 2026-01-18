
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::match(['get', 'post'], '/mental', function (\Illuminate\Http\Request $request) {
        $nb = intval($request->input('nb', 50));
        $nb = ($nb > 0 && $nb <= 200) ? $nb : 50;
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
                $a = rand(1, 10);
                $b = rand(1, 10);
                $calculs->push(['a' => $a, 'b' => $b]);
            }
            $results = null;
        }
        return view('pages.mental', [
            'calculs' => $calculs,
            'results' => $results,
            'nb' => $nb,
        ]);
    })->name('mental');
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
