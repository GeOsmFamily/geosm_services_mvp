<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cartes', function (Blueprint $table) {
            $table->id();
            $table->integer('groupe_carte_id')->unsigned();
            $table->string('nom');
            $table->string('url');
            $table->string('image_url')->nullable();
            $table->string('type')->nullable();
            $table->string('identifiant')->nullable();
            $table->string('bbox')->nullable();
            $table->string('projection')->nullable();
            $table->string('zmax')->nullable();
            $table->string('zmin')->nullable();
            $table->string('commentaire')->nullable();
            $table->boolean('principal')->default(false);
            $table->integer('vues')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cartes');
    }
};
