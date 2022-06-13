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
        Schema::create('couche_instances', function (Blueprint $table) {
            $table->id();
            $table->integer('couche_id')->unsigned();
            $table->integer('instance_id')->unsigned();
            $table->string('opacite')->nullable();
            $table->string('qgis_url')->nullable();
            $table->string('bbox')->nullable();
            $table->string('projection')->nullable();
            $table->integer('number_features')->default(0);
            $table->integer('vues')->default(0);
            $table->string('surface')->nullable();
            $table->string('distance')->nullable();
            $table->integer('telechargement')->default(0);
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
        Schema::dropIfExists('couche_instances');
    }
};
