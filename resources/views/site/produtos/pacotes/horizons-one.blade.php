@extends('site/structure')

@section('head')
	<title>xxxx | Segurança | B-Safe</title>
	<meta name="description" content="">
@endsection

@section('header')
	<picture class="picture-mob">
		<source srcset="{{url('assets/images/background/produtos/bg-horizon-one-internet-voz-479x400.jpg')}}" media="(max-width: 479px)">
		<source srcset="{{url('assets/images/background/produtos/bg-horizon-one-internet-voz-667x550.jpg')}}" media="(max-width: 667px)">
		<source srcset="{{url('assets/images/background/produtos/bg-horizon-one-internet-voz-768x550.jpg')}}" media="(max-width: 768px)">
		<source srcset="{{url('assets/images/background/produtos/bg-horizon-one-internet-voz.jpg')}}">
		<img src="{{url('assets/images/background/produtos/bg-horizon-one-internet-voz.jpg')}}" alt="xxxx One"/>
	</picture>

	<div class="full-width destaque">
		<div class="center">
			<div class="block-g fright" >
				<div class="block fleft">
					<div class="fleft" style="width:100%;">
						<img class="logo-produto" src="{{url('assets/images/produtos/svg/horizonone.svg')}}" alt="" />
						<h1 style="color:rgba(0,0,0,0); height:1px;">xxxx One</h1>
					</div>
					<p>
						Serviço de telefonia perfeito para pequenas e médias empresas que querem mais velocidade com economia. 
						Além de 5 Mbps de conexão dedicada, você pode escolher o plano de telefonia com tarifa fixa que tenha o melhor 
						custo/benefício para o seu negócio.
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
	<main class="conteudo-xxxx-one">
		<section>
			<div class="full-width pad50">
				<div class="center">
					<div class="titulo fleft">
						<h2>VANTAGENS</h2>
						<p class="only-desk">O melhor da internet, agora também com serviços de voz</p>
					</div>
					<div class="row">
						<div class="column column-6 vantagens">
							<ul>
								<li>Serviços de telefonia de acordo com a necessidade da sua empresa</li>
								<li>Rede 100% em fibra óptica</li>
								<li>Ideal para pequenas e médias empresas</li>
								<li>Atendimento personalizado para seu negócio</li>
								<li>Portabilidade numérica dos telefones da empresa</li>
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

	<?php get_template_part('noticias','one'); ?>

	<!-- Contato -->
	<section id="contato-produtos">
		@include('../site/shared/solicite-produto', ['produto' => 'xxxx-one'])
	</section>
	<!-- End Contato -->
@endsection

@section('script')
	<script src="{{url('js/send_mail.js')}}"></script>
@endsection