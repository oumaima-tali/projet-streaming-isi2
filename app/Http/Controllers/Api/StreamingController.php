<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Morceau;
use App\Models\Playlist;
use Illuminate\Http\Request;

class StreamingController extends Controller
{
    /**
     * Règle 1 : Musiques accessibles gratuitement
     */
    public function gratuites()
    {
        $morceaux = Morceau::with(['album.artiste', 'styles'])
                           ->where('est_gratuit', true)
                           ->get();

        return response()->json($morceaux);
    }

    /**
     * Règle 2 : Musiques payantes
     */
    public function payantes()
    {
        $morceaux = Morceau::with(['album.artiste', 'styles'])
                           ->where('est_gratuit', false)
                           ->get();

        return response()->json($morceaux);
    }
    
    /**
     * Action d'acheter un morceau
     */
    public function acheter(Request $request, $id)
    {
        $morceau = Morceau::findOrFail($id);
        $user = $request->user();

        // On vérifie si l'utilisateur possède déjà ce morceau
        if ($user->achats()->where('morceau_id', $id)->exists()) {
            return response()->json(['message' => 'Vous possédez déjà ce morceau'], 400);
        }

        // On enregistre l'achat avec le prix actuel figé pour la facture
        $user->achats()->attach($id, [
            'prix_facture' => $morceau->prix
        ]);

        return response()->json(['message' => 'Achat effectué avec succès !']);
    }

    /**
     * Règle 4 : Visualiser la facturation des achats
     */
    public function factures(Request $request)
    {
        // On récupère les achats et on formate la réponse pour n'afficher que la facture
        $factures = $request->user()->achats->map(function($morceau) {
            return [
                'morceau_id' => $morceau->id,
                'titre' => $morceau->titre,
                'prix_paye' => $morceau->pivot->prix_facture,
                'date_achat' => $morceau->pivot->date_achat,
            ];
        });

        return response()->json($factures);
    }

    /**
     * Règle 3 : Ajouter un morceau à une playlist (si acheté)
     */
    public function ajouterPlaylist(Request $request, $playlist_id, $morceau_id)
    {
        $user = $request->user();
        $playlist = Playlist::findOrFail($playlist_id);

        // 1. Vérifier que la playlist appartient bien à cet utilisateur
        if ($playlist->user_id !== $user->id) {
            return response()->json(['message' => 'Action non autorisée'], 403);
        }

        // 2. Vérifier que le morceau a bien été acheté ou qu'il est gratuit
        $morceau = Morceau::findOrFail($morceau_id);
        $estAchete = $user->achats()->where('morceau_id', $morceau_id)->exists();

        if (!$morceau->est_gratuit && !$estAchete) {
            return response()->json(['message' => 'Vous devez acheter ce morceau pour l\'ajouter à une playlist'], 403);
        }

        // 3. Ajouter à la playlist (sans faire de doublon)
        if (!$playlist->morceaux()->where('morceau_id', $morceau_id)->exists()) {
            $playlist->morceaux()->attach($morceau_id);
            return response()->json(['message' => 'Morceau ajouté à la playlist']);
        }

        return response()->json(['message' => 'Ce morceau est déjà dans la playlist'], 400);
    }

}