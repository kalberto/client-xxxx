<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldOnServicosSite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('servicos_site',function (Blueprint $table){
		    $table->boolean('externo')->nullable()->default(false)->after('ativo');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servicos_site',function (Blueprint $table){
		    $table->boolean('externo');
	    });
    }
}
