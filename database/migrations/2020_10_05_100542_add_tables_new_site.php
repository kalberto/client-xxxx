<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTablesNewSite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('log_administrador', function (Blueprint $table) {
		    $table->json('alteracoes')->nullable();
	    });
    	Schema::create('languages',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('name',50);
		    $table->string('locale',50)->nullable();
		    $table->boolean('default')->nullable();
	    });
	    Schema::create('produtos_site',function(Blueprint $table){
		    $table->increments('id');
		    $table->boolean('ativo')->nullable();
		    $table->boolean('externo')->nullable()->default(false);
		    $table->timestamps();
	    });
	    Schema::create('produtos_site_translation',function(Blueprint $table){
		    $table->increments('id');
		    $table->integer('owner_id')->unsigned();
		    $table->foreign('owner_id')->references('id')->on('produtos_site');
		    $table->integer('language_id')->unsigned();
		    $table->foreign('language_id')->references('id')->on('languages');

		    $table->string('title',255);
		    $table->string('url',255);
		    $table->string('sub_title',255)->nullable();
		    $table->string('link',255)->nullable();
		    $table->text('text_description_1')->nullable();
		    $table->text('text_description_2')->nullable();
		    $table->unique(['url', 'language_id']);
	    });
	    Schema::create('servicos_site', function (Blueprint $table){
		    $table->increments('id');
		    $table->boolean('ativo')->nullable();
		    $table->integer('media_id')->unsigned()->nullable();
		    $table->foreign('media_id')->references('id')->on('media');
		    $table->timestamps();

	    });
	    Schema::create('servicos_site_translation',function (Blueprint $table){
	    	$table->increments('id');
	    	$table->integer('owner_id')->unsigned();
	    	$table->foreign('owner_id')->references('id')->on('servicos_site');
		    $table->integer('language_id')->unsigned();
		    $table->foreign('language_id')->references('id')->on('languages');

		    $table->string('title',255);
		    $table->string('url',255);
		    $table->string('sub_title',255)->nullable();
		    $table->text('text_description_1')->nullable();
		    $table->text('text_description_2')->nullable();
		    $table->longText('benefits')->nullable();
		    $table->longText('differentials')->nullable();
		    $table->unique(['url', 'language_id']);
	    });
	    Schema::create('produtos_has_servicos_site',function (Blueprint $table){
		    $table->integer('produto_id')->unsigned();
		    $table->foreign('produto_id')->references('id')->on('produtos_site');
		    $table->integer('servico_id')->unsigned();
		    $table->foreign('servico_id')->references('id')->on('servicos_site');
	    });
	    Schema::create('servicos_site_has_servicos_site',function (Blueprint $table){
		    $table->integer('servico_id')->unsigned();
		    $table->foreign('servico_id')->references('id')->on('produtos_site');
		    $table->integer('servico_relacionado_id')->unsigned();
		    $table->foreign('servico_relacionado_id')->references('id')->on('servicos_site');
	    });
	    Schema::create('videos_site',function (Blueprint $table){
		    $table->increments('id');
		    $table->boolean('ativo')->nullable();
		    $table->integer('thumb_id')->unsigned()->nullable();
		    $table->foreign('thumb_id')->references('id')->on('media');
		    $table->integer('video_id')->unsigned()->nullable();
		    $table->foreign('video_id')->references('id')->on('media');
		    $table->timestamps();
	    });
	    Schema::create('videos_site_translation',function (Blueprint $table){
		    $table->increments('id');
		    $table->integer('owner_id')->unsigned();
		    $table->foreign('owner_id')->references('id')->on('videos_site');
		    $table->integer('language_id')->unsigned();
		    $table->foreign('language_id')->references('id')->on('languages');

		    $table->string('title',255);
		    $table->text('text_description')->nullable();
	    });
	    Schema::create('servicos_has_videos_site',function (Blueprint $table){
		    $table->integer('servico_id')->unsigned();
		    $table->foreign('servico_id')->references('id')->on('servicos_site');
		    $table->integer('video_id')->unsigned();
		    $table->foreign('video_id')->references('id')->on('videos_site');
	    });
	    Schema::create('produtos_has_videos_site',function (Blueprint $table){
		    $table->integer('produto_id')->unsigned();
		    $table->foreign('produto_id')->references('id')->on('produtos_site');
		    $table->integer('video_id')->unsigned();
		    $table->foreign('video_id')->references('id')->on('videos_site');
	    });
	    Schema::create('paginas',function (Blueprint $table){
		    $table->increments('id');
		    $table->integer('video_id')->unsigned()->nullable();
		    $table->foreign('video_id')->references('id')->on('media');
		    $table->timestamps();
	    });
	    Schema::create('paginas_has_videos_site',function (Blueprint $table){
		    $table->integer('pagina_id')->unsigned();
		    $table->foreign('pagina_id')->references('id')->on('paginas');
		    $table->integer('video_id')->unsigned();
		    $table->foreign('video_id')->references('id')->on('videos_site');
	    });
	    Schema::create('paginas_translation',function (Blueprint $table){
		    $table->increments('id');
		    $table->integer('owner_id')->unsigned();
		    $table->foreign('owner_id')->references('id')->on('paginas');
		    $table->integer('language_id')->unsigned();
		    $table->foreign('language_id')->references('id')->on('languages');

		    $table->string('title',255);
		    $table->string('url',255);
		    $table->string('sub_title',255)->nullable();
		    $table->longText('text_1')->nullable();
		    $table->longText('text_2')->nullable();
		    $table->unique(['url', 'language_id']);
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('paginas_translation');
	    Schema::dropIfExists('paginas_has_videos_site');
	    Schema::dropIfExists('paginas');
	    Schema::dropIfExists('servicos_has_videos_site');
	    Schema::dropIfExists('produtos_has_videos_site');
	    Schema::dropIfExists('videos_site_translation');
	    Schema::dropIfExists('videos_site');
	    Schema::dropIfExists('servicos_site_has_servicos_site');
	    Schema::dropIfExists('produtos_has_servicos_site');
	    Schema::dropIfExists('servicos_site_translation');
	    Schema::dropIfExists('servicos_site');
	    Schema::dropIfExists('produtos_site_translation');
	    Schema::dropIfExists('produtos_site');
    	Schema::dropIfExists('languages');
	    Schema::table('log_administrador', function (Blueprint $table) {
		    $table->dropColumn('alteracoes');
	    });
    }
}
