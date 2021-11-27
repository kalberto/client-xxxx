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
@endsection

@section('content')
    <main class="">
        <section class="pad50bot">
            <div class="full-width">
                <div class="center" style="padding:50px 20px 20px">
                    <h3 >Prezado usuário visitante,</h3>
                    <p>A xxxx preza pela segurança e privacidade dos dados cedidos pelos usuários e clientes.
                            Por meio dos formulários de contato, são coletadas informações pessoais que serão utilizadas posteriormente
                            a fim de traçar um perfil dos usuários que acessam o site e para que seja efetuado contato pelo setor responsável.
                            Essas informações não serão vendidas, repassadas ou utilizadas sem a devida autorização. Além disso, utilizamos as
                            informações do seu navegador para retargeting, remarketing e propagandas personalizadas.<br><br
                        >O conteúdo de nossas políticas de privacidade pode ser alterado a qualquer momento, sem aviso prévio.
                    </p>
                </div>
            </div>
        </section>
    </main>
@endsection