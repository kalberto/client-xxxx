<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$modulos = [
    		[
			    'nome' => 'Produtos Site',
			    'icone' => 'dashboard',
			    'modulo_list' => 'painel.produtos-site.listar',
			    'modulo_url' => 'painel.produtos-site',
			    'order' => 6
		    ],
		    [
			    'nome' => 'Serviços Site',
			    'icone' => 'dashboard',
			    'modulo_list' => 'painel.servicos-site.listar',
			    'modulo_url' => 'painel.servicos-site',
			    'order' => 7
		    ],
		    [
			    'nome' => 'Vídeos Site',
			    'icone' => 'dashboard',
			    'modulo_list' => 'painel.videos-site.listar',
			    'modulo_url' => 'painel.videos-site',
			    'order' => 8
		    ],
		    [
			    'nome' => 'Páginas Site',
			    'icone' => 'dashboard',
			    'modulo_list' => 'painel.paginas-site.listar',
			    'modulo_url' => 'painel.paginas-site',
			    'order' => 9
		    ],
		    [
			    'nome' => 'Seja um parceiro',
			    'icone' => 'mail_outline',
			    'modulo_list' => 'painel.seja-parceiro.listar',
			    'modulo_url' => 'painel.seja-parceiro',
			    'order' => 10
		    ],
	    ];
    	foreach ($modulos as $modulo){
    		if(DB::table('modulo_gerenciador')->where('modulo_url','=',$modulo['modulo_url'])->count() == 0){
			    DB::table('modulo_gerenciador')->insert($modulo);
		    }
	    }
        $languages = [
        	['name' => 'Portugês Brazil','locale' => 'pt-br','default' => true,],
        	['name' => 'British English','locale' => 'en-gb','default' => false,],
        ];
        foreach ($languages as $language){
        	if(DB::table('languages')->where('locale','=',$language['locale'])->count() == 0){
		        DB::table('languages')->insert($language);
	        }
        }
        $medias_root = [
        	[
        		'alias' => 'servicos-site',
		        'path' => 'upload/media/servicos-site/'
	        ],
	        [
		        'alias' => 'videos-site-thumb',
		        'path' => 'upload/media/videos-site/thumb/'
	        ],
	        [
		        'alias' => 'videos-site',
		        'path' => 'upload/media/videos-site/'
	        ],
        ];
        foreach ($medias_root as $item){
        	if(DB::table('media_root')->where('alias','=',$item['alias'])->count() == 0){
        		DB::table('media_root')->insert($item);
	        }
        }

        $paginas = [
        ];
	    foreach ($paginas as $item){
		    if(DB::table('paginas')->where('id','=',$item['id'])->count() == 0){
		    	\App\Models\Site\Paginas::store($item,'127.0.0.1');
		    }
	    }

	    if(DB::table('paginas_translation')->where('url','=','ceo')->count() >= 1){
	    	$pages = \App\Models\Site\Translations\PaginasTranslations::where('url','=','ceo')->get();
	    	foreach ($pages as $page){
			    $page->url = 'haroldojacobovicz';
			    $page->save();
		    }
	    }

	    $servicos = [
	    ];

	    foreach ($servicos as $item){
		    if(DB::table('servicos_site')->where('id','=',$item['id'])->count() == 0){
			    \App\Models\Site\Servicos::store($item,'127.0.0.1');
		    }
	    }

	    $produtos = [
	    ];

	    foreach ($produtos as $item){
		    if(DB::table('produtos_site')->where('id','=',$item['id'])->count() == 0){
			    \App\Models\Site\Produtos::store($item,'127.0.0.1');
		    }
	    }

	    $estados = [
		    [
			    'nome' => 'Acre',
			    'uf' => 'AC'
		    ],[
			    'nome' => 'Alagoas',
			    'uf' => 'AL'
		    ],[
			    'nome' => 'Amapá',
			    'uf' => 'AP'
		    ],[
			    'nome' => 'Amazonas',
			    'uf' => 'AM'
		    ],[
			    'nome' => 'Bahia',
			    'uf' => 'BA'
		    ],[
			    'nome' => 'Ceará',
			    'uf' => 'CE'
		    ],[
			    'nome' => 'Distrito Federal',
			    'uf' => 'DF'
		    ],[
			    'nome' => 'Espírito Santo',
			    'uf' => 'ES'
		    ],[
			    'nome' => 'Goiás',
			    'uf' => 'GO'
		    ],[
			    'nome' => 'Maranhão',
			    'uf' => 'MA'
		    ],[
			    'nome' => 'Mato Grosso',
			    'uf' => 'MT'
		    ],[
			    'nome' => 'Mato Grosso do Sul',
			    'uf' => 'MS'
		    ],[
			    'nome' => 'Minas Gerais',
			    'uf' => 'MG'
		    ],[
			    'nome' => 'Pará',
			    'uf' => 'PA'
		    ],[
			    'nome' => 'Paraíba',
			    'uf' => 'PB'
		    ],[
			    'nome' => 'Paraná',
			    'uf' => 'PR'
		    ],[
			    'nome' => 'Pernambuco',
			    'uf' => 'PE'
		    ],[
			    'nome' => 'Piauí',
			    'uf' => 'PI'
		    ],[
			    'nome' => 'Rio de Janeiro',
			    'uf' => 'RJ'
		    ],[
			    'nome' => 'Rio Grande do Norte',
			    'uf' => 'RN'
		    ],[
			    'nome' => 'Rio Grande do Sul',
			    'uf' => 'RS'
		    ],[
			    'nome' => 'Rondônia',
			    'uf' => 'RO'
		    ],[
			    'nome' => 'Roraima',
			    'uf' => 'RR'
		    ],[
			    'nome' => 'Santa Catarina',
			    'uf' => 'SC'
		    ],[
			    'nome' => 'São Paulo',
			    'uf' => 'SP'
		    ],[
			    'nome' => 'Sergipe',
			    'uf' => 'SE'
		    ],[
			    'nome' => 'Tocantins',
			    'uf' => 'TO'
		    ],
	    ];

	    foreach ($estados as $item){
		    if(DB::table('estados')->where('uf','=',$item['uf'])->count() == 0){
			    DB::table('estados')->insert($item);
		    }
	    }
    }
}
