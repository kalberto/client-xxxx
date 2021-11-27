<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 02/01/2018
 * Time: 11:59
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 02/01/2018
 * Time: 11:59
 */
?>
<section class="servicos-ipv6">
    <header>
        <h2>Endereçamento de IPv6</h2>
    </header>
    <table class="default two-columns">
        <tbody>
            @foreach($enderecamento_ipv6 as $enderecamento)
                <tr>
                    <td data-text="GATEWAY">{{$enderecamento['gateway']}}</td>
                    <td data-text="IP DO CLIENTE">{{$enderecamento['ip_cliente']}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>