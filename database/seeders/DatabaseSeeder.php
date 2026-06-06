<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Artiste;
use App\Models\Album;
use App\Models\Morceau;
use App\Models\Style;
use App\Models\Playlist;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Créer un utilisateur de test pour tester l'API plus tard
        $user = User::factory()->create([
            'name' => 'Utilisateur Test',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // Le mot de passe sera "password"
        ]);

        // 2. Créer 5 styles de musique
        $styles = Style::factory(5)->create();

        // 3. Créer 5 artistes
        Artiste::factory(5)->create()->each(function ($artiste) use ($styles) {
            
            // Pour chaque artiste, on crée 2 albums
            Album::factory(2)->create(['artiste_id' => $artiste->id])->each(function ($album) use ($styles) {
                
                // Pour chaque album, on crée 5 morceaux
                Morceau::factory(5)->create(['album_id' => $album->id])->each(function ($morceau) use ($styles) {
                    
                    // On attache 1 ou 2 styles aléatoires à chaque morceau
                    $morceau->styles()->attach(
                        $styles->random(rand(1, 2))->pluck('id')->toArray()
                    );
                });
            });
        });

        // 4. Créer une playlist pour notre utilisateur de test
        $playlist = Playlist::create([
            'nom' => 'Ma Super Playlist',
            'user_id' => $user->id,
        ]);

        // On ajoute 3 morceaux aléatoires à cette playlist
        $morceauxAleatoires = Morceau::inRandomOrder()->take(3)->pluck('id');
        $playlist->morceaux()->attach($morceauxAleatoires);
    }
}