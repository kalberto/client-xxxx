<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 26/12/2017
 * Time: 13:41
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 26/12/2017
 * Time: 13:53
 */
?>
<section class="servicos-dados">
    <header>
        <h2>Dados do Serviço</h2>
    </header>
    <table class="default">
        <tbody>
            <tr>
                {{--<td data-text="Velocidade">{{$servico['velocidade']}}mb/s</td>
                <td data-text="Status">{{$servico['status']}}</td>--}}
                <td data-text="DATA DE ATIVAÇÃO">{{$servico['data_ativacao']}}</td>
                <td data-text="DESIGNADOR">{{$servico['id']}}</td>
                <td data-text="DATA DE VENCIMENTO">{{$servico['data_vencimento']}}</td>
                <td data-text="PERÍDODO DE APURAÇÃO">{{$servico['periodo_apuracao']}}</td>
            </tr>
        </tbody>
    </table>
</section>