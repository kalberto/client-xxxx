<?php
/**
 * User: alberto.almeida
 * Date: 04/08/2017
 * Time: 16:49
 */
namespace App\Http\Controllers;

use App\Lead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Request\SendMailRequest;
use App\Http\Request\XPJ022Request;
use App\Http\Request\XPJ021Request;

use Mail;
use Response;

class ContatoController extends Controller
{

	public function __construct() {
		define("FROM_EMAIL",'atendimentosite@xxxx.com' );
		define('TO_EMAIL','crm@xxxx.com');
	}

	public function contatoMail(Request $request)
	{
		$mailRequest = new SendMailRequest;
		$data = $request->all();
		$validate = $mailRequest->validar($data);

		if(!$validate->fails()){
			$subject = '(Contato do site - xxxx.com)';
			$mailto = TO_EMAIL;
			if($data['assunto'] == "trabalhe-conosco"){
				$subject = '(Contato do site - Trabalhe conosco)';
				$mailto = 'contato.rh@xxxx.com';
			}
			/*
			if(isset($data['form_origem']) && $data['form_origem'] == 'modal-lead'){
				$subject = '(Contato do site - Modal Lead)';
				$mailto = 'contato.rh@xxxx.com';
			}
			*/
			$return = $this->send('emails/email_contato',$data,$mailto,$subject);
		}
		else{
			$return = [
				'msg' => 'Ocorreu um erro ao enviar o e-mail.',
				'error_validate' => $validate->errors()
			];
			$return['status'] = 422;
		}

		return Response::json($return,$return['status']);
	}

	public function produtoMail(Request $request, $slug = null){
		if($slug == "fastpack")
			$mailRequest = new XPJ021Request;
		else
			$mailRequest = new XPJ022Request;
		$data = $request->all();
		$validate = $mailRequest->validar($data);

		if(!$validate->fails()){
			$subject = $this->getSubject($slug);
			$mailto = TO_EMAIL;
			$return = $this->send('emails/email_produtos',$data,$mailto,$subject);
		}
		else{
			$return = [
				'msg' => 'Ocorreu um erro ao enviar o e-mail.',
				'error_validate' => $validate->errors()
			];
			$return['status'] = 422;
		}

		return Response::json($return,$return['status']);
	}

	private function send($view,$data,$mailto,$subject){

		if(!Mail::send($view, $data, function ($message) use ( $mailto, $data, $subject) {

			$message->from(FROM_EMAIL, utf8_decode($data['empresa']));
			$message->subject($subject);
			$message->to($mailto);
			if(isset($data['email']))
				$message->replyTo($data['email'],$data['empresa']);
		})){ //E-mail enviado com sucesso

			Lead::store($data);

			//Mensagem enviada com sucesso
			$statusCode = 200;
			$response = [
				'status' => $statusCode,
				'msg' => 'Mensagem enviada com sucesso!'
			];

		}else{
			//Erro ao enviar formulário
			$statusCode = 401;
			$response = [
				'status' => $statusCode,
				'msg' => 'Ocorreu um erro ao enviar o e-mail.'
			];
		}

		return $response;
	}

	private function getSubject($slug){

		if($slug == 'bsafe')
			return '(Contato do site - B-Safe)';
		if($slug == 'bwifi')
			return '(Contato do site - B-Wifi)';
		if($slug == 'fibracall')
			return '(Contato do site - Fibra Call)';
		if($slug == 'fibraconnect')
			return '(Contato do site - Fibra-Connect)';
		if($slug == 'max')
			return '(Contato do site - xxxx Max)';
		if($slug == 'myweb')
			return '(Contato do site - My-web)';
		if($slug == 'one')
			return '(Contato do site - xxxx One)';
		if($slug == 'fastpack')
			return '(Contato do site - Fastpack)';

		return '(Contato do site - Produtos)';

	}

}