<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected $connection = 'production';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->mediumText('comentario')->nullable();
            $table->integer('avaliacao')->nullable();
        });

        Schema::table('historico_atendimentos', function (Blueprint $table) {
            $table->mediumText('comentario')->nullable();
            $table->integer('avaliacao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('atendimentos', ['comentario', 'avaliacao']);
        Schema::dropColumns('historico_atendimentos', ['comentario', 'avaliacao']);
    }
};
