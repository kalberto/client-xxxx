@extends('site/structure')

@section('head')
    <title>xxxx | Internet | Fibra Connect</title>
    <meta name="description" content="">
@endsection

@section('header')
    <picture class="picture-mob">
        <source srcset="{{url('assets/images/background/produtos/bg-fibra-connect-479x400.jpg')}}" media="(max-width: 479px)">
        <source srcset="{{url('assets/images/background/produtos/bg-fibra-connect-667x550.jpg')}}" media="(max-width: 667px)">
        <source srcset="{{url('assets/images/background/produtos/bg-fibra-connect-768x550.jpg')}}" media="(max-width: 768px)">
        <source srcset="{{url('assets/images/background/produtos/bg-fibra-connect.jpg')}}">
        <img src="{{url('assets/images/background/produtos/bg-fibra-connect.jpg')}}" alt="Fibra Connect"/>
    </picture>

    <div class="full-width destaque">
        <div class="center">
            <div class="block-g fright" >
                <div class="block fleft">
                    <div class="fleft" style="width:100%;">
                        <img class="logo-produto" src="{{url('assets/images/produtos/svg/fibraconnect.svg')}}" alt="" />
                        <h1 style="color:rgba(0,0,0,0); height:1px;">FIBRA CONNECT - INTERNET BANDA LARGA</h1>
                    </div>
                    <p>
                        Por meio de uma conexão exclusiva, o Fibra Connect garante que você envie e receba arquivos na mesma velocidade. 
                        Permite, ainda, que você aumente a velocidade com rapidez e facilidade quando precisar! 
                        Internet ágil, 100% em fibra óptica e sem interferências.
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
    <main class="conteudo-fibra-connect">
        <section>
            <div class="full-width pad50">
                <div class="center">
                    <div class="titulo fleft">
                        <h2>
                            VANTAGENS
                        </h2>
                        <p class="only-desk">Conexão com a mesma velocidade de download e upload</p>
                    </div>
                    <div class="row">
                        <div class="column column-6 vantagens">
                            <ul>
                                <li>Garantia de 100% da velocidade contratada</li>
                                <li>Conexão com a mesma velocidade de download e upload</li>
                                <li>Acesso disponível em velocidades de 5 Mbps a 1 Gbps</li>
                                <li>Tráfego de dados ilimitado e preço mensal fixo</li>
                                <li>Aumento de velocidade para períodos predefinidos sob demanda</li>
                            </ul>
                        </div>
                        <div class="column column-6 completo">
                            <div class="block-produto suporte produto only-desk dashed">
                                <p><span>SUPORTE TÉCNICO</span><br>
                                    <strong>24h</strong> por dia, <strong>7 dias</strong><br>
                                    por semana, com atendimento em até <strong>4h.</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="row pad50bot">
                        <div class="column column-12">
                            <a class="btn fleft center" href="{{ route('produtos') }}">
                                <div class="escuro side">
                                    CONHEÇA  OUTROS PRODUTOS xxxx
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php get_template_part('noticias','fibraconect'); ?>

    <!-- Contato -->
    <section id="contato-produtos">
        @include('../site/shared/solicite-produto',['produto'=>'fibra-connect'])
    </section>
    <!-- End Contato -->

@endsection

@section('script')
    <script src="{{url('js/send_mail.js')}}"></script>
@endsection