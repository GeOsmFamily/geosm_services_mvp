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
        Schema::create('instances', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('geom')->nullable();
            $table->boolean('mapillary')->default(true);
            $table->boolean('comparator')->default(true);
            $table->boolean('altimetrie')->default(false);
            $table->boolean('download')->default(true);
            $table->boolean('routing')->default(true);
            $table->string('app_version')->default('1.5.0');
            $table->string('app_github_url')->nullable();
            $table->string('app_email')->nullable();
            $table->string('app_whatsapp')->nullable();
            $table->string('app_facebook')->nullable();
            $table->string('app_twitter')->nullable();
            $table->text('mapillary_api_key')->nullable();
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
        Schema::dropIfExists('instances');
    }
};
