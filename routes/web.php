<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(
    ['middleware' => ['verify.shopify', 'billable']],
    function () {

        Route::get('/', [SettingController::class, 'index'])
            ->name('home');

        Route::get('/general-settings', [SettingController::class, 'index'])
            ->name('general.settings');

        Route::post('/general-settings-save', [SettingController::class, 'save'])
            ->name('general.settings.save');

        Route::get('/rules-index', [RuleController::class, 'index'])->name('rules.index');

        Route::get('/rules-create', [RuleController::class, 'create'])->name('rules.create');

        Route::get('/product-get', [RuleController::class, 'getProducts'])->name('product.get');

        Route::post('/rules-save', [RuleController::class, 'save'])->name('rules.save');

        Route::get('/rule-delete', [RuleController::class, 'destroy'])->name('rule.delete');

        Route::get('/rule-edit/{rule}', [RuleController::class, 'edit'])->name('rules.edit');

        Route::get('/rule-search', [RuleController::class, 'ruleSearch'])->name('rule.search');

        Route::get('/rule-pagination', [RuleController::class, 'pagination'])->name('rule.pagination');

        Route::get('/customers-index', [CustomerController::class, 'index'])->name('customers.index');

        Route::get('/customer-search', [CustomerController::class, 'search'])->name('customers.search');

        Route::get('/change-status/{id}', [CustomerController::class, 'changeStatus'])->name('change.status');

        Route::get('/export-csv-of-customers', [CustomerController::class, 'exportCsv'])->name('export.csv.of.customers');

        Route::get('/export-csv-of-rule', [RuleController::class, 'exportCsv'])->name('export.csv.of.rules');
    }
);
