<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_networks', function (Blueprint $table) {
            $table->id(); // Colonne d'auto-incrémentation pour l'ID
            $table->text('icon_html'); // Code HTML de l'icône du réseau social
            $table->string('link'); // Lien vers le profil sur le réseau social
            $table->timestamps(); // Champs de date de création et de mise à jour automatiques
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_networks');
    }
}
