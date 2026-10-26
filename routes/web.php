<?php

use App\Http\Controllers\web\User\UserController;
use App\Http\Controllers\web\User\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\SigninController;
use App\Http\Controllers\web\AdminController;
use App\Http\Controllers\web\MenuTreeController;
use App\Http\Controllers\web\MenuController;
use App\Http\Controllers\web\UnitKerjaController;
use App\Http\Controllers\web\setting\IndikatorController;
use App\Http\Controllers\web\setting\IntervalController;
use App\Http\Controllers\web\setting\LokasiController;
use App\Http\Controllers\web\setting\UnitKerjaAktifController;
use App\Http\Controllers\web\setting\HistoryController;
use App\Http\Controllers\web\monitoring\LogController;



// Route::get('/', function () {
//     return view('dashboard');
// });

//clear & recreate api Routes
Route::get('/clear', function () {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:cache');

    return "Cleared!";
});

// file upload 
// tambahkan route baru
Route::get('/files', [App\Http\Controllers\web\FileController::class, 'index'])
    ->name('files.index');

Route::get('/files/create', [App\Http\Controllers\web\FileController::class, 'create'])
    ->name('files.create');

Route::post('/files/store', [App\Http\Controllers\web\FileController::class, 'store'])
    ->name('files.store');

Route::get('/files/{file}/download', [App\Http\Controllers\web\FileController::class, 'download'])
    ->name('files.download');
// ------

Route::get('/', [AdminController::class, 'index']);
Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard.index');
Route::get('/getSoundfile', [AdminController::class, 'getSoundfile'])->name('getSoundfile');
Route::get('/getRegionalList', [UnitKerjaController::class, 'getRegionalList'])->name('getRegionalList');
Route::get('/playSoundLokasi', [AdminController::class, 'playSoundLokasi'])->name('playSoundLokasi');
Route::get('/gettag', [AdminController::class, 'getTagname'])->name('gettag');
Route::get('/download', [AdminController::class, 'getDownload'])->name('download');
Route::post('/storefile', [AdminController::class, 'storefile'])->name('storefile');
Route::get('/playSoundData', [AdminController::class, 'playSoundData'])->name('playSoundData');
Route::get('/testsize', [LokasiController::class, 'testSize'])->name('testsize');



// Route::get('/storefile', function () {
//     return csrf_token(); 
// });

Route::get('/signin', [SigninController::class, 'index'])->name('login');
Route::post('/signin', [SigninController::class, 'actionlogin']);
Route::get('/logout', [SigninController::class, 'actionlogout']);

Route::get('/GenRolePermision', [RoleController::class, 'GenRolePermision']);


// Route::get('/users', [UserController::class, 'index']);
Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('menu', MenuController::class);
    Route::resource('units', UnitKerjaController::class);
    Route::resource('indikator', IndikatorController::class);
    Route::resource('interval', IntervalController::class);
    Route::resource('lokasi', LokasiController::class);
    Route::resource('server', UnitKerjaAktifController::class);
    Route::resource('history', HistoryController::class);
    Route::resource('log', LogController::class);
 
    Route::get('/UserDtable', [UserController::class, 'showUserDatatable']);
    
    Route::get('/getrolemenudata', [MenuTreeController::class, 'getrolemenudata']);
    Route::get('/getrolemenu', [MenuTreeController::class, 'getrolemenu']);
    Route::post('/setmenurole', [MenuTreeController::class, 'setmenurole']);
    
    Route::get('menudata', [MenuController::class, 'data'])->name('menu.data');

    Route::get('/getdtlhist', [HistoryController::class, 'GetDetailHistory']);
    Route::get('/refreshgrid', [HistoryController::class, 'refreshgrid']);
    Route::get('/get_hist_datatable', [HistoryController::class, 'get_datatable']);
    Route::get('/get_log_datatable', [LogController::class, 'get_datatable']);
    Route::get('/get_unit_datatable', [UnitKerjaAktifController::class, 'get_datatable']);
    
    Route::get('/getlogdtlhist', [LogController::class, 'GetDetaillogHistory']);
    Route::get('/getdetailunit', [UnitKerjaAktifController::class, 'GetDetailUnit']);
    Route::get('/refreshgridUnit', [UnitKerjaAktifController::class, 'refreshgrid']);


    Route::get('/getnotif', [AdminController::class, 'getNotif'])->name('notif.getnotif');
    Route::get('/shownotif', [AdminController::class, 'showNotif'])->name('notif.index');

});
