@extends('site.structure')

@section('head')
	<title>xxxx | Telefonia | Smart Box</title>
	<meta name="description" content="">
@endsection

@section('header')
	<picture class="picture-mob">
        <source srcset="{{url('assets/images/background/produtos/bg-smart-box-479x400.jpg')}}" media="(max-width: 479px)">
        <source srcset="{{url('assets/images/background/produtos/bg-smart-box-667x550.jpg')}}" media="(max-width: 667px)">
        <source srcset="{{url('assets/images/background/produtos/bg-smart-box-768x550.jpg')}}" media="(max-width: 768px)">
        <source srcset="{{url('assets/images/background/produtos/bg-smart-box.jpg')}}">
        <img src="{{url('assets/images/background/produtos/bg-smart-box.jpg')}}" alt="Smart Box"/>
	</picture>

	<div class="full-width destaque">
		<div class="center">
			<div class="block-g fright" >
				<div class="block fleft">
					<div class="fleft" style="width:100%;">
						<img class="logo-produto" src="{{url('assets/images/produtos/smart-box.png')}}" alt="Smart Box" />
						<h1 style="color:rgba(0,0,0,0); height:1px;">Smart Box</h1>
					</div>
					<p>
						O Smart Box é a solução perfeita para modernizar a estrutura de telefonia, de escritórios virtuais e até de empresas que possuem equipes externas ou diversas sedes.
					</p>
				</div>
				<a id="contato" class="btn fright">
					<div class="claro side">
						SOLICITE AGORA
					</div>
				</a>
			</div>
		</div>
	</div>
@endsection

@section('content')
	<main class="conteudo-smart-box">
		<section>
			<div class="full-width pad50">
				<div class="center">
					<div class="titulo fleft">
						<h2>VANTAGENS</h2>
						<p class="only-desk"> A central telefônica do futuro.</p>
					</div>
					<div class="row">
						<div class="column column-6 vantagens vantagens-1">
							<ul>
								<li>Transferência assistida de chamadas internas e externas.</li>
								<li>URA Básica – 1º Nível de Atendimento</li>
								<li>Salas de conferência para até 10 participantes</li>
								<li>Conferência a 3</li>
								<li>Siga-me Incondicional</li>
								<li>Não Perturbe</li>
								<li>Captura de chamadas em grupo</li>
							</ul>
						</div>
						<div class="column column-6 vantagens vantagens-2">
							<ul>
								<li>Discagem Direta a Ramal – DDR</li>
								<li>Função Chefe Secretária</li>
								<li>Bloqueio de recebimento de chamadas a cobrar</li>
								<li>Pin Code (Cadeado eletrônico)</li>
							</ul>
							<div class="block-produto suporte produto only-desk dashed">
								<p>
									<span>SUPORTE TÉCNICO</span><br>
									<strong>24h</strong> por dia, <strong>7 dias</strong><br>
									por semana, com atendimento em até <strong>4h.</strong>
								</p>
							</div>
						</div>
					</div>
					<div class="row pad50bot">
						<div class="column column-12">
							<a class="btn fleft center" href="{{ route('produtos') }}">
								<div class="escuro side">
									CONHEÇA OUTROS PRODUTOS xxxx
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>

	<?php get_template_part('noticias','myweb'); ?>

	<!-- Contato -->
	<section id="contato-produtos">
		@include('../site/shared/solicite-produto',['produto' => 'smart-box'])
	</section>
	<!-- End Contato -->
@endsection

@section('script')
	<script src="{{url('js/send_mail.js')}}"></script>
@endsection