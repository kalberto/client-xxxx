<a name="contato-produtos" style="visibility: hidden; display: block; height: 0;"></a>
<div class="full-width formulario">
    <div class="center">
        <div class="titulo fleft">
            <h2 class="only-desk">SOLICITE OS PRODUTOS xxxx</h2>
            <h2 class="only-mob">SOLICITE</h2>
            <p class="only-desk">PERSONALIZADOS PARA SUA EMPRESA </p>
        </div>
        <form id="produto_form" name="produto_form" method="POST" onsubmit="dataLayer.push({'Componente': 'CTA', 'Action': 'Enviar', 'event': 'form-contato'});dataLayer.push({'event': 'conversao', 'produto':'{{$produto}}'});">
            <div class="row form">
                <div class="column column-6">
                    <input type="text" placeholder="Empresa" name="empresa">
                </div>
                <div class="column column-6">
                    <input type="text" placeholder="Contato" name="contato">
                </div>
            </div>
            <div class="row form">
                <div class="column column-6">
                    <input type="text" placeholder="E-mail" name="email">
                </div>
                <div class="column column-6">
                    <input type="text" placeholder="Telefone" name="telefone">
                </div>
            </div>
            <div class="row form">
                <div class="column column-12">
                    <textarea rows="4" placeholder="Mensagem" name="mensagem"></textarea>
                </div>
            </div>
            <input id="slug" name="slug" value="produtos" hidden>
            <div class="row">
                <div class="column column-12">
                    {!! csrf_field() !!}
                    <input id="contato" class="btn-enviar fright" type="submit" name="BTEnvia" onsubmit="dataLayer.push({'Componente': 'CTA', 'Action': 'Enviar', 'event': 'form-pagina-produtos'});" value="ENVIAR">
                </div>
            </div>
        </form>
    </div>
</div>