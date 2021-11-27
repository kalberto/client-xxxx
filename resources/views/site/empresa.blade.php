@extends('site/structure')

@section('head')
    <title>xxxx | Empresa</title>
    <meta name="description" content="">
@endsection

@section('header')
    <picture class="picture-mob">
        <source srcset="{{url('assets/images/background/empresa/bg-empresa-479x400.jpg')}}" media="(max-width: 479px)">
        <source srcset="{{url('assets/images/background/empresa/bg-empresa-667x550.jpg')}}" media="(max-width: 667px)">
        <source srcset="{{url('assets/images/background/empresa/bg-empresa-768x550.jpg')}}" media="(max-width: 768px)">
        <source srcset="{{url('assets/images/background/empresa/bg-empresa.jpg')}}">
        <img src="{{url('assets/images/background/empresa/bg-empresa.jpg')}}" alt="Fachada xxxx"/>
    </picture>

    <div class="full-width destaque">
        <div class="center">
            <div class="block-m fright" >
                <div class="block fleft">
                    <h1>xxxx <span>TELECOM</span></h1>
                    <p>TODO O KNOW-HOW E EXPERIÊNCIA ADQUIRIDOS PARA VIABILIZAR UM <strong>SERVIÇO EXCLUSIVO E DEDICADO ÀS EMPRESAS.</strong></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <main class="conteudo-empresa">
        <section class="pad50bot empresa">
            <div class="full-width">
                <div class="center">
                    <div class="titulo fleft">
                        <h2>A EMPRESA</h2>
                        <p class="only-desk">EXPERIÊNCIA NA GESTÃO DE INTERNET PARA O SEU NEGÓCIO</p>
                    </div>
                    <div class="row">
                        <div class="column column-12">
                            <p>A xxxx é uma operadora de telecom com sede em Curitiba que possui soluções inteligentes na gestão de internet para empresas.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column column-6">
                            <p>
                                A xxxx conta com <strong>soluções corporativas customizadas em telecomunicações e tecnologia da informação</strong>
                                que levam conexão à internet, rede de dados, gerenciamento de segurança, armazenamento e back-up de servidores virtuais com alta
                                confiabilidade e disponibilidade para empresas.
                            </p>
                        </div>
                        <div class="column column-6">
                            <iframe src="https://www.youtube.com/embed/g0H3XWukDDM" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column column-12">
                            <p>
                                Com alta conexão, ambientes customizados por cliente, <strong>máxima segurança para as empresas e suporte técnico especializado</strong>, 
                                a xxxx é a melhor escolha para o seu negócio.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pad50bot">
            <div class="fibras fleft">
                <div class="cinza texto">
                    <p>
                        São <strong>três mil quilômetros de fibra óptica</strong> nas cidades de Curitiba, São José dos Campos, Osasco,
                        Barueri e Mauá, instalados com capital próprio <strong>num investimento de mais de 25 milhões de dólares.</strong>
                    </p>
                </div>
            </div>
        </section>
        
		<?php get_template_part('noticias','empresa'); ?>
    </main>
@endsection