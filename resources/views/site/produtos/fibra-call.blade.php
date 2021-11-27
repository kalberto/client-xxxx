@extends('site/structure')

@section('head')
	<title>xxxx | Telefonia | Fibra Call</title>
	<meta name="description" content="">
@endsection

@section('header')
	<picture class="picture-mob">
        <source srcset="{{url('assets/images/background/produtos/bg-fibra-call-telefonia-479x400.jpg')}}" media="(max-width: 479px)">
        <source srcset="{{url('assets/images/background/produtos/bg-fibra-call-telefonia-667x550.jpg')}}" media="(max-width: 667px)">
        <source srcset="{{url('assets/images/background/produtos/bg-fibra-call-telefonia-768x550.jpg')}}" media="(max-width: 768px)">
        <source srcset="{{url('assets/images/background/produtos/bg-fibra-call-telefonia.jpg')}}">
        <img src="{{url('assets/images/background/produtos/bg-fibra-call-telefonia.jpg')}}" alt="Fibra Call"/>
	</picture>
	
	<div class="full-width destaque">
		<div class="center">
			<div class="block-g fright" >
				<div class="block fleft">
					<div class="fleft" style="width:100%;">
						<img class="logo-produto" src="{{url('assets/images/produtos/svg/fibracall.svg')}}" alt="" />
						<h1 style="color:rgba(0,0,0,0); height:1px;">FIBRA CALL</h1>
					</div>
					<p>
						Serviços de <strong>telefonia fixa</strong> com mais qualidade, com planos que permitem 
						<strong>chamadas locais, nacionais e internacionais</strong>, 
						sempre com preços competitivos e transparência total nas cobranças.
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
	<main class="conteudo-fibra-call">
		<section>
			<div class="full-width pad50">
				<div class="center">
					<div class="titulo fleft">
						<h2>VANTAGENS</h2>
						<p class="only-desk">O MELHOR DA INTERNET, AGORA TAMBÉM COM SERVIÇO DE VOZ</p>
					</div>
					<div class="row">
						<div class="column column-6 vantagens">
						<ul>
							<li>Ligações entre ramais sem custo adicional</li>
							<li>Comunicação rápida e eficaz com os clientes</li>
							<li>Tarifas atrativas para qualquer tipo de chamada</li>
							<li>Conexão 100% fibra óptica xxxx</li>
							<li>Tecnologia de ponta na comunicação</li>
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

	<?php get_template_part('noticias','fibracall'); ?>

	<!-- Contato -->
    <section id="contato-produtos">
        @include('../site/shared/solicite-produto',['produto' => 'fibra-call'])
    </section>
    <!-- End Contato -->
@endsection

@section('script')
	<script src="{{url('js/send_mail.js')}}"></script>
@endsection