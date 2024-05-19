<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureUser;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'Expense Tracker',
    ]);
});

Route::get('/get-expense-data', [ExpenseController::class, 'getExpenseData']);
Route::get('/get-category/{month}/{year}', [ExpenseController::class, 'getCategory']);
Route::post('/login', [UserController::class, 'login'])->middleware(EnsureUser::class);
Route::post('/setup-budget', [ExpenseController::class, 'setupBudget']);
Route::post('/update-budget/{month}/{year}', [ExpenseController::class, 'updateBudget']);
