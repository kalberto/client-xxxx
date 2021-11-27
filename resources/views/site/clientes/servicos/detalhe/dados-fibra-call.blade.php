<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 26/12/2017
 * Time: 13:44
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 02/01/2018
 * Time: 14:00
 */
?>
<section class="servicos-fibracall">
    <header>
        <h2>Dados do Serviço FibraCall</h2>
    </header>
    <table class="default">
        <tbody>
            <tr>
                <td data-text="DOMÍNIO">{{$dados['dominio']}}</td>
                <td data-text="TIPO DE ACESSO">{{$dados['tipo_acesso']}}</td>
                <td data-text="NÚMERO DE CANAIS">{{$dados['numero_canais']}}</td>
                <td data-text="PROFILE">{{$dados['profile']}}</td>
                <td data-text="PROXY">{{$dados['proxy']}}</td>
                <td data-text="POSSUI QUOTA DIÁRIA">{{$dados['p_quota_diaria']}}</td>
            </tr>
            <tr>
                <td data-text="PLANO DE TARIFAS">{{$dados['plano_tarifa']}}</td>
                <td data-text="PILOTO">{{$dados['piloto']}}</td>
                <td data-text="QUOTA - LIMITE DIÁRIO">{{$dados['quota_diaria']}}</td>
                <td data-text="CADÊNCIA">{{$dados['cadencia']}}</td>
                <td data-text="CONSUMO PARCIAL">{{$dados['consumo_parcial']}}</td>
                <td data-text="QUOTA - CONSUMO DESDE ZERO HORAS:">{{$dados['consumo_quota']}}</td>
            </tr>
        </tbody>
    </table>
</section>