<header class="header" id="header">
    <a name="top" style="visibility: hidden; display: block; height: 0;"></a>
    <nav class="menu laranja">
        <div class="logo">
            <img src="{{url('assets/images/svg/logo-xxxx-telecom.svg')}}" alt="Logo da xxxx" />
        </div>
        <ul class="only-desk">
            <li id="nav-home"><a href="{{ url('/') }}">HOME</a></li>
            <li id="nav-produtos"><a href="{{ url('institucional')  }}">INSTITUCIONAL</a></li>
            <li id="nav-noticias"><a href="https://blog.xxxx.com/">BLOG</a></li>
            <li id="nav-contato"><a href="{{ url('contato') }}">CONTATO</a></li>
        </ul>
        <a id="nav-clientes" class="only-desk" href="{{ url('cliente/') }}">Área do Cliente</a>
        <h3 id="menu-toggle" class="only-mob"> </h3>
        <ul id="menu">
            <li><a href="{{ url('cliente/') }}">Área do Cliente</a></li>
            <li><a href="{{ url('/') }}">HOME</a></li>
            <li><a href="{{ url('institucional') }}">Institucional</a></li>
            <li><a href="https://blog.xxxx.com/">BLOG</a></li>
            <li><a href="{{ url('contato') }}">CONTATO</a></li>
        </ul>
    </nav>

    @yield('header')
</header>