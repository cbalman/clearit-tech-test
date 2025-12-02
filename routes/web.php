<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Welcome Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard Redirect Logic
|--------------------------------------------------------------------------
| Redirects authenticated users to their respective dashboards based on their role.
*/

Route::get('/dashboard', function () {
    // Check if user is logged in
    if (auth()->check()) {
        // Redirect Agent
        if (auth()->user()->role === 'agent') {
            return redirect()->route('agent.dashboard');
        }
        // Redirect standard User
        return redirect()->route('user.dashboard');
    }
    // Fallback: Redirect to login if somehow reached without 'auth' middleware
    return redirect('/login');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| SHARED TICKET ROUTES (Authentication Only)
|--------------------------------------------------------------------------
| Accessible by both 'user' and 'agent'. Authorization logic (who can see which ticket)
| is handled inside the TicketController methods.
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // User Dashboard (displays user's own tickets)
    Route::get('/user/dashboard', [TicketController::class, 'userIndex'])->name('user.dashboard');

    // 1. FIXED SEGMENT ROUTES (MUST COME BEFORE PARAMETER ROUTES to avoid conflict with {ticket})

    // Ticket Creation form
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    // Store new ticket
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');

    // 2. PARAMETER ROUTES

    // View Ticket Details (Shared by User/Agent, authorization handled in controller)
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

    // Document Upload (Shared by User/Agent)
    Route::post('/tickets/{ticket}/upload-document', [TicketController::class, 'uploadDocument'])->name('tickets.upload');
});


/*
|--------------------------------------------------------------------------
| AGENT-SPECIFIC ROUTES (Requires role:agent Middleware)
|--------------------------------------------------------------------------
| These actions are only for Agents.
*/

Route::middleware(['auth', 'verified', 'role:agent'])->group(function () {

    // Agent Dashboard (displays all open tickets)
    Route::get('/agent/dashboard', [TicketController::class, 'agentIndex'])->name('agent.dashboard');

    // Agent Actions
    // Request additional documentation from the user
    Route::patch('/tickets/{ticket}/request-document', [TicketController::class, 'requestDocument'])->name('tickets.requestDocument');
    // Update the ticket status (in_progress, completed)
    Route::patch('/tickets/{ticket}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
});


/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
| Accessible by both roles.
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
