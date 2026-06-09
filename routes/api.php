<?php

use App\Http\Controllers\Api\MorceauController;
use App\Http\Controllers\Api\PlaylistController;
use App\Http\Controllers\Api\AchatController;

// Routes publiques
Route::get('/musiques/gratuites', [MorceauController::class, 'gratuites']);

// Routes protégées par Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/musiques/payantes', [MorceauController::class, 'payantes']);
    Route::post('/morceaux/{id}/acheter', [AchatController::class, 'acheter']);
    Route::get('/factures', [AchatController::class, 'factures']);
    Route::post('/playlists/{playlist_id}/morceaux/{morceau_id}', [PlaylistController::class, 'ajouterPlaylist']);
});