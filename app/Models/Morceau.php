<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Morceau extends Model
{
    /** @use HasFactory<\Database\Factories\MorceauFactory> */
    use HasFactory;

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function styles()
    {
        return $this->belongsToMany(Style::class);
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class);
    }
}
