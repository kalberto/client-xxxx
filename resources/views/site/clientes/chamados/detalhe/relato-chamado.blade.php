<div class="relato-solicitacao">
    <table class="detail">
        <tr>
            <td>
                <dl>
                    <dt>Solicitante: </dt>
                    <dd>{{$chamado['solicitante']}}</dd>

                    <dt>Contato:</dt>
                    <dd>{{$chamado['contato']}}</dd>

                    <dt>Assunto:</dt>
                    <dd>{{$chamado['assunto']}}</dd>
                </dl>
            </td>
            <td>
                <dl>
                    <dt>Tipo: </dt>
                    <dd>{{$chamado['tipo']}}</dd>

                    <dt>Status:</dt>
                    <dd>{{$chamado['status']}}</dd>

                    <dt>Última Atualização:</dt>
                    <dd>{{$chamado['ultima_atualizacao']}}</dd>
                </dl>
            </td>
        </tr>
    </table>
</div>