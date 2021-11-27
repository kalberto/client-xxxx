@extends('site.structure')

@section('head')
	<title>xxxx | Produtos | Fastoack</title>
	<meta name="description" content="">
@endsection

@section('header')
	<picture class="picture-mob">
        <source srcset="{{url('assets/images/background/produtos/bg_header_fastpack_op2_479x550.jpg')}}" media="(max-width: 479px)">
        <source srcset="{{url('assets/images/background/produtos/bg_header_fastpack_op2_667x550.jpg')}}" media="(max-width: 667px)">
        <source srcset="{{url('assets/images/background/produtos/bg_header_fastpack_op2_768x550.jpg')}}" media="(max-width: 768px)">
        <source srcset="{{url('assets/images/background/produtos/bg_header_fastpack_op2.jpg')}}">
        <img src="{{url('assets/images/background/produtos/bg-smart-box.jpg')}}" alt="Smart Box"/>
	</picture>

	<div class="full-width destaque">
		<div class="center">
			<div class="block-g fright" >
				<div class="block fleft">
					<div class="fleft" style="width:100%;">
						<img class="logo-produto" src="{{url('assets/images/produtos/svg/logo_fastpack.svg')}}" alt="" />
						<h1 style="color:rgba(0,0,0,0); height:1px;">FIBRA CALL</h1>
					</div>
					<p>
						Com a xxxx, sua empresa será tratada com a grandeza que ela merece.
						Soluções 100% fibra óptica em voz e dados. Internet mais rápida do que banda larga, linha telefônica com ligações à vontade para números fixos locais e muitas outras vantagens.
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
	<main class="conteudo-fastpack">
		<section>
			<div class="full-width pad50">
				<div class="center">
					<div class="titulo fleft">
						<h2>Fastpack</h2>
						<p class="only-desk"> Para empresas pequenas ou médias que pensam grande.</p>
					</div>
					<div class="row">
						<div class="column column-6 vantagens">
							<ul>
								<li>Excelente custo-benefício.</li>
								<li>Serviço eficaz e adequado à sua necessidade.</li>
								<li>Pacotes simples e objetivos.</li>
								<li>Internet superior a uma banda larga, ideal para navegação, redes sociais, vídeos e muito mais.</li>
								<li>Sua empresa pode falar à vontade para qualquer telefone fixo na sua cidade.</li>
								<li>Serviços adicionais de PABX virtual, backup e 0800</li>
							</ul>
						</div>
						<div class="column column-6 completo">
							<div class="block-produto suporte produto only-desk dashed">
								<p>
									<span>SUPORTE TÉCNICO</span><br>
									<strong>24h</strong> por dia, <strong>7 dias</strong><br>
									por semana.
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
		<a name="contato-produtos" style="visibility: hidden; display: block; height: 0;"></a>
		<div class="full-width formulario">
			<div class="center">
				<div class="titulo fleft">
					<h2 class="only-desk">SOLICITE O FASTPACK xxxx E MUDE SUA EMPRESA DE PATAMAR.</h2>
					<h2 class="only-mob">SOLICITE</h2>
					<p class="only-desk">PERSONALIZADOS PARA SUA EMPRESA </p>
				</div>
				<form id="produto_form" name="produto_form" method="POST" onsubmit="dataLayer.push({'Componente': 'CTA', 'Action': 'Enviar', 'event': 'form-contato'});dataLayer.push({'event': 'conversao', 'produto':'fastpack'});">
					<div class="row form">
						<div class="column column-6">
							<input type="text" placeholder="Empresa" name="empresa">
						</div>
						<div class="column column-6">
							<input type="text" placeholder="Contato" name="contato">
						</div>
					</div>
					<div class="row form">
						<div class="column column-6">
							<input type="text" placeholder="E-mail" name="email">
						</div>
						<div class="column column-6">
							<input id="cep" type="text" placeholder="Cep" name="cep">
						</div>
					</div>
					<div class="row form">
						<div class="column column-6">
							<input id="telefone" class="telefone-fastpack" type="text" placeholder="Telefone" name="telefone">
						</div>
						<div class="column column-6">
							<textarea rows="4" placeholder="Mensagem" name="mensagem"></textarea>
						</div>
					</div>
					<input id="slug" name="slug" value="fastpack" hidden>
					<div class="row">
						<div class="column column-12">
							{!! csrf_field() !!}
							<input id="contato" class="btn-enviar fright" type="submit" name="BTEnvia" onsubmit="dataLayer.push({'Componente': 'CTA', 'Action': 'Enviar', 'event': 'form-pagina-produtos'});" value="ENVIAR">
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
	<!-- End Contato -->
@endsection

@section('script')
	<script src="{{url('js/jquery.mask.min.js')}}"></script>
	<script src="{{url('js/fastpack.js')}}"></script>
	<script src="{{url('js/send_mail.js')}}"></script>
@endsection