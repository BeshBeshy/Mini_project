<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SubCategoryController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function (){
    Route::post('login', 'login');
    Route::post('register', 'register');
});


Route::middleware(['auth', 'authorize:admin'])->group(function () {

    Route::post('createCategory',       [CategoryController::class,     'CreateCategory']);
    Route::post('createSubCategory',    [SubCategoryController::class,  'CreateSubCategory']);
    Route::post('createTransaction',    [TransactionController::class,  'CreateTransaction']);
    Route::get('viewAllTransactions',   [TransactionController::class,  'ViewAllTransactions']);
    Route::Post('createPayment',        [PaymentController::class,  'CreatePayment']);
    Route::get('viewAllPayments',       [PaymentController::class,  'ViewAllPayments']);
    Route::post('viewTransactionPayments',   [PaymentController::class,  'ViewTransactionPayments']);
    Route::post('BasicReport',   [ReportController::class,  'CreateBasicReport']);
    Route::get('MonthlyReport',   [ReportController::class,  'MonthlyReport']);

});

Route::middleware(['auth'])->group(function () {

    Route::get('getTransactions',   [TransactionController::class,  'ShowTransactions']);

});
