@extends('site.web')

@section('body_content')
    <style>
        #cookies {
            position: fixed; 
            bottom: 20px; left: 20px; max-width: 280px; background: #fff; border-radius: 10px; 
            padding: 10px 10px 20px 10px; border: 1px solid #e75629;
            z-index: 11;
        }
        #cookies button{
        display: block; color: #fff; font-size: 12px; text-align: left; padding: 4px 10px;
        border: none; background: #e75629; text-transform: uppercase; font-weight: bold; border-radius: 3px
        }
        #cookies p{
            color: #000; font-size: 14px; line-height: 16px; margin-bottom: 10px;
        }
        #cookies p a{
            color: #000; text-decoration: underline; color: #e75629;
        }
        #cookies.ativo{
            display: none
        }
    </style>
    <script>
        function fechar() {
            document.querySelector('#cookies').classList.add('ativo')
        }
    </script>
    {{-- Tag Manager (noscript) --}}
    @include('site.shared.mainComponents.tag-manager')

    {{-- Header --}}
    @include('site.shared.mainComponents.header')

    {{-- Content --}}
    @yield('content')

    <div id="cookies">
        <p>Este site utiliza cookies para uma experiência personalizada de navegação. <a href="politica-de-privacidade">Saiba mais.</a></p>
        <button onclick="fechar()">Prosseguir</button>
    </div>
    {{-- Footer --}}
    @include('site.shared.mainComponents.footer')

    <?php wp_footer(); ?>
    <script type="text/javascript"> var base_url = '{{url("/")}}'; </script>
    <script src="{{ url('js/jquery-2.1.1.js') }}"></script>
    <script src="{{ url('js/main.js') }}"></script>
    @yield('script')

@endsection