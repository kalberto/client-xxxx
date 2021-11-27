@extends('site/structure')

@section('head')
    <title>xxxx | Contato</title>
    <meta name="description" content="">
@endsection

@section('header')
    <picture class="picture-mob">
        <source srcset="{{url('assets/images/background/contato/bg-contato-479x400.jpg')}}" media="(max-width: 479px)">
        <source srcset="{{url('assets/images/background/contato/bg-contato-667x550.jpg')}}" media="(max-width: 667px)">
        <source srcset="{{url('assets/images/background/contato/bg-contato-768x550.jpg')}}" media="(max-width: 768px)">
        <source srcset="{{url('assets/images/background/contato/bg-contato.jpg')}}">
        <img src="{{url('assets/images/background/contato/bg-contato.jpg')}}" alt="Atendente xxxx"/>
    </picture>

    <div class="full-width destaque">
        <div class="center">
            <div class="block-g fright" >
                <div class="block fleft">
                    <h1>FALE COM A <span>xxxx</span></h1>
                    <p>NOSSOS TÉCNICOS VÃO OFERECER UMA <strong>RESPOSTA SOB MEDIDA PARA A SUA EMPRESA</strong> MOSTRAR O SEU MELHOR.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="full-width only-desk">
        <div class="center contato">
            <div class="fright laranja telefone">
                <ul>
                    <li>Ligue agora para:</li>
                    <li>0800 604 3939</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <main class="conteudo-contato">
        <section>
            <a name="contato" style="visibility: hidden; display: block; height: 0;"></a>
            <div class="full-width">
                <div class="center">
                    <div class="titulo fleft">
                        <h2>CONTATO</h2>
                        <p class="only-desk">ENVIE SUA DÚVIDA, CRÍTICA OU SUGESTÃO</p>
                    </div>
                    <div class="fright laranja telefone only-mob fleft">
                        <ul>
                            <li>Ligue agora para:</li>
                            <li>0800 604 3939</li>
                        </ul>
                    </div>
                    <div id="div_for_message" class="form-contato fleft  pad50bot">
                        <form id="contato_form" action="" name="contato_form" onsubmit="dataLayer.push({'Componente': 'CTA', 'Action': 'Enviar', 'event': 'form-contato'});dataLayer.push({'event': 'conversao', 'produto':'contato'});" method="post">
                            <div class="row">
                                <div class="column column-12">
                                    <input type="text" placeholder="Empresa" name="empresa">
                                </div>
                            </div>
                            <div class="row">
                                <div class="column column-12">
                                    <input type="text" placeholder="Contato" name="contato">
                                </div>
                            </div>
                            <div class="row">
                                <div class="column column-6">
                                    <input type="text" placeholder="E-mail" name="email">
                                </div>
                                <div class="column column-6">
                                    <input type="text" placeholder="Telefone" name="telefone">
                                </div>
                            </div>
                            <div class="row">
                                <div class="column column-12">
                                    <select name="assunto">
                                        <option data-form="1" value="1">Escolha uma opção</option>
                                        <option data-form="informacoes" value="Informacoes">Informações</option>
                                        <option data-form="reclamacoes" value="Reclamacoes">Reclamações</option>
                                        <option data-form="elogios" value="Elogios">Elogios</option>
                                        <option data-form="produtos-servicos" value="Produtos/Servicos">Produtos/Serviços</option>
                                        <option data-form="trabalhe-conosco" value="trabalhe-conosco">Trabalhe conosco</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column column-12">
                                    <textarea rows="5" placeholder="Mensagem" name="mensagem"></textarea>
                                </div>
                            </div>
                            <div class="column column-12">
                                {!! csrf_field() !!}
                                <input id="contato" class="btn-enviar fright" name="BTEnvia" type="submit" value="ENVIAR" onsubmit="dataLayer.push({'Componente': 'CTA', 'Action': 'Enviar', 'event': 'form-contato'});">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('script')
<script src="{{url('js/send_mail.js')}}"></script>
@endsection