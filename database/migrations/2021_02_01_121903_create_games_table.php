<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->integer('rating')->nullable();
            $table->integer('metacritic_rating')->nullable();
            $table->json('metadata')->nullable();
            $table->date('released_at')->nullable();
            $table->smallInteger('sync_status')->default(0);

            $table->timestamps();

            $table->index(['released_at'], 'released_at');
            $table->index(['rating'], 'rating');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
