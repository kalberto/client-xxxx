<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCnpjDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('cnpj', function (Blueprint $table) {
		    $table->renameColumn('cnpj', 'documento');
	    });
        Schema::rename('cnpj','documento');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::rename('documento','cnpj');
	    Schema::table('cnpj', function (Blueprint $table) {
		    $table->renameColumn('documento', 'cnpj');
	    });
    }
}
