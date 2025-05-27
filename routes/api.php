<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserStatusController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookTransactionController;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){

    //USER
    Route::get('/get-users', [UserController::class, 'getUsers']);
    Route::post('/add-user', [UserController::class, 'addUser']);
    Route::put('/edit-user/{id}', [UserController::class, 'editUser']);
    Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser']);
    
    //ROLE
    Route::get('/get-roles', [RoleController::class, 'getRoles']);
    Route::post('/add-role', [RoleController::class, 'addRole']);
    Route::put('/edit-role/{id}', [RoleController::class, 'editRole']);
    Route::delete('/delete-role/{id}', [RoleController::class, 'deleteRole']);

    // USER STATUS
    Route::get('/get-user-statuses', [UserStatusController::class, 'getUserStatuses']);
    Route::post('/add-user-status', [UserStatusController::class, 'addUserStatus']);
    Route::put('/edit-user-status/{id}', [UserStatusController::class, 'editUserStatus']);
    Route::delete('/delete-user-status/{id}', [UserStatusController::class, 'deleteUserStatus']);

    // BOOK ROUTES
    Route::get('/get-books', [BookController::class, 'getBooks']);
    Route::post('/add-book', [BookController::class, 'addBook']);
    Route::put('/edit-book/{id}', [BookController::class, 'editBook']);
    Route::delete('/delete-book/{id}', [BookController::class, 'deleteBook']);

    // BOOK TRANSACTION ROUTES
    Route::get('/get-transactions', [BookTransactionController::class, 'getTransactions']);
    Route::post('/add-transaction', [BookTransactionController::class, 'addTransaction']);
    Route::put('/edit-transaction/{id}', [BookTransactionController::class, 'editTransaction']);
    Route::delete('/delete-transaction/{id}', [BookTransactionController::class, 'deleteTransaction']);


    Route::post('/logout', [AuthenticationController::class, 'logout']);


});