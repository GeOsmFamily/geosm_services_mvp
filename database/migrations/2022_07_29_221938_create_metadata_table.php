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
        Schema::create('metadata', function (Blueprint $table) {
            $table->id();
            $table->integer('carte_id')->nullable();
            $table->integer('couche_id')->nullable();
            $table->text('resume')->nullable();
            $table->text('description')->nullable();
            $table->string('zone')->nullable();
            $table->string('epsg')->nullable();
            $table->string('langue')->nullable();
            $table->string('echelle')->nullable();
            $table->string('licence')->nullable();
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
        Schema::dropIfExists('metadata');
    }
};
