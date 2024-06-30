<?php

use App\Http\Controllers\ExplorerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemsController;
use Illuminate\Support\Facades\Route;

Route::get('/explorers/{id}', [ExplorerController::class, 'show']);
Route::get('/explorers', [ExplorerController::class, 'index']);
Route::get('/items/{id}', [ItemsController::class, 'show']);
Route::get('/items', [ItemsController::class, 'index']);
Route::get('/explorers/{id}/inventory', [InventoryController::class, 'explorerInventory']);
Route::get('/inventory', [InventoryController::class, 'index']);
Route::post('/explorers', [ExplorerController::class, 'store']);
Route::post('/items', [ItemsController::class, 'store']);
Route::post('/explorers/inventory', [InventoryController::class, 'store']);
Route::patch('/explorers/{id}', [ExplorerController::class, 'update']);
Route::post('/explorers/switch', [InventoryController::class, 'trocarItens']);
