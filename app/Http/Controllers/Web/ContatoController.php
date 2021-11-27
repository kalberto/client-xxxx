<?php

namespace App\Http\Controllers\Web;

use App\Helpers\NotMapped;
use App\Helpers\SEO;
use App\Http\Requests\Web\Contato\SejaParceiroRequest;
use App\Lead;
use App\Http\Requests\Web\Request;
use App\Http\Request\SendMailRequest;
use App\Http\Request\XPJ022Request;
use App\Http\Request\XPJ021Request;

use App\Mail\ContatoMail;
use App\Mail\SejaUmParceiroMail;
use App\Models\Site\SejaParceiro;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContatoController extends Controller
{

	private static function getUsers($tipo = false){
		$debug = env('APP_ENV') == 'local' || env('APP_ENV') == 'dev' || env('APP_ENV') == 'develop';
		if($debug){
			return [(object)[
				'email' => 'alberto.almeida@xxxx.com.br',
				'name' => "Alberto"
			]];
		}else{
			if($tipo == 'rh'){
				return [(object)[
					'email' => 'contato.rh@xxxxxxx.com',
					'name' => 'RH xxxx'
				]];
			}
			if($tipo == 'compras'){
				return [(object)[
					'email' => 'compras@xxxxxx.com',
					'name' => 'Compras xxxxxx'
				]];
			}
			if($tipo == 'seja-parceiro'){
				return [(object)[
					'email' => 'Indicador@xxxxx.com',
					'name' => 'Indicador xxxxx'
				]];
			}
			return [(object)[
				'email' => 'crm@xxxxxxx.com',
				'name' => 'CRM xxxxx'
			]];
		}
	}

	public function assuntos(Request $request){
		$language = $this->getLanguage($request->all());
		return response()->json([
			'assuntos' => NotMapped::$assuntos[$language->locale],
			'seo' => SEO::get('contato')
		]);
	}

	public function estados(Request $request){
		return response()->json(DB::table("estados")->select(['nome','uf'])->get(),200);
	}

	public function contato(Request $request){
		$language = $this->getLanguage($request->all());
		$data = $request->all();
		$mailto = $this->getUsers();
		if(isset($data['slug'])){
			$subject = $this->getSubject($data['slug']);
			if($data['slug'] == "fastpack")
				$mailRequest = new XPJ021Request;
			else
				$mailRequest = new XPJ022Request;
		}else{
			$mailRequest = new SendMailRequest;
			$subject = '(Contato do site - xxxx.com)';
			if(($data['assunto'])){
				if($data['assunto'] == "trabalhe-conosco"){
					$subject = '(Contato do site - Trabalhe conosco)';
					$mailto = $this->getUsers('rh');
				}
				elseif($data['assunto'] == "compras"){
					$subject = '(Contato do site - Compras)';
					$mailto = $this->getUsers('compras');
				}
			}

		}
		$data = $request->all();
		$validate = $mailRequest->validar($data);
		if(!$validate->fails()){
			if($this->send($mailto,$subject, $data)){
				$return = [
					'msg' => NotMapped::$messages[$language->locale]['mail_success']
				];
				$statusCode = 200;
			}else{
				$return = [
					'msg' => NotMapped::$messages[$language->locale]['mail_error']
				];
				$statusCode = 500;
			}
		}
		else{
			$return = [
				'msg' => NotMapped::$messages[$language->locale]['mail_validation'],
				'error_validate' => $validate->errors()
			];
			$statusCode = 422;
		}
		return response()->json($return,$statusCode);
	}

	public function sejaUmParceiro(SejaParceiroRequest $request){
		$language = $this->getLanguage($request->all());
		$data = $request->all();
		$registro = SejaParceiro::store($data,$request->ip());
		if($registro != false){
			$subject = '(Contato do site - Seja um parceiro)';
			$mail = new SejaUmParceiroMail($subject,$data);
			$mailto = $this->getUsers('seja-parceiro');
			Mail::to($mailto)->send($mail);
			$statusCode = 200;
			$response = [
				'msg' => NotMapped::$messages[$language->locale]['mail_success']
			];
		}else{
			$statusCode = 500;
			$response = [
				'msg' => NotMapped::$messages[$language->locale]['mail_error']
			];
		}
		return response()->json($response,$statusCode);
	}

	public static function send($users,$subject ,$data){
		try{
			$mail = new ContatoMail($subject,$data);
			Mail::to($users)->send($mail);
			if(isset($data['nome']))
				$data['contato'] = $data['nome'];
			Lead::store($data);
			return true;
		}
		catch (\Exception $exception){
			Log::error($exception->getMessage());
		}
		return false;
	}

	private function getSubject($slug){

		if($slug == 'bsafe')
			return '(Contato do site - xxxx)';
		if($slug == 'bwifi')
			return '(Contato do site - xxxx)';
		if($slug == 'fibracall')
			return '(Contato do site - xxx)';
		if($slug == 'fibraconnect')
			return '(Contato do site - xxxx)';
		if($slug == 'max')
			return '(Contato do site - xxxx)';
		if($slug == 'myweb')
			return '(Contato do site - xxxx)';
		if($slug == 'one')
			return '(Contato do site - xxxx)';
		if($slug == 'fastpack')
			return '(Contato do site - xxxx)';

		return '(Contato do site - xxxxx)';

	}

}