<?php

use App\Http\Controllers\FinanceController;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance');
    Route::get('/finance/list', [FinanceController::class, 'list'])->name('finance.list');
    Route::get('/finance/{id}/edit', [FinanceController::class, 'edit'])->name('finance.edit');
    Route::put('/finance/{id}', [FinanceController::class, 'update'])->name('finance-update');
    Route::get('/finance/not-interested', [FinanceController::class, 'notInterested'])->name('finance.not-interested');
    Route::get('/finance/not-interested/list', [FinanceController::class, 'notInterestedList'])->name('finance.not-interested.list');
    Route::get('/finance/payout', [FinanceController::class, 'payout'])->name('finance.payout');
    Route::get('/finance/payout/list', [FinanceController::class, 'payoutList'])->name('finance.payout.list');
    Route::get('/finance/payout-edit/{id}', [FinanceController::class, 'payoutedit'])->name('finance.payoutedit');


    Route::put('/payout-update/{booking}', [FinanceController::class, 'payoutupdate'])->name('payout-update');



    Route::get('/finance/payout-completed', [FinanceController::class, 'payoutcompleted'])->name('finance.payoutcompleted');
    Route::get('/finance/payout-completed/list', [FinanceController::class, 'payoutcompletedList'])->name('finance.payoutcompleted.list');
    Route::get('/finance/view/{id}', [FinanceController::class, 'view'])->name('finance.view');
    Route::get('/finance/retail', [FinanceController::class, 'retail'])->name('finance.retail');
    Route::get('/finance/retail/list', [FinanceController::class, 'retailList'])->name('finance.retail.list');
    Route::get('/finance/retail-edit/{id}', [FinanceController::class, 'retailedit'])->name('finance.retailedit');

    Route::get('/finance/retailed', [FinanceController::class, 'retailed'])->name('finance.retailed');
    Route::get('/finance/retailed/list', [FinanceController::class, 'retailedList'])->name('finance.retailed.list');
});
