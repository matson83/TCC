<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranscricoesTable extends Migration
{
    public function up()
    {
        Schema::create('transcricoes', function (Blueprint $table) {
            $table->id();
            $table->string('caminho_audio');
            $table->text('transcricao_ajustada');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transcricoes');
    }
}
