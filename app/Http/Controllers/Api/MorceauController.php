<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Morceau;
use Illuminate\Http\Request;

class MorceauController extends Controller
{
    
    public function gratuites()
    {
        $morceaux = Morceau::with(['album.artiste', 'styles'])
                           ->where('est_gratuit', true)
                           ->get();

        return response()->json($morceaux);
    }

    
    public function payantes()
    {
        $morceaux = Morceau::with(['album.artiste', 'styles'])
                           ->where('est_gratuit', false)
                           ->get();

        return response()->json($morceaux);
    }
}