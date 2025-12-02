<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController; // Importar el controlador de tickets
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| welcome route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

// user redirect based on role
Route::get('/dashboard', function () {
    // agent?
    if (auth()->check()) {
        if (auth()->user()->role === 'agent') {
            return redirect()->route('agent.dashboard');
        }
        return redirect()->route('user.dashboard');
    }
    // user not login
    return redirect('/login');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| agent routes (Role: agent)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:agent'])->group(function () {
    // Dashboard
    Route::get('/agent/dashboard', [TicketController::class, 'agentIndex'])->name('agent.dashboard');

    // crud
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::patch('/tickets/{ticket}/request-document', [TicketController::class, 'requestDocument'])->name('tickets.requestDocument');
    Route::patch('/tickets/{ticket}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
});

/*
|--------------------------------------------------------------------------
| user routes (Role: user)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    // Dashboard
    Route::get('/user/dashboard', [TicketController::class, 'userIndex'])->name('user.dashboard');

    // crud
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');

    // user documentation
    Route::post('/tickets/{ticket}/upload-document', [TicketController::class, 'uploadDocument'])->name('tickets.upload');
});


/*
|--------------------------------------------------------------------------
| both profiles
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
