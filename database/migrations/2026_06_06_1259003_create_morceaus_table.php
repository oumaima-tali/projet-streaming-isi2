<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
    {
        Schema::create('morceaus', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->integer('duree'); // Durée en secondes
            $table->decimal('prix', 8, 2);
            $table->boolean('est_gratuit')->default(false);
            $table->foreignId('album_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('morceaus');
    }
};
