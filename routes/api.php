<?php

use App\Http\Controllers\Api\MorceauController;
use App\Http\Controllers\Api\PlaylistController;
use App\Http\Controllers\Api\AchatController;
use App\Http\Controllers\Api\AuthController;   

// Routes publiques
Route::get('/musiques/gratuites', [MorceauController::class, 'gratuites']);
Route::post('/login', [AuthController::class, 'login']);   
// Routes protégées par Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/musiques/payantes', [MorceauController::class, 'payantes']);
    Route::post('/morceaux/{id}/acheter', [AchatController::class, 'acheter']);
    Route::get('/factures', [AchatController::class, 'factures']);

    Route::get('/playlists', [PlaylistController::class, 'index']);     
    Route::post('/playlists', [PlaylistController::class, 'store']);    

    Route::post('/playlists/{playlist_id}/morceaux/{morceau_id}', [PlaylistController::class, 'ajouterPlaylist']);
});