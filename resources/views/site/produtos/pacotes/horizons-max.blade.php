@extends('site/structure')

@section('head')
	<title>xxxx | Segurança | B-Safe</title>
	<meta name="description" content="">
@endsection

@section('header')
	<picture class="picture-mob">
		<source srcset="{{url('assets/images/background/produtos/bg-horizon-max-internet-voz-479x400.jpg')}}" media="(max-width: 479px)">
		<source srcset="{{url('assets/images/background/produtos/bg-horizon-max-internet-voz-667x550.jpg')}}" media="(max-width: 667px)">
		<source srcset="{{url('assets/images/background/produtos/bg-horizon-max-internet-voz-768x550.jpg')}}" media="(max-width: 768px)">
		<source srcset="{{url('assets/images/background/produtos/bg-horizon-max-internet-voz.jpg')}}">
		<img src="{{url('assets/images/background/produtos/bg-horizon-max-internet-voz.jpg')}}" alt="xxxx Max"/>
	</picture>

	<div class="full-width destaque">
		<div class="center">
			<div class="block-g fright" >
				<div class="block fleft">
					<div class="fleft" style="width:100%;">
						<img class="logo-produto" src="{{url('assets/images/produtos/svg/horizonmax.svg')}}" alt="" />
						<h1 style="color:rgba(0,0,0,0); height:1px;">xxxx Max</h1>
					</div>
					<p>
						Atende com eficiência a sua demanda, tanto em internet e rede de dados quanto em telefonia. 
						Serviços com garantia de qualidade e disponibilidade através da rede 100% fibra óptica xxxx,
						com a melhor relação custo/benefício para sua empresa.
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
	<main class="conteudo-xxxx-max">
		<section>
			<div class="full-width pad50">
				<div class="center">
					<div class="titulo fleft">
						<h2>VANTAGENS</h2>
						<p class="only-desk">Para grandes empresas, serviços de internet e voz exclusivos</p>
					</div>
					<div class="row">
						<div class="column column-6 vantagens">
							<ul>
								<li>Melhor relação custo/benefício para a empresa</li>
								<li>Precisão e eficácia nas demandas</li>
								<li>Ideal para grandes empresas</li>
								<li>Portabilidade numérica dos telefones da empresa</li>
								<li>Atendimento personalizado para seu negócio</li>
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
							<a class="btn fleft center" href="{{url('produtos')}}">
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

	<?php get_template_part('noticias','max'); ?>

	<!-- Contato -->
	<section id="contato-produtos">
		@include('../site/shared/solicite-produto', ['produto' => 'xxxx-max'])
	</section>
	<!-- End Contato -->
@endsection

@section('script')
	<script src="{{url('js/send_mail.js')}}"></script>
@endsection