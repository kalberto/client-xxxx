@extends('site/structure')

@section('head')
    <title>xxxx</title>
    <meta name="description" content="">
@endsection

@section('header')
    <!--<picture>
        <source srcset="{{url('assets/images/background/home/bg-slider-400x-300.jpg')}}" media="(max-width: 400px)">
        <source srcset="{{url('assets/images/background/home/bg-slider-667x300.jpg')}}" media="(max-width: 667px)">
        <source srcset="{{url('assets/images/background/home/bg-slider-768x550.jpg')}}" media="(max-width: 768px)">
        <source srcset="{{url('assets/images/background/home/bg-slider.jpg')}}">
        <img src="{{url('assets/images/background/home/bg-slider.jpg')}}" alt="Fibra óptica xxxx"/>
    </picture>-->

    <div class="videos">
        <a class="open-video" href="#video">
            Veja o vídeo completo 
            <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <ellipse cx="10" cy="10" rx="8.91" ry="8.91" stroke="#fff" fill="none" stroke-width="2"></ellipse>
                <path fill="#fff" d="M6.152 14.075v-8.15c0-.945 1.004-1.536 1.772-1.063l7.087 4.075c.828.472.828 1.713 0 2.185l-7.086 4.075c-.768.414-1.772-.177-1.772-1.122z"></path>
            </svg>
        </a>
        <video autoplay muted loop poster="{{url('assets/videos/xxxx_o_ponto_thumb.png')}}" preload="auto">
            <source type="video/mp4" src="{{url('assets/videos/xxxx_o_ponto_vt60_edit02_3_1080p.mp4')}}">
            Vídeo não suportado neste navegador, considere utilizar um navegador mais atual.
        </video>
    </div>

	<?php
		#if(time() > strtotime("2018-10-17 22:00:00")) {
	?>
    <?php
		#<div class="promocao center selo">
		#	<div>
		#		<svg width="100%" height="100%" preserveAspectRatio="none">
		#			<polygon points="0,30 0,600 600,600 600,0 30,0" fill="#000" fill-opacity="0.5"/>
		#		</svg>
		#		<div class="texto">
		#			<p class="promocao-titulo">
		#				Não fique amarrado em outras operadoras.
		#			</p>
		#			<p>
		#				<strong>
		#					Se você foi no LIDE e ganhou o brinde
		#					<a href="https://www.youtube.com/watch?v=AdIlE4xSj0w" title="Youtube - xxxx">Clique Aqui</a>
		#				</strong>
		#			</p>
		#		</div>
		#	</div>
		#</div>
    ?>
	<?php
		#}
	?>
@endsection

@section('content')
<main class="conteudo-home" id="conteudo">
    <!-- Sobre a xxxx -->
    <article id="sobre">
        <div class="full-width">
            <div class="center">
                <div class="float-block">
                    <div class="laranja pad fleft">
                        <h1>xxxx</h1>
                        <p>
                            <strong>Soluções em internet, telefonia</strong> e armazenamento em nuvem para atender as necessidades mais específicas do <strong>seu negócio.</strong>
                        </p>
                    </div>
                    <a class="btn fright" onclick="dataLayer.push({'Componente': 'CTA', 'Action': 'Solicite', 'event': 'pagina-produtos'});" href="{{url('produtos')}}">
                        <div class="side escuro">SAIBA MAIS</div>
                    </a>
                </div>
                <div class="pub-call fright only-desk">
                    <p>ATENDIMENTO <strong>EXCLUSIVO</strong></p>
                    <p>E <strong>PERSONALIZADO</strong> PARA SUA EMPRESA</p>
                </div>
            </div>
        </div>
    </article>

    <!-- Pacotes -->
    <section id="pacotes">
        @include('site/shared/pacotes')
    </section>

    <!-- Produtos -->
    <section id="produtos">
        <div class="full-width produtos pad50bot">
            <div class="center">
                <div class="titulo fleft">
                    <h2>SOLUÇÕES</h2>
                    <p class="only-desk">PARA CADA NECESSIDADE, UMA SOLUÇÃO</p>
                </div>
                <div class="row">
                    <div class="column column-6">
                        <a class="fleft" onclick="dataLayer.push({'Componente': 'CTA', 'Action': 'Solicite', 'event': 'fibra-connect'});"  href="{{url('/produtos/fibra-connect')}}">
                            <div class="block-produto internet">
                                <div class="top">
                                    <h3>INTERNET<br> DEDICADA</h3>
                                </div>
                                <div class="bottom">
                                    <p>Por meio de uma conexão exclusiva, o <strong>Fibra Connect</strong> garante que você envie e receba arquivos na mesma velocidade.</p>
                                    <div class="mais fright"> + </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="column column-6">
                        <a class="fleft" onclick="dataLayer.push({'Componente': 'CTA', 'Action': 'Solicite', 'event': 'fibra-connect'});" href="{{url('/produtos/fibra-call')}}">
                            <div class="block-produto telefonia">
                                <div class="top">
                                    <h3>TELEFONIA</h3>
                                </div>
                                <div class="bottom">
                                    <p><strong>O Fibra Call</strong> oferece serviços de telefonia fixa com planos que permitem chamadas locais, nacionais e internacionais.</p>
                                    <div class="mais fright"> + </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="column column-6">
                        <a class="fleft" onclick="dataLayer.push({'Componente': 'CTA', 'Action': 'Solicite', 'event': 'my-web'});" href="{{url('/produtos/my-web')}}">
                            <div class="block-produto rede">
                                <div class="top">
                                    <h3>GERENCIAMENTO<br> DE REDE</h3>
                                </div>
                                <div class="bottom">
                                    <p>Com uma <strong>rede privativa (VPN)</strong>, a troca de informações e os dados entre seus colaboradores trafegam com segurança.</p>
                                    <div class="mais fright"> + </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="column column-6">
                        <a class="fleft" onclick="dataLayer.push({'Componente': 'CTA', 'Action': 'Solicite', 'event': 'b-wifi'});" href="{{url('/produtos/b-wifi')}}">
                            <div class="block-produto seguranca">
                                <div class="top">
                                    <h3>REDE WIFI</h3>
                                </div>
                                <div class="bottom">
                                    <p>O <strong>B-Wifi</strong> permite mobilidade e conectividade nas redes wireless corporativas, com benefícios operacionais e comerciais.</p>
                                    <div class="mais fright"> + </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="column column-12 only-desk dashed">
                        <div class="block-produto suporte">
                            <p>
                                <span>SUPORTE TÉCNICO</span><br>
                                <strong>24h</strong> por dia, <strong>7 dias</strong> por semana.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Noticias -->
    <?php get_template_part('noticias','home'); ?>

    <!-- Depoimentos -->
    <section id="depoimentos">
        @include('site/shared/depoimentos')
    </section>

    <aside id="video" class="videos-pop">
        <div>
            <video src="{{url('assets/videos/xxxx_o_ponto_vt60_edit02_3_1080p.mp4')}}" controls preload="auto"></video>
        </div>
    </aside>
</main>
@endsection