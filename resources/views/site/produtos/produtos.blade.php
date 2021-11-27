@extends('site/structure')

@section('head')
    <title>xxxx | Produtos Corporativos</title>
    <meta name="description" content="">
@endsection

@section('header')
    <picture class="picture-mob">
        <source srcset="{{url('assets/images/background/produtos/bg-produtos-479x400.jpg')}}" media="(max-width: 479px)">
        <source srcset="{{url('assets/images/background/produtos/bg-produtos-667x550.jpg')}}" media="(max-width: 667px)">
        <source srcset="{{url('assets/images/background/produtos/bg-produtos-768x550.jpg')}}" media="(max-width: 768px)">
        <source srcset="{{url('assets/images/background/produtos/bg-produtos.jpg')}}">
        <img src="{{url('assets/images/background/produtos/bg-produtos.jpg')}}" alt="Rede xxxx"/>
    </picture>

    <div class="full-width destaque">
        <div class="center">
            <div class="block-m fright" >
                <div class="block fright">
                    <h1>PRODUTOS <span>xxxx</span></h1>
                    <div class="subtitle">
                        <p>EXCLUSIVOS E PERSONALIZADOS <strong>PARA SUA EMPRESA</strong></p>
                    </div>
                    <p>
                        <strong>Soluções eficientes</strong> para empresas que procuram agilidade e segurança nos negócios, 
                        <strong>com alto desempenho na gestão de internet</strong> e segurança em rede de dados.
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
    <main class="conteudo-produtos">
        <!-- Produtos -->
        <section id="conheca-produtos">
            <div class="full-width listagem-produtos">
                <div class="center">
                    <div class="titulo fleft">
                        <h2>CONHEÇA</h2>
                        <p class="only-desk">PRODUTOS xxxx QUE ATENDEM ÀS NECESSIDADES DA SUA EMPRESA</p>
                    </div>
                    <div class="row">
                        <div class="column column-4">
                            <a class="fleft" href="{{ route('produtos.fastpack') }}">
                                <div class="block-produto internet">
                                    <div class="top">
                                        <img src="{{url('assets/images/svg/icon_fastpack_produtos.svg')}}" alt="" />
                                    </div>
                                    <div class="bottom">
                                        <h3>FASTPACK</h3>
                                        <p>Para empresas pequenas ou médias <strong>que pensam grande</strong>.</p>
                                        <div class="mais fright"> + </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="column column-4">
                            <a class="fleft" href="{{ route('produtos', ['slug' => 'fibra-connect']) }}">
                                <div class="block-produto internet">
                                    <div class="top">
                                        <img src="{{url('assets/images/svg-internet.svg')}}" alt="" />
                                    </div>
                                    <div class="bottom">
                                        <h3>FIBRA CONNECT</h3>
                                        <p>Internet de alta velocidade, <strong>100% fibra óptica</strong> e 100% de banda download e upload.</p>
                                        <div class="mais fright"> + </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="column column-4">
                            <a class="fleft" href="{{ route('produtos', ['slug' => 'fibra-call']) }}">
                                <div class="block-produto telefonia">
                                    <div class="top">
                                        <img src="{{url('assets/images/svg-telefone.svg')}}" alt="" />
                                    </div>
                                    <div class="bottom">
                                        <h3>FIBRA CALL</h3>
                                        <p>Serviços de <strong>telefonia fixa</strong> com qualidade e segurança nas comunicações por voz.</p>
                                        <div class="mais fright"> + </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column column-4">
                            <a class="fleft" href="{{ route('produtos', ['slug' => 'b-wifi']) }}">
                                <div class="block-produto rede">
                                    <div class="top">
                                        <img src="{{url('assets/images/svg-wifi.svg')}}" alt="" />
                                    </div>
                                    <div class="bottom">
                                        <h3>B WIFI</h3>
                                        <p><strong>Conectividade wireless corporativa</strong>, alta velocidade e segurança para sua empresa.</p>
                                        <div class="mais fright"> + </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="column column-4">
                            <a class="fleft" href="{{ route('produtos', ['slug' => 'my-web']) }}">
                                <div class="block-produto seguranca">
                                    <div class="top">
                                        <img src="{{url('assets/images/svg-pc-plug.svg')}}" alt="" />
                                    </div>
                                    <div class="bottom">
                                        <h3>MY WEB</h3>
                                        <p>Com uma <strong>rede privativa (VPN)</strong>, a troca de informações trafega com proteção e segurança.</p>
                                        <div class="mais fright"> + </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="column column-4">
                            <a class="fleft" href="{{ route('produtos', ['slug' => 'b-safe']) }}">
                                <div class="block-produto seguranca seis">
                                    <div class="top">
                                        <img src="{{url('assets/images/svg-seguranca.svg')}}" alt="" />
                                    </div>
                                    <div class="bottom">
                                        <h3>B SAFE</h3>
                                        <p>Soluções para <strong>detectar ameaças e proteger</strong> seus dados contra possíveis ataques.</p>
                                        <div class="mais fright"> + </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column column-4">
                            <a class="fleft" href="{{ route('produtos', ['slug' => 'smart-box']) }}">
                                <div class="block-produto telefonia smart-box">
                                    <div class="top">
                                        <img src="{{url('assets/images/png-central-telefonica.png')}}" alt="" />
                                    </div>
                                    <div class="bottom">
                                        <h3>SMART BOX</h3>
                                        <p>A central telefônica que cresce com sua empresa. Muitas facilidades em uma <strong>única central telefônica inteligente.</strong></p>
                                        <div class="mais fright"> + </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Produtos -->

        <!-- Pacotes -->
        <section id="pacotes">
            @include('../site/shared/pacotes')
        </section>
        <!-- End Pacotes -->
    </main>

    <!-- Contato -->
    <section id="contato-produtos">
        @include('../site/shared/solicite-produto',['produto' => 'produtos'])
    </section>
    <!-- End Contato -->

    <!-- Noticias -->
    <?php get_template_part('noticias','home'); ?>
    <!-- End Noticias -->
@endsection

@section('script')
    <script src="{{url('js/send_mail.js')}}"></script>
@endsection