<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StreamingController;
use App\Http\Controllers\Api\AuthController;

// Route publique pour se connecter
Route::post('/login', [AuthController::class, 'login']);

// Routes publiques
Route::get('/musiques/gratuites', [StreamingController::class, 'gratuites']);

// Routes protégées par Sanctum (il faut un token valide pour y accéder)
Route::middleware('auth:sanctum')->group(function () {
    // Liste des musiques payantes
    Route::get('/musiques/payantes', [StreamingController::class, 'payantes']);
    
    // Actions de l'utilisateur
    Route::post('/morceaux/{id}/acheter', [StreamingController::class, 'acheter']);
    Route::get('/compte/factures', [StreamingController::class, 'factures']);
    Route::post('/playlists/{playlist_id}/ajouter/{morceau_id}', [StreamingController::class, 'ajouterPlaylist']);
});