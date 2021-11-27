@extends('site/structure')

@section('head')
	<title>xxxx | Rede Wifi | B-Wifi</title>
	<meta name="description" content="">
@endsection

@section('header')
	<picture class="picture-mob">
        <source srcset="{{url('assets/images/background/produtos/bg-b-wifi-479x400.jpg')}}" media="(max-width: 479px)">
        <source srcset="{{url('assets/images/background/produtos/bg-b-wifi-667x550.jpg')}}" media="(max-width: 667px)">
        <source srcset="{{url('assets/images/background/produtos/bg-b-wifi-768x550.jpg')}}" media="(max-width: 768px)">
        <source srcset="{{url('assets/images/background/produtos/bg-b-wifi.jpg')}}">
        <img src="{{url('assets/images/background/produtos/bg-b-wifi.jpg')}}" alt="B-Wifi"/>
	</picture>

	<div class="full-width destaque">
		<div class="center">
			<div class="block-g fright" >
				<div class="block fleft">
					<div class="fleft" style="width:100%;">
						<img class="logo-produto" src="{{url('assets/images/produtos/svg/Bwi-fi.svg')}}" alt="" />
						<h1 style="color:rgba(0,0,0,0); height:1px;">B-Wifi</h1>
					</div>
					<p>
						Mobilidade e conectividade nas redes wireless corporativas, com benefícios operacionais e comerciais. 
						Conexão instantânea, banda garantida para cada usuário, autenticação, controle e gestão da sua empresa.
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
	<main class="conteudo-b-wifi">
		<section>
			<div class="full-width pad50">
				<div class="center">
					<div class="titulo fleft">
						<h2>VANTAGENS</h2>
						<p class="only-desk">Internet com versatilidade na sua empresa</p>
					</div>
					<div class="row">
						<div class="column column-6 vantagens">
							<ul>
								<li>Serviço de alta velocidade</li>
								<li>Alta conexão na rede wireless da sua empresa</li>
								<li>Administração e gerenciamento da rede WLAN</li>
								<li>Projeto de redes Wi-Fi padrão IEEE 802.11 a, b, g, n e ac</li>
								<li>Sem investimentos e riscos de obsolescência (TCO)</li>
							</ul>
						</div>
						<div class="column column-6 completo">
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

	<?php get_template_part('noticias','bwifi'); ?>

	<!-- Contato -->
	<section id="contato-produtos">
		@include('../site/shared/solicite-produto', ['produto' => 'b-wifi'])
	</section>
	<!-- End Contato -->
@endsection

@section('script')
	<script src="{{url('js/send_mail.js')}}"></script>
@endsection