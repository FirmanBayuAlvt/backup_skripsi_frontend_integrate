<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\LivestockController;
use App\Http\Controllers\Web\PenController;
use App\Http\Controllers\Web\FeedController;
use App\Http\Controllers\Web\PredictionController;
use App\Http\Controllers\Web\ReportController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

/*
|--------------------------------------------------------------------------
| LOGIN ROUTES (Public)
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    $email = request('email');
    $password = request('password');
    if ($email && $password) {
        session(['user' => ['name' => 'Admin', 'email' => $email]]);
        return redirect()->route('dashboard');
    }
    return back()->withErrors(['email' => 'Email dan password harus diisi.']);
})->name('login.post');

Route::post('/logout', function () {
    session()->flush();
    return redirect()->route('login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Aktifkan kembali setelah login berfungsi)
|--------------------------------------------------------------------------
*/
// Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Livestock
    Route::prefix('livestocks')->name('livestocks.')->group(function () {
        Route::get('/', [LivestockController::class, 'index'])->name('index');
        Route::get('/{id}', [LivestockController::class, 'show'])
            ->where('id', '[0-9]+')
            ->name('show');
    });

    // Pens
    Route::prefix('pens')->name('pens.')->group(function () {
        Route::get('/', [PenController::class, 'index'])->name('index');
        Route::get('/{id}', [PenController::class, 'show'])
            ->where('id', '[0-9]+')
            ->name('show');
        Route::get('/{id}/analytics', [PenController::class, 'analytics'])
            ->where('id', '[0-9]+')
            ->name('analytics');
    });

    // Feeds
    Route::prefix('feeds')->name('feeds.')->group(function () {
        Route::get('/', [FeedController::class, 'index'])->name('index');
        Route::get('/stock', [FeedController::class, 'stock'])->name('stock');
        Route::get('/requirements', [FeedController::class, 'requirements'])->name('requirements');
    });

    // Predictions
    Route::prefix('predictions')->name('predictions.')->group(function () {
        Route::get('/', [PredictionController::class, 'index'])->name('index');
        Route::get('/correlation', [PredictionController::class, 'correlation'])->name('correlation');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/performance', [ReportController::class, 'performance'])->name('performance');
        Route::get('/growth', [ReportController::class, 'growth'])->name('growth');
    });

    /*
    |--------------------------------------------------------------------------
    | WEB API (AJAX)
    |--------------------------------------------------------------------------
    */
    Route::prefix('web-api')->name('web-api.')->group(function () {
        // Dashboard API
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/overview', [DashboardController::class, 'getOverviewData'])->name('overview');
            Route::get('/pen-analytics', [DashboardController::class, 'getPenAnalytics'])->name('pen-analytics');
            Route::get('/predictions/history', [DashboardController::class, 'getPredictionHistory'])->name('predictions.history');
        });

        // Livestock API
        Route::prefix('livestocks')->name('livestocks.')->group(function () {
            Route::get('/data', [LivestockController::class, 'getLivestocksData'])->name('data');
            Route::get('/{id}/detail', [LivestockController::class, 'getLivestockDetail'])
                ->where('id', '[0-9]+')
                ->name('detail');
            Route::post('/store', [LivestockController::class, 'storeLivestock'])->name('store');
            Route::post('/{id}/record-weight', [LivestockController::class, 'recordWeight'])
                ->where('id', '[0-9]+')
                ->name('record-weight');
            Route::post('/import', [LivestockController::class, 'importLivestocks'])->name('import');
        });

        // Pens API
        Route::prefix('pens')->name('pens.')->group(function () {
            Route::get('/data', [PenController::class, 'getPensData'])->name('data');
            Route::get('/{id}/detail', [PenController::class, 'getPenDetail'])
                ->where('id', '[0-9]+')
                ->name('detail');
            Route::get('/{id}/analytics', [PenController::class, 'getPenAnalytics'])
                ->where('id', '[0-9]+')
                ->name('analytics');
            Route::post('/store', [PenController::class, 'storePen'])->name('store');
            Route::post('/import', [PenController::class, 'importPens'])->name('import'); // tambahkan
        });

        // Feeds API
        Route::prefix('feeds')->name('feeds.')->group(function () {
            Route::get('/data', [FeedController::class, 'getFeedsData'])->name('data');
            Route::get('/stock-levels', [FeedController::class, 'getStockLevels'])->name('stock-levels');
            Route::get('/requirements', [FeedController::class, 'getFeedRequirements'])->name('requirements');
            Route::post('/record-feeding', [FeedController::class, 'recordFeeding'])->name('record-feeding');
            Route::post('/update-stock', [FeedController::class, 'updateStock'])->name('update-stock');
            Route::post('/store', [FeedController::class, 'storeFeed'])->name('store');
            Route::post('/import', [FeedController::class, 'importFeeds'])->name('import'); // tambahkan
        });

        // Predictions API
        Route::prefix('predictions')->name('predictions.')->group(function () {
            Route::get('/data', [PredictionController::class, 'getPredictionsData'])->name('data');
            Route::get('/history', [PredictionController::class, 'getPredictionHistory'])->name('history');
            Route::get('/correlation', [PredictionController::class, 'getCorrelationData'])->name('correlation');
            Route::post('/create', [PredictionController::class, 'createPrediction'])->name('create');
        });

        // Reports API
        Route::get('/reports/data', [ReportController::class, 'getReportsData'])->name('reports.data');
    });
// }); // AKHIR GRUP AUTH (DIKOMENTARI SEMENTARA)

/*
|--------------------------------------------------------------------------
| FALLBACK (404)
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return view('errors.404');
});
