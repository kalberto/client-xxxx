@extends('site.structure')

@section('head')
	<title>xxxx | Produtos</title>
    <meta name="description" content="">
    <link rel="stylesheet" href="{{ url('css/comparativo.css') }}">
@endsection

@section('header')
	<picture class="picture-mob">
        <source srcset="{{url('assets/images/background/produtos/bg-produtos-479x400.jpg')}}" media="(max-width: 479px)">
        <source srcset="{{url('assets/images/background/produtos/bg-produtos-667x550.jpg')}}" media="(max-width: 667px)">
        <source srcset="{{url('assets/images/background/produtos/bg-produtos-768x550.jpg')}}" media="(max-width: 768px)">
        <source srcset="{{url('assets/images/background/produtos/bg-produtos.jpg')}}">
        <img src="{{url('assets/images/background/produtos/bg-produtos.jpg')}}" alt="Rede xxxx"/>
	</picture>
@endsection

@section('content')
	<main class="conteudo-comparativoFastpack">
		<section>
			<div class="full-width pad50">
				<div class="center">
					<table class="comparativoFastpack">
                        <tr class="maior">
                            <td colspan="2"></td>
                            <th colspan="2" class='variacao1 border'>
                                <strong>
                                    Conheça abaixo os serviços xxxx e <br> descubra qual se encaixa ao seu perfil.
                                </strong>  
                            </th>
                        </tr>

                        <tr class="maior">
                            <td colspan="2"></td>
                            <td class='variacao1'>
                                Corporativo
                                <img class="logo" src="{{ url('assets/images/svg/logo-xxxx-telecom.svg') }}" alt="">
                            </td>
                            <td class='variacao2'>
                                Empresarial
                                <img class="logo" src="{{ url('assets/images/produtos/svg/logo_fastpack.svg') }}" alt="">
                                
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2" class='variacao9'>Forma de venda</th>
                            <td class='variacao1' data-caption="Forma de venda">Customizado de <br> acordo com a <br> necessidade do cliente</td>
                            <td class='variacao2'>Combo com produtos <br> pré-definidos e adequado <br> as empresas de pequeno porte</td>
                        </tr>

                        <tr>
                            <th rowspan="3" class='variacao6 pacoteComparativo'>Telefonia</th>
                            <th class='variacao5 textLeft'>
                                <img src="{{ url('assets/images/icons/voz.png') }}" alt="">
                                Plano de voz
                            </th>
                            <td class='variacao3' data-caption="Plano de voz">Sem limite de linhas</td>
                            <td class='variacao4'>Até 5 linhas</td>
                        </tr>

                        <tr>
                            <th class='variacao5 textLeft'>
                                <img src="{{ url('assets/images/icons/0800.png') }}" alt="">
                                0800
                            </th>
                            <td class='variacao3' data-caption="0800">Disponível</td>
                            <td class='variacao4'>Disponível</td>
                        </tr>

                        <tr>
                            <th class='variacao5 textLeft'>
                                <img src="{{ url('assets/images/icons/pabx.png') }}" alt="">
                                PABX virtual
                            </th>
                            <td class='variacao3' data-caption="PABX virtual">Sem limite de linhas</td>
                            <td class='variacao4'>Até 5 linhas</td>
                        </tr>

                        <tr>
                            <th rowspan="2" class='variacao6 pacoteComparativo'>
                                Internet
                            </th>
                            <th class='variacao5 textLeft'>
                                <img src="{{ url('assets/images/icons/download.png') }}" alt="">
                                Download
                            </th>
                            <td class='variacao3' data-caption="Download">Até 10 Gb</td> 
                            <td class='variacao4'>50 Mbps, <br> 100 Mbps <br> ou 150 Mbps</td>
                        </tr>

                        <tr>
                            <th class='variacao5 textLeft'>
                                <img src="{{ url('assets/images/icons/upload.png') }}" alt="">
                                Upload
                            </th>
                            <td class='variacao3' data-caption="Upload">100% da velocidade contratada</td>
                            <td class='variacao4'>50% da velocidade contratada</td>
                        </tr>

                        <tr>
                            <td class='variacao10 pacoteComparativo'></td>
                            <th class='variacao7 textLeft'>
                                <img src="{{ url('assets/images/icons/ip.png') }}" alt="">
                                IP Fixo
                            </th>
                            <td class='variacao8' data-caption="IP Fixo">Disponível</td>
                            <td class='variacao7'>Indisponível</td>
                        </tr>

                        <tr>
                            <td class='variacao10 pacoteComparativo'></td>
                            <th class='variacao7 textLeft'>
                                <img src="{{ url('assets/images/icons/vpn.png') }}" alt="">
                                VPN
                            </th>
                            <td class='variacao8' data-caption="VPN">Disponível</td>
                            <td class='variacao7'>Indisponível</td>
                        </tr>

                        <tr>
                            <td class='variacao10 pacoteComparativo'></td>
                            <th class='variacao7 textLeft'>
                                <img src="{{ url('assets/images/icons/backup.png') }}" alt="">
                                Serviço de Backup
                            </th>
                            <td class='variacao8' data-caption="Serviço de Backup">Customizado <br> pelo cliente</td>
                            <td class='variacao7'>50 Gb, <br> 100 Gb <br> ou 200 Gb</td>
                        </tr>
                    </table>

                    <a href="{{ route('produtos.fastpack') }}" id="saibaMaisFastpack" class="btn fright saibaMaisComparativo">
                        <div class="claro side">
                            saiba mais sobre o 
                            <br> Fastpack
                        </div>
                    </a>
                    <a href="{{ route('produtos') }}" id="saibaMaisxxxx" class="btn fright saibaMaisComparativo">
                        <div class="claro side">
                            saiba mais sobre o 
                            <br> Corporativo xxxx
                        </div>
                    </a>
				</div>
			</div>
		</section>
	</main>
@endsection

@section('script')
    <script src="{{ url('js/comparativo.fastpack.js') }}"></script>
@endsection