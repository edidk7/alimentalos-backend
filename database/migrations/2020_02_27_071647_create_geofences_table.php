<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeofencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geofences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->unique()->index();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('photo_id')->unsigned()->index()->nullable();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->polygon('shape')->nullable();
            $table->boolean('is_public')->default(true);
            $table->text('photo_url')->nullable();
            $table->timestamps();
        });

        Schema::create('geofenceables', function (Blueprint $table) {
            $table->bigInteger('geofence_id')->index()->unsigned();
            $table->morphs('geofenceable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geofences');
        Schema::dropIfExists('geofenceables');
    }
}