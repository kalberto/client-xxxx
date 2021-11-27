<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 26/12/2017
 * Time: 13:39
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 28/12/2017
 * Time: 16:33
 */
?>
<section class="servicos-ipv4">
    <header>
        <h2>Endereçamento de IP</h2>
    </header>
    <table class="default">
        <tbody>
            @foreach($enderecamentos as $enderecamento)
                <tr>
                    <td data-text="ENDEREÇO DA REDE">{{$enderecamento['network']}}</td>
                    <td data-text="ENDEREÇO DO GATEWAY">{{$enderecamento['gateway']}}</td>
                    <td data-text="MASCÁRA DE SUBREDE">{{$enderecamento['mask']}}</td>
                    <td data-text="ENDEREÇOS IP UTILIZÁVEIS DO CLIENTE">{{$enderecamento['usable']}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>