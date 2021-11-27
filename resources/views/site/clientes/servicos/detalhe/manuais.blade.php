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
<section class="servicos-manuais">
    <header>
        <h2>Manuais do Produto</h2>
    </header>
    <table class="default">
        <tbody>
            @foreach($manuais as $manual)
                <tr>
                    <td class="texto-esquerda">{{$manual['nome']}}</td>
                    <td class="texto-direita"><a href="{{url($manual['url'])}}" class="botao">Download</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>