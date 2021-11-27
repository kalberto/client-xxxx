<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 01/12/2017
 * Time: 14:11
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 01/12/2017
 * Time: 14:11
 */
?>
<!DOCTYPE html>
<html ng-app="app">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#104232">
    <link rel="stylesheet" href="{{ url('css/main.css') }}">
    {{-- <link rel="stylesheet" href="{{ url('css/main-custom.css') }}"> --}}
    <link rel="stylesheet" href="{{ url('css/area-cliente.css') }}">
    <link rel="icon" type="image/png" href="{{url('favicon.ico')}}" sizes="32x32">
    @yield('head')
</head>
<body id="AreaCliente">

{{-- HEADER --}}
<header class="menu-topo">
    @yield('header')
    <div class="menu-logo fleft">
        <div class="menu-mob">
            <a href="#" class="menu-btn">
                <span></span>
                <span></span>
                <span></span>
            </a>
        </div>
        <a class="logo" href="{{url('cliente/')}}">
            <img src="{{url('assets/images/svg/logo-xxxx-telecom.svg')}}" alt="Logo da xxxx">
            <img class="mob" src="{{url('assets/images/svg/logo-xxxx-symbol.svg')}}" alt="Logo da xxxx">
        </a>
    </div>
    <div class="log-opcoes fleft">
    	<div class="usuario" href="#">
    		<div class="detalhes">
                <p class="nome">Olá, {{$usuario['nome']}}</p>
                @if(isset($usuario['ultimo_acesso']) && $usuario['ultimo_acesso']!= false)
                    <p class="ult acesso" ng-if="{{isset($usuario['ultimo_acesso'])}}">Último acesso em {{$usuario['ultimo_acesso']}}</p>
                @endif
                @if(isset($usuario['ultimo_acesso']) && $usuario['ultimo_acesso'] == false)
                    <p class="pri acesso" ng-if="{{isset($usuario['ultimo_acesso'])}}">Primeiro acesso</p>
                @endif
    		</div>
    		<div class="foto">
                @if(isset($usuario['media']))
                    <img src="{{url($usuario['media']->media_root->path.$usuario['media']->nome )}}" alt="{{$usuario['nome']}}" width="40" height="40">
                @else
                    <img src="{{url('assets/images/cliente/ico-usuario-semfoto.png')}}" alt="{{$usuario['nome']}}" width="40" height="40">
                @endif
    		</div>

            <ul class="menu-usuario">
                <li><a href="{{url('cliente/perfil')}}">Meu Perfil</a></li>
                <li><a class="logout" href="{{url('clientes/auth/logout')}}">Logout</a></li>
            </ul>

    	</div>
    </div>
</header>


<div class="content">

    {{-- Menu Lateral --}}
	<aside class="menu-lateral">
		<nav class="js-scroll">
			<ul>
				@foreach($usuario['menu'] as $menu_user)
                    @if(isset($menu) && $menu == $menu_user['url'])
                        <li><a class="selected-menu" href="{{url($menu_user['url'])}}">{{$menu_user['nome']}}</a></li>
                    @else
                        <li><a href="{{url($menu_user['url'])}}">{{$menu_user['nome']}}</a></li>
                    @endif

				@endforeach
			</ul>
		</nav>
    </aside>

    {{-- Main Loader --}}
    <div class="main-loader hide">
        <div class="loader">
            Carregando
            <div class="anim">
                <div></div>
                <div></div>
            </div>
        </div>
    </div>

    {{-- Conteudo principal --}}
	<main class="main-content">
        <div class="wrapper" ng-app="app">
		  @yield('content')
        </div>
	</main>

</div>
<footer>
    @include('site.footer')
</footer>
</body>
<script type="text/javascript">
    var base_url = '{{url("/")}}';
</script>
<script src="{{ url('js/jquery-2.1.1.js') }}"></script>
<script src="{{ url('js/main.js') }}"></script>
<script src="{{ url('js/amcharts/amcharts.js') }}"></script>
<script src="{{ url('js/amcharts/lang/pt.js') }}"></script>
<script src="{{ url('js/vendor.js') }}"></script>
<script src="{{ url('js/amChartsDirective.js') }}"></script>
<script src="{{ url('js/app.js') }}"></script>
<script src="{{ url('js/services.js') }}"></script>
@yield('script')
</html>
