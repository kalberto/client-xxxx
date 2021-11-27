<footer>
    <div class="full-width laranja rodape pad">
        <div class="center">
            <div class="row">
                <div class="column column-3">
                    <img src="{{url("assets/images/svg/logo-xxxx-telecom-branco.svg")}}" alt="Logo da xxxx" />
                    <div id="anchor" class="fright btn-scroll only-mob">V</div>
                </div>
                <div class="column column-4 only-desk">
                    <h5>MAPA DO SITE</h5>
                    <ul>
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('institucional') }}">Institucional</a></li>
                        <li><a href="https://blog.xxxx.com">Blog</a></li>
                        <li><a href="{{ url('contato') }}">Contato</a></li>
                        <li style="margin-top:10px;"><a target="_blank" href="{{ url('https://www.xxxx.com/documentoslegais/') }}">Documentos Legais</a></li>
                    </ul>
                </div>
                <div class="column column-5 only-desk">
                    <h5>CONTATO</h5>
                    <h6 class="fone">Telefone</h6>
                    <ul>
                        <li>0800 604 3939</li>
                    </ul>
                    <h6 class="mail">E-mail:</h6>
                    <ul>
                        <li><a href="mailto:contato@xxxx.com" >contato@xxxx.com</a></li>
                    </ul>
                    <h6 class="endereco">Endereço:</h6>
                    <ul>
                        <li>Rua 13 de Maio, 1062 - Curitiba</li>
                        <li>CEP: 80.510-030</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="full-width cinza copy">
        <div class="center">
            <p>Copyright <?php echo date('Y'); ?>. xxxx - Todos os direitos reservados.</p>
            <div class="social fright">
                <a target="_blank" href="https://www.facebook.com/xxxx/">
                    <img width="11" class="fleft" src="{{url('assets/images/ico-fb.svg')}}" style="width:11px;" />
                </a>
                <a target="_blank" href="https://www.linkedin.com/company/xxxx-telecomunica-es-e-tecnologia-ltda">
                    <img width="25" class="fleft" src="{{url('assets/images/ico-linkedin.svg')}}" style="width:25px;" />
                </a>
            </div>
        </div>
    </div>
</footer>