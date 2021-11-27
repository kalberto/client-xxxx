<html>
<head>
    <meta charset="UTF-8">
    <title>Contato</title>
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
                                        <strong>Empresa:</strong> {{ $empresa }} <br/>
                                        <strong>Contato:</strong> {{ $contato }} <br/>
                                        <strong>E-mail:</strong> {{ $email }} <br/>
                                        <strong>Telefone:</strong> {{ $telefone }} <br/>
                                        <strong>Cep:</strong> {{ isset($cep) ? $cep : '' }} <br/>
                                        <strong>Mensagem:</strong> {{ $mensagem }} <br/>
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