<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Morceau;
use App\Models\Playlist;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function index(Request $request)
    {
        $playlists = $request->user()
                             ->playlists()
                             ->with('morceaux.album.artiste')
                             ->get();

        return response()->json($playlists);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $playlist = $request->user()->playlists()->create([
            'nom' => $request->nom,
        ]);

        return response()->json($playlist, 201);
    }

    public function ajouterPlaylist(Request $request, $playlist_id, $morceau_id)
    {
        $user = $request->user();
        $playlist = Playlist::findOrFail($playlist_id);

        if ($playlist->user_id !== $user->id) {
            return response()->json(['message' => 'Action non autorisée'], 403);
        }

        $morceau = Morceau::findOrFail($morceau_id);
        $estAchete = $user->achats()->where('morceau_id', $morceau_id)->exists();

        if (!$morceau->est_gratuit && !$estAchete) {
            return response()->json(['message' => 'Vous devez acheter ce morceau pour l\'ajouter à une playlist'], 403);
        }

        if (!$playlist->morceaux()->where('morceau_id', $morceau_id)->exists()) {
            $playlist->morceaux()->attach($morceau_id);
            return response()->json(['message' => 'Morceau ajouté à la playlist']);
        }

        return response()->json(['message' => 'Ce morceau est déjà dans la playlist'], 400);
    }
}