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
        Schema::create('couches', function (Blueprint $table) {
            $table->id();
            $table->integer('sous_thematique_id')->unsigned();
            $table->string('nom');
            $table->string('nom_en');
            $table->string('geometry')->nullable();
            $table->string('schema_table_name')->nullable();
            $table->string('remplir_color')->default('#808080');
            $table->string('contour_color')->default('#808080');
            $table->string('service_carto')->nullable();
            $table->text('identifiant')->nullable();
            $table->string('wms_type')->nullable();
            $table->string('logo')->nullable();
            $table->text('sql')->nullable();
            $table->string('condition')->nullable();
            $table->boolean('mode_sql')->default(false);
            $table->string('sql_complete')->nullable();
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
        Schema::dropIfExists('couches');
    }
};
