@extends('site/structure')

@section('head')
	<title>xxxx | Rede Privativa | My Web</title>
	<meta name="description" content="">
@endsection

@section('header')
	<picture class="picture-mob">
        <source srcset="{{url('assets/images/background/produtos/bg-my-web-479x400.jpg')}}" media="(max-width: 479px)">
        <source srcset="{{url('assets/images/background/produtos/bg-my-web-667x550.jpg')}}" media="(max-width: 667px)">
        <source srcset="{{url('assets/images/background/produtos/bg-my-web-768x550.jpg')}}" media="(max-width: 768px)">
        <source srcset="{{url('assets/images/background/produtos/bg-my-web.jpg')}}">
        <img src="{{url('assets/images/background/produtos/bg-my-web.jpg')}}" alt="My Web"/>
	</picture>

	<div class="full-width destaque">
		<div class="center">
			<div class="block-g fright" >
				<div class="block fleft">
					<div class="fleft" style="width:100%;">
						<img class="logo-produto" src="{{url('assets/images/produtos/svg/myweb.svg')}}" alt="" />
						<h1 style="color:rgba(0,0,0,0); height:1px;">My Web</h1>
					</div>
					<p>Com uma rede privativa (VPN), a troca de informações entre seus colaboradores e os dados do seu negócio trafega com proteção e segurança. 
						Solução perfeita para quem deseja interligar diferentes unidades da mesma empresa em uma rede com velocidade e segurança.
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
	<main class="conteudo-my-web">
		<section>
			<div class="full-width pad50">
				<div class="center">
					<div class="titulo fleft">
						<h2>VANTAGENS</h2>
						<p class="only-desk">Segurança e estabilidade nas redes de dados da sua empresa</p>
					</div>
					<div class="row">
						<div class="column column-6 vantagens">
							<ul>
								<li>Acesso dedicado 100% em fibra óptica</li>
								<li>Possibilidade de interligar dois ou mais pontos</li>
								<li>Segurança garantida em um ambiente virtual exclusivo</li>
								<li>Tráfego de dados ilimitado e preço mensal fixo</li>
								<li>VPN com velocidades de 2 Mbps a 1 Gbps</li>
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

	<?php get_template_part('noticias','myweb'); ?>

	<!-- Contato -->
	<section id="contato-produtos">
		@include('../site/shared/solicite-produto',['produto'=> 'my-web'])
	</section>
	<!-- End Contato -->
@endsection

@section('script')
	<script src="{{url('js/send_mail.js')}}"></script>
@endsection