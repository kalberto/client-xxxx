<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 21/10/2020
 * Time: 15:11
 */

namespace App\Helpers;


class NotMapped {
	public static $messages = [
		'pt-br' => [
			'mail_success'      => 'Mensagem enviada com sucesso!',
			'mail_error'        => 'Ocorreu um erro ao enviar o e-mail.',
			'mail_validation'   => 'Preencha os campos corretamente.'
		],
		'en-gb' => [
			'mail_success'      => 'Message sent successfully!',
			'mail_error'        => 'An error occurred while sending the email.',
			'mail_validation'   => 'Fill in the fields correctly'
		]
	];

	public static $assuntos = [
		'pt-br' => [
			[
				'name' => 'informacoes',
				'value' => 'Informações',
			],
			[
				'name' => 'reclamacoes',
				'value' => 'Reclamações',
			],
			[
				'name' => 'elogios',
				'value' => 'Elogios',
			],
			[
				'name' => 'Produtos/Servicos',
				'value' => 'Produtos/Serviços',
			],
			[
				'name' => 'trabalhe-conosco',
				'value' => 'Trabalhe conosco',
			],
			[
				'name' => 'compras',
				'value' => 'Compras'
			]
		],
		'en-gb' => [
			[
				'name' => 'informacoes',
				'value' => 'Informações EN-GB',
			],
			[
				'name' => 'reclamacoes',
				'value' => 'Reclamações',
			],
			[
				'name' => 'elogios',
				'value' => 'Elogios',
			],
			[
				'name' => 'Produtos/Servicos',
				'value' => 'Produtos/Serviços',
			],
			[
				'name' => 'trabalhe-conosco',
				'value' => 'Trabalhe conosco',
			],
			[
				'name' => 'compras',
				'value' => 'Compras'
			]
		]
	];
}