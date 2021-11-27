<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// Route::get('/', function () {
//     return view('site/home');
// });
// Route::get('/home', function () {
// 	return view('site/home');
// });
// Route::get('/empresa',function (){
// 	return view('site/empresa');
// });

// Route::get('produtos-corporativos/{slug?}', function($slug = null) {
// 	if(isset($slug))
// 		return view('site/produtos/' . $slug);
// 	return view('site/produtos/produtos');
// })->where('slug', 'b-safe|b-wifi|fibra-call|fibra-connect|my-web|pacotes\/xxxx-one|pacotes\/xxxx-max|smart-box')
// 	->name('produtos');

//Produtos
// Route::group(['prefix' => 'produtos-corporativos'],function (){
// 	Route::get('/',function (){
// 		return view('site/produtos/produtos');
// 	})->name('produtos');

// 	Route::get('/b-safe',function (){
// 		return view('site/produtos/b-safe');
// 	})->name('produto.b-safe');

// 	Route::get('/b-wifi',function (){
// 		return view('site/produtos/b-wifi');
// 	})->name('produto.b-wifi');

// 	Route::get('/fibra-call',function (){
// 		return view('site/produtos/fibra-call');
// 	})->name('produto.fibra-call');

// 	Route::get('/fibra-connect',function (){
// 		return view('site/produtos/fibra-connect');
// 	})->name('produto.fibra-connect');

// 	Route::get('/my-web',function (){
// 		return view('site/produtos/my-web');
// 	})->name('produto.my-web');
	
// 	Route::get('/pacotes/xxxx-one',function (){
// 		return view('site/produtos/pacotes/xxxx-one');
// 	})->name('produto.pacote.xxxx-one');

// 	Route::get('/pacotes/xxxx-max',function (){
// 		return view('site/produtos/pacotes/xxxx-max');
// 	})->name('produto.pacote.xxxx-max');

// 	Route::get('/smart-box',function (){
// 		return view('site/produtos/smart-box');
// 	})->name('produto.smart-box');
// });

// Route::get('/fastpack',function (){
// 	return view('site/produtos/fastpack_b');
// })->name('produtos.fastpack');

// Route::get('/produtos',function (){
// 	return view('site/produtos/comparativo');
// })->name('produtos.comparativo');

// Route::get('/produtos/{slug}', function($slug) {
// 	return redirect()->route('produtos', ['slug' => $slug]);
// });

//Contato
// Route::get('/contato',function (){
// 	return view('site/contato');
// });

//E-mails
Route::post('/contato/send-produto-mail/{slug}','ContatoController@produtoMail');

Route::group(['prefix'=>'cliente','middleware'=>['auth:cliente_api']],function () {
	/** Serviços */
	Route::get('/servicos/load','Cliente\ServicoController@getServicosCliente');
	Route::get('/servicos','Cliente\ServicoController@index');
	Route::get('/servicos/{id}','Cliente\ServicoController@detalhe');
	Route::get('/servicos2/{id}','Cliente\ServicoController@getServicobyId');
	Route::get('/servicos/{id}/logins','Cliente\ServicoController@getLogins');
	Route::get('/servicos/{id}/called-traffic','Cliente\ServicoController@getCalledTraffic');
	Route::get('/servicos/{id}/service-traffic','Cliente\ServicoController@getServiceTraffic');
	Route::get('/servicos/{id}/call-detailed/{url}','Cliente\ServicoController@getCallDetailed');
	Route::get('/servicos/{id}/call-detailed-time','Cliente\ServicoController@getCallDetailedTime');
	Route::get('/servicos/{id}/calls','Cliente\ServicoController@getCalls');

	/** Novo Chamado **/
	Route::get('/chamados/abrir-chamado','Cliente\ChamadoController@abrirChamado');
	Route::get('/chamados/services','Cliente\ChamadoController@getServiceList');
	/** Chamados */
	Route::get('/chamados','Cliente\ChamadoController@index');
	Route::get('/chamados/load','Cliente\ChamadoController@getChamadosCliente');
	Route::get('/chamados/last','Cliente\ChamadoController@getLastChamados');
	Route::get('/chamados/resolved','Cliente\ChamadoController@getChamadosResolvidos');
	Route::get('/chamados/{id}/nodes','Cliente\ChamadoController@getIncidentNodes');
	Route::get('/chamados/{documento}/{id}','Cliente\ChamadoController@detalhe');
	Route::post('/chamado','Cliente\ChamadoController@postChamado');


	/** Faturas */
	Route::get('/faturas','Cliente\FaturaController@index');
	Route::get('/faturas/list','Cliente\FaturaController@getFaturasCliente');
	Route::get('/faturas/last','Cliente\FaturaController@getLastFaturasCliente');

	/** Contratos */
	Route::get('/contratos','Cliente\ContratoController@index');
	Route::get('/contratos/load','Cliente\ContratoController@getContratosCliente');
	Route::get('/contratos/vigentes','Cliente\ContratoController@getContratosVigentes');

	/** Contato */
	Route::get('/contato','Cliente\ContatoController@index');
	Route::get('/contato/fields','Cliente\ContatoController@getRightNowFields');
	Route::get('/contato/list','Cliente\ContatoController@getContactList');
	Route::get('/contato/services','Cliente\ContatoController@getServiceList');
	Route::post('/contato','Cliente\ContatoController@postContact');

	/** Faq */
	Route::get('/faqs/{url}','Cliente\FaqController@index');

	/** Manuais */
	Route::get('/manuais/{url}','Cliente\ManuaisController@index');

	/** Perfil */
	Route::get('/perfil','Cliente\PerfilController@index');
	Route::post('perfil/editar','Cliente\PerfilController@editar');
	Route::post('perfil/senha','Cliente\PerfilController@senha');
	Route::post('perfil/media', 'Cliente\PerfilController@createMedia');
	Route::get('perfil/media/{id}', 'Cliente\PerfilController@getMedia');

	/** Primeiro Acesso */
	Route::get('/primeiro-acesso','Cliente\PrimeiroAcessoController@index');

	Route::get('','Cliente\DashboardController@index');
	Route::get('/dashboard/servicos','Cliente\DashboardController@getServicosCliente');
	Route::get('/dashboard/chamados','Cliente\DashboardController@getChamadosCliente');
	Route::get('/dashboard/faturas','Cliente\DashboardController@getFaturasCliente');
	Route::get('/dashboard/contratos','Cliente\DashboardController@getContratosCliente');

});

//Login - Logout
Route::group(['prefix' => 'clientes'], function () {

	Route::group(['middleware' => ['auth:api']],function (){
		Route::get('gerenciador/login/{api_token}','Cliente\Auth\UsuarioAuthController@loginGerenciador');
	});

	Route::group(array('prefix' => 'auth'),function (){
		// Route::get('login', 'Cliente\Auth\UsuarioAuthController@showLoginForm')->name('clientes/login');
		// Route::post('login', 'Cliente\Auth\UsuarioAuthController@login');
		Route::get('logout', 'Cliente\Auth\UsuarioAuthController@logout')->name('logout');
		Route::get('password/reset', 'Cliente\Auth\UsuarioForgotPassowordController@showLinkRequestForm')->name('password.request');
		// Route::post('password/email', 'Cliente\Auth\UsuarioForgotPassowordController@sendResetLinkEmail')->name('password.email');
		Route::get('password/reset/{token}', 'Cliente\Auth\UsuarioResetPasswordController@showResetForm')->name('password.reset');
		Route::post('password/reset', 'Cliente\Auth\UsuarioResetPasswordController@reset');
	});
});

Route::get('/{slug?}/{other_slug?}', 'Web\PaginasController@spa')
	->where(['slug' => '^(?!(gerenciador|clientes|noticia))[a-zA-Z0-9_+-]+']);
