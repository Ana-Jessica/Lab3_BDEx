<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id('id_solicitacao');
            $table->foreignId('id_desenvolvedor')->constrained('developers', 'id_desenvolvedor')->onDelete('cascade');
            $table->foreignId('id_vaga')->constrained('opportunities', 'id_vaga')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('requests');
    }
};