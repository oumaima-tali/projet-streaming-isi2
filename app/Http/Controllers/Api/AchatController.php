<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Morceau;
use Illuminate\Http\Request;

class AchatController extends Controller
{
    
    public function acheter(Request $request, $id)
    {
        $morceau = Morceau::findOrFail($id);
        $user = $request->user();

        if ($user->achats()->where('morceau_id', $id)->exists()) {
            return response()->json(['message' => 'Vous possédez déjà ce morceau'], 400);
        }

        $user->achats()->attach($id, [
            'prix_facture' => $morceau->prix
        ]);

        return response()->json(['message' => 'Achat effectué avec succès !']);
    }

    
    public function factures(Request $request)
    {
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
}