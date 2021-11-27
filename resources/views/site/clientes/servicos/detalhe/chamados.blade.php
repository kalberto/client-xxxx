<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 26/12/2017
 * Time: 13:38
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 26/12/2017
 * Time: 13:38
 */
?>
<section class="servicos-chamados">
    <header>
        <h2>Histórico de Chamados</h2>
        <span class="subtitulo texto-laranja">{{$item['nome']}}</span>
    </header>
    <table class="default">
        <tbody>
            @foreach($chamados as $chamado)
                <tr>
                    <td class="texto-laranja" data-text="Número de Protocolo">{{$chamado['protocolo']}}</td>
                    <td data-text="DATA DE ABERTURA">{{ $chamado['data_abertura'] }}</td>
                    <td data-text="CATEGORIA">{{ $chamado['categoria'] }}</td>
                    <td data-text="STATUS">{{ $chamado['status'] }}</td>
                    <td class="botoes"><a class="botao" href="{{$chamado['link']}}">Detalhes</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>

