$(document).ready(function() {

    $("#contato_form").on('focus', '.erro', function(){
        $(this).removeClass('erro');
    });
    $("#contato_form").submit(function (event) {

        event.preventDefault();

        var url = base_url + '/contato/send-mail';

        var data = $("#contato_form").serialize();

        if( $('.form-contato .erros').length > 0){
            $('.form-contato .erros').remove();
        }
        $.ajax({
            type:'POST',
            url:url,
            data:data,
            dataType:'json'
        }).done(function (data) {
            var html = '<div class="overlay ativo"><div class="box-branco"><img src="assets/images/icone-mensagem-enviada.svg" /><p class="primeiro">SUA MENSAGEM FOI ENVIADA COM SUCESSO!</p><p  class="segundo">EM BREVE ENTRAREMOS EM CONTATO.</p><div id="fechar" class="btn"><div class="side claro">FECHAR</div><div class="both"></div></div><div class="both"></div></div></div>';
            $('html, body').animate({
                scrollTop: $("#header").offset().top
            }, 600);
            $('body').append(html);
            //$('#contato_form')[0].reset();

            //window.scrollTo(0,0);
        }).fail(function(data){
            var inputNames, errorTexts;

            var errorInputs = data.responseJSON.error_validate
            inputNames = Object.getOwnPropertyNames(errorInputs);
            errorTexts = Object.values(errorInputs);

            //para cada erro, procura um input para colocar a classe
            inputNames.forEach(function(c,i){
                $('#contato_form').find('[name='+c+']').addClass('erro');
            });

            $('.form-contato').append('<div class="erros"></div>')

            errorTexts.forEach(function(c,i){
                $('.form-contato .erros').append('<p>'+c+'</p>');
            });

        })

    });

    $("#produto_form").on('focus', '.erro', function(){
        $(this).removeClass('erro');
    });
    $("#produto_form").submit(function (event) {

        event.preventDefault();
        var data = $("#produto_form").serialize();
        var url = base_url + '/contato/send-produto-mail/'+$("#slug").val();
        $('.formulario').css("padding-bottom","30px");

        if( $('.formulario .center .erros').length > 0){
            $('.formulario .center .erros').remove();
        }

        $.ajax({
            type:'POST',
            url:url,
            data:data,
            dataType:'json'
        }).done(function (data) {
            var html = '<div class="overlay ativo"><div class="box-branco"><img src="assets/images/icone-mensagem-enviada.svg" /><p class="primeiro">SUA MENSAGEM FOI ENVIADA COM SUCESSO!</p><p  class="segundo">EM BREVE ENTRAREMOS EM CONTATO.</p><div id="fechar" class="btn"><div class="side claro">FECHAR</div><div class="both"></div></div><div class="both"></div></div></div>';
            $('html, body').animate({
                scrollTop: $("#header").offset().top
            }, 1000);
            $('body').append(html);
            $('#produto_form')[0].reset();
        }).fail(function(data){
            var inputNames, errorTexts;

            var errorInputs = data.responseJSON.error_validate
            inputNames = Object.getOwnPropertyNames(errorInputs);
            errorTexts = Object.values(errorInputs);

            //para cada erro, procura um input para colocar a classe
            inputNames.forEach(function(c,i){
                $('#produto_form').find('[name='+c+']').addClass('erro');
            });

            $('.formulario .center').append('<div class="erros"></div>')

            errorTexts.forEach(function(c,i){
                $('.formulario .center .erros').append('<p>'+c+'</p>');
            });
            $('.formulario').css("padding-bottom","0px");
        })
    });
});