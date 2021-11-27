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
                                    <p style="font-family: 'Trebuchet MS', Arial, sans-serif; color: #8d0b0e;"><strong>Formulário de contato enviado através do site</strong></p>
                                    <p style="font-family: 'Trebuchet MS', Arial, sans-serif; font-size: 14px;">
	                                    @if(isset($data['produto']))
		                                    <strong>Produto:</strong> {{ $data['produto'] }} <br/>
	                                    @endif
                                        @if(isset($data['assunto']))
                                            <strong>Assunto:</strong>{{$data['assunto']}} <br>
                                        @endif
                                        @if(isset($data['empresa']))
                                            <strong>Empresa:</strong> {{ $data['empresa'] }} <br/>
                                        @endif
                                        @if(isset($data['nome']))
                                            <strong>Nome:</strong> {{ $data['nome'] }} <br/>
                                        @endif
                                        @if(isset($data['contato']))
		                                    <strong>Contato:</strong>{{$data['contato']}} <br>
	                                    @endif
                                        @if(isset($data['email']))
                                            <strong>E-mail:</strong> {{ $data['email'] }} <br/>
                                        @endif
                                        @if(isset($data['telefone']))
                                            <strong>Telefone:</strong> {{ $data['telefone'] }} <br/>
                                        @endif
	                                    @if(isset($data['endereco']))
		                                    <strong>Endereço:</strong> {{ $data['endereco']}} <br/>
	                                    @endif
	                                    @if(isset($data['numero']))
		                                    <strong>Número:</strong> {{ $data['numero']}} <br/>
	                                    @endif
	                                    @if(isset($data['cep']))
		                                    <strong>CEP:</strong> {{ $data['cep']}} <br/>
	                                    @endif
	                                    @if(isset($data['mensagem']))
		                                    <strong>Mensagem:</strong> {{ $data['mensagem'] }} <br/>
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