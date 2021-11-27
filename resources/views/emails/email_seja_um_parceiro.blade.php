<html>
<head>
    <meta charset="UTF-8">
    <title>{{$subject}}</title>
</head>
<body>

<table width="550" align="center"; cellpadding="0" cellspacing="0" border="2" bordercolor="#0d392b">
    <tr><td>

            <table width="550" align="center"; cellpadding="0" cellspacing="0" border="0">
                <tr><td height="20"></td></tr>
                <tr>
                    <td align="center">
                        <table width="480">
                            <tr>
                                <td style="font-family: 'Trebuchet MS', Arial, sans-serif;">
                                    <p style="font-family: 'Trebuchet MS', Arial, sans-serif; color: #8d0b0e;"><strong>Formulário de seja um parceiro enviado através do site</strong></p>
                                    <p style="font-family: 'Trebuchet MS', Arial, sans-serif; font-size: 14px;">
	                                    @if(isset($data['documento']))
		                                    <strong>Documento:</strong> {{ $data['documento'] }} <br/>
	                                    @endif
	                                    @if(isset($data['data']['nome']))
		                                    <strong>Nome:</strong>{{$data['data']['nome']}} <br>
	                                    @endif
	                                    @if(isset($data['data']['email']))
		                                    <strong>E-mail:</strong> {{ $data['data']['email'] }} <br/>
	                                    @endif
	                                    @if(isset($data['data']['telefone']))
		                                    <strong>Número:</strong>{{$data['data']['telefone']}} <br>
	                                    @endif
	                                    @if(isset($data['data']['razao_social']))
		                                    <strong>Razão social:</strong> {{ $data['data']['razao_social'] }} <br/>
	                                    @endif
	                                    @if(isset($data['data']['endereco']))
		                                    <strong>Endereço:</strong> {{ $data['data']['endereco']}} <br/>
	                                    @endif
	                                    @if(isset($data['data']['uf']) && isset($data['data']['cidade']))
		                                    <strong>Cidade:</strong> {{ $data['data']['cidade'].'-'.$data['data']['uf']}} <br/>
	                                    @endif
	                                    @if(isset($data['data']['ramo_atuacao']))
		                                    <strong>Ramo atuação:</strong> {{ $data['data']['ramo_atuacao']}} <br/>
	                                    @endif
	                                    @if(isset($data['data']['redes_sociais']) && sizeof($data['data']['redes_sociais']) > 0)
		                                    <strong>Redes Sociais:</strong> <br/>
		                                    @foreach($data['data']['redes_sociais'] as $item)
			                                    {{ $item }} <br/>
		                                    @endforeach
	                                    @endif
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><td height="20"></td></tr>
            </table>

        </td></tr>
</table>

</body>
</html>