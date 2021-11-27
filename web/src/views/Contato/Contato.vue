<template lang="pug">
    article#contato
        .container
            .left
                div.titulo
                    h2 {{ $t('titulos.h1') }}
                    p {{ $t('titulos.h2') }}
                div.links
                    div.link
                        div.img
                            include ../../assets/svgs/phone.svg
                        div.info
                            strong {{ $t('contatos.servicos') }}
                            p 0800 604 3939
                    div.link
                        div.img
                            include ../../assets/svgs/phone.svg
                        div.info
                            strong {{ $t('contatos.suporte') }}
                            p 0800 604 3939
                    div.link
                        div.img
                            include ../../assets/svgs/mail.svg
                        div.info
                            strong {{ $t('contatos.email') }}
                            p contato@xxxx.com
                    div.link
                        div.img
                            include ../../assets/svgs/pin.svg
                        div.info
                            strong {{ $t('contatos.endereco') }}
                            p Rua 13 de Maio, 1062 - Curitiba
                            p CEP: 80.510-030
                    div.link
                        div.img
                            include ../../assets/svgs/verified.svg
                        div.info
                            strong Lei 13.709/18 - Art. 41
                            p juridico@xxxx.com
                div.assista
                    // button(@click="$eventbus.$emit('toggleVideos', true)")
                    //     img(src="@images/icons/play-preto.png", alt="")
                    //     | {{ $t('botoes.assista-video') }}
                    h3 {{ $t('titulos.h1') }}
            .center
                form(@submit.prevent="send", v-if="content")
                    .input.select.required
                        label(for="select_assunto") {{ $t('labels.assunto') }}
                        select(id="select_assunto", name="select_assunto", v-model="formulario.assunto")
                            option(:value="null", disabled) {{ $t('labels.assunto') }}
                            option(v-for="assunto in content.assuntos", :value="assunto.name") {{ assunto.value }}
                        span.erro(v-if="errors.assunto") {{ errors.assunto[0] }}
                    .input.required
                        label(for="input_nome") {{ $t('labels.nome') }}
                        input(id="input_nome", name="input_nome", v-model="formulario.nome", type="text")
                        span.erro(v-if="errors.nome") {{ errors.nome[0] }}
                    .input.required
                        label(for="input_email") {{ $t('labels.email') }}
                        input(id="input_email", name="input_email", v-model="formulario.email", type="email")
                        span.erro(v-if="errors.email") {{ errors.email[0] }}
                    .input.telefone.required
                        label(for="input_telefone") {{ $t('labels.telefone') }}
                        input(id="input_telefone", name="input_telefone", v-model="formulario.telefone", type="tel", v-mask="['(##) #####-####', '(##) ####-####']")
                        span.erro(v-if="errors.telefone") {{ errors.telefone[0] }}
                    .input.mensagem
                        label(for="textarea_mensagem") {{ $t('labels.mensagem') }}
                        textarea(id="textarea_mensagem", name="textarea_mensagem", v-model="formulario.mensagem")
                    .enviar
                        p {{ $t('labels.obrigatorio') }}
                        button(type="submit", v-html="btnText", :disabled="btn != 'enviar'")
            .right(v-if="servico")
                picture.bg
                    img(src="@images/geral/bg_contato@2x.jpg")
                a(:href="servico.link", target="_blank")
                    figure.servico
                        picture
                            img(:src="require(`@images/categorias/thumb_${servico.url}.jpg`)", :alt="servico.title")
                        figcaption
                            span 01
                            | {{ servico.title }}
</template>

<script>
import { mask } from 'vue-the-mask'
import qs from 'qs'

const FORMULARIO = {
    assunto: null,
    nome: ``,
    email: ``,
    telefone: ``,
    mensagem: ``,
    slug: null
}

export default {
    name: "view-contato",
    loadPageRoute: `/contato/assuntos`,
    directives: {
        mask
    },
    data() {
        return {
            formulario: Object.assign({}, FORMULARIO, {
                slug: this.$route.query.produto || null,
                assunto: this.$route.query.assunto || null
            }),
            errors: {},
            servico: null,
            btn: 'enviar'
        }
    },
    computed: {
        btnText() {
            if (this.btn == 'enviando')
                return this.$t('botoes.enviando')
            if (this.btn == 'enviado')
                return this.$t('botoes.enviado')
            if (this.btn == 'erro')
                return this.$t('botoes.erro')
            return this.$t('botoes.enviar')
        }
    },
    created() {
        this.$eventbus.$emit('toggleColor', { isWhite: true })
        this.$eventbus.$on('change-language', this.loadServico)
        this.loadServico()
    },
    methods: {
        loadServico() {
            const localOptions = localStorage.getItem('htt-options')
            const language = Object.assign({}, { language: 'pt-br' }, localOptions ? JSON.parse(localOptions) : {})

            this.$axios.get(`/produtos/servidor-virtual?${qs.stringify(language)}`)
                .then(response => this.servico = response.data)
        },
        send() {
            const localOptions = localStorage.getItem('htt-options')
            const language = Object.assign({}, { language: 'pt-br' }, localOptions ? JSON.parse(localOptions) : {})

            this.errors = {}
            this.btn = 'enviando'

            this.$axios.post(`contato?${qs.stringify(language)}`, this.formulario)
                .then(() => {
                    this.btn = 'enviado'
                    this.formulario = Object.assign({}, FORMULARIO)
                })
                .catch(error => {
                    this.btn = 'erro'
                    if (error.response && error.response.status == 422)
                        this.errors = error.response.data.error_validate
                })
                .finally(() => {
                    setTimeout(() => {
                        this.btn = 'enviar'
                    }, 3000)
                })
        }
    },
}
</script>

<style lang="stylus" scoped src="./Contato.styl"></style>

<i18n src="@/i18n/geral.json"></i18n>
<i18n src="@/i18n/contato.json"></i18n>
