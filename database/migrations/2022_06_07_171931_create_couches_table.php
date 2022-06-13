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

            $table->string('identifiant')->nullable();


            $table->string('wms_type')->nullable();

            $table->string('logo')->nullable();

            $table->string('sql')->nullable();
            $table->string('condition')->nullable();
            $table->boolean('mode_sql')->default(false);
            $table->string('sql_complete')->nullable();
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
