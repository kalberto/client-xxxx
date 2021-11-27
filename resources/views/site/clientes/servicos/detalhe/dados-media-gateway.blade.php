<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 26/12/2017
 * Time: 13:47
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 02/01/2018
 * Time: 14:00
 */
?>
<section class="servicos-mediagateway">
    <header>
        <h2>Dados do Serviço Media Gateway</h2>
    </header>
    <table class="default">
        <tbody>
            <tr>
                <td data-text="ENDEREÇO IP">{{$dados['endereco_ip']}}</td>
                <td data-text="IDENTIFICADOR">{{$dados['identificador']}}</td>
                <td data-text="CANAIS">{{$dados['canais']}}</td>
                <td data-text="MODELO">{{$dados['modelo']}}</td>
                <td data-text="SINALIZAÇÃO">{{$dados['sinalizacao']}}</td>
                <td data-text="FORNECEDOR">{{$dados['fornecedor']}}</td>
            </tr>
        </tbody>
    </table>
</section>
