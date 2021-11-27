import FormularioFastpack from '../domain/formulario-fastpack/FormularioFastpack';
let form = new FormularioFastpack(base_url + '/contato/send-produto-mail/fastpack');

$('.scrollToContato').on('click', ($event) => {
    $event.preventDefault();
    $('html,body').animate({
        scrollTop: $('#sectionContato').offset().top
    }, 'slow');
})

$('#form_cep').mask('00.000-000');
$("#form_telefone")
    .mask("(99) 99999-9999")
    .focusout(function (event) {
        var target, phone, element;
        target = (event.currentTarget) ? event.currentTarget : event.srcElement;
        phone = target.value.replace(/\D/g, '');
        element = $(target);
        element.unmask();
        if (phone.length > 10) {
            element.mask("(99) 99999-9999");
        } else {
            element.mask("(99) 9999-9999");
        }
    });
$('#form_fastpack').on('submit', ($event) => {
    $event.preventDefault();
    let form_fastpack = $event.target;

    $(form_fastpack).find('.error').removeClass('error');
    $(form_fastpack).find('span.m-error').text('').removeClass('ativo');
    $(form_fastpack)
        .find('button[type="submit"]')
        .attr('disabled', true)
        .html('Enviando...');
    form
        .parseForm(form_fastpack)
        .send()
        .then((response) => {
            $(form_fastpack)
                .find('button[type="submit"]')
                .html('Enviado!');
            $(form_fastpack)
                .addClass('success');

            form_fastpack.reset();
        })
        .catch((error) => {
            let errors = error.response.data.error_validate;
            $(form_fastpack)
                .find('button[type="submit"]')
                .html('Solicite Agora <span>&rsaquo;</span>');
            for (let err in errors) {
                $('#form_' + err)
                    .addClass('error').next()
                    .text(errors[err]).addClass('ativo');
            }
        })
        .finally(() => {
            $(form_fastpack)
                .find('button[type="submit"]')
                .attr('disabled', false);

            setTimeout(() => {
                $(form_fastpack)
                    .find('button[type="submit"]')
                    .html('Solicite Agora <span>&rsaquo;</span>');
                $(form_fastpack)
                    .removeClass('success');
            }, 5000)
        });
});