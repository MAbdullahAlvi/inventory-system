<?php

use App\Http\Controllers\Inventory\SaveruleController;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Inventory\PricingController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Inventory\TransactionController;

Route::prefix('inventory')->group(function () {

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/products/{product}/price', [PricingController::class, 'calculatePrice']);

    Route::post('/inventory/update', [InventoryController::class, 'update']);
    Route::post('/saverules', [SaveruleController::class, 'store']);

    Route::post('/transactions/process', [TransactionController::class, 'process']);

    Route::get('/audit-logs', function () {
        return response()->json([
            'logs' => AuditLog::with('transaction.product')
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ]);
    });
});
