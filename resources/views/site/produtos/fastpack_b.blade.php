@extends('site.web')

@section('head')
	<title>xxxx | Produtos | Fastpack</title>
	<meta name="description" content="Fastpack">
	<link rel="stylesheet" href="{{ url('css/produto.fastpack.css') }}">
@endsection

@section('body_content')

    {{-- Tag Manager (noscript) --}}
    @include('site.shared.mainComponents.tag-manager')
	
    {{-- Content --}}
   	<main id="app" class="fastpack-b">
		<a name="top" style="visibility: hidden; display: block; height: 0;"></a>
		@include('site.produtos.fastpack_b.section-topo')
		@include('site.produtos.fastpack_b.section-beneficios')
		@include('site.produtos.fastpack_b.section-contato')
	</main>

    {{-- Footer --}}
    @include('site.shared.mainComponents.footer')

    <?php wp_footer(); ?>
    <script type="text/javascript"> var base_url = '{{url("/")}}'; </script>
	<script src="{{ url('js/jquery-2.1.1.js') }}"></script>
	<script src="{{ url('js/jquery.mask.min.js') }}"></script>
    <script src="{{ url('js/main.js') }}"></script>
	<script src="{{ url('js/produto.fastpack.js') }}"></script>

@endsection