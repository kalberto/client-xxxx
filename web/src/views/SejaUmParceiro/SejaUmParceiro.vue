<template lang="pug">
    article#seja-parceiro
        .container
            .left
                div.titulo(v-if="content")
                    h2(v-html="content.title")
                div.texto(v-if="content", v-html="content.text_1")
            .center
                form(@submit.prevent="send")
                    .input.required
                        label(for="input_nome") {{ $t('labels.nome') }}
                        input(id="input_nome", name="input_nome", v-model="formulario.nome", type="text")
                        span.erro(v-if="errors['data.nome']") {{ errors['data.nome'][0] }}
                    .input.required
                        label(for="input_email") {{ $t('labels.email') }}
                        input(id="input_email", name="input_email", v-model="formulario.email", type="email")
                        span.erro(v-if="errors['data.email']") {{ errors['data.email'][0] }}
                    .input.telefone.required
                        label(for="input_telefone") {{ $t('labels.telefone') }}
                        input(id="input_telefone", name="input_telefone", v-model="formulario.telefone", type="tel", v-mask="['(##) # ####-####', '(##) ####-####']")
                        span.erro(v-if="errors['data.telefone']") {{ errors['data.telefone'][0] }}
                    .input.required
                        label(for="input_razao_social") {{ $t('labels.razao_social') }}
                        input(id="input_razao_social", name="input_razao_social", v-model="formulario.razao_social", type="text")
                        span.erro(v-if="errors['data.razao_social']") {{ errors['data.razao_social'][0] }}
                    .input.required
                        label(for="input_cnpj") {{ $t('labels.cnpj') }}
                        input(id="input_cnpj", name="input_cnpj", v-model="formulario.documento", type="tel", v-mask="['##.###.###/####-##']")
                        span.erro(v-if="errors.documento") {{ errors.documento[0] }}
                    .input.required
                        label(for="input_endereco") {{ $t('labels.endereco') }}
                        input(id="input_endereco", name="input_endereco", v-model="formulario.endereco", type="text")
                        span.erro(v-if="errors['data.endereco']") {{ errors['data.endereco'][0] }}
                    .input.required._50
                        label(for="input_cidade") {{ $t('labels.cidade') }}
                        input(id="input_cidade", name="input_cidade", v-model="formulario.cidade", type="text")
                        span.erro(v-if="errors['data.cidade']") {{ errors['data.cidade'][0] }}
                    .input.select.required._50
                        label(for="select_estado") {{ $t('labels.estado') }}
                        select(id="select_estado", name="select_estado", v-model="formulario.uf")
                            option(:value="null", disabled)
                            option(v-for="estado in estados", :value="estado.uf") {{ estado.nome }}
                        span.erro(v-if="errors['data.uf']") {{ errors['data.uf'][0] }}
                    .input.required
                        label(for="input_ramo_atuacao") {{ $t('labels.ramo_atuacao') }}
                        input(id="input_ramo_atuacao", name="input_ramo_atuacao", v-model="formulario.ramo_atuacao", type="text")
                        span.erro(v-if="errors['data.ramo_atuacao']") {{ errors['data.ramo_atuacao'][0] }}
                    .input.multiple
                        label(for="input_redes_sociais") {{ $t('labels.redes_sociais') }}
                        ul
                            li(v-for="rede, index in formulario.redes_sociais")
                                input(:id="`input_redes_sociais_${index}`", name="input_redes_sociais", type="text", v-model="formulario.redes_sociais[index]", @keydown.enter.prevent="() => false")
                        span.erro(v-if="errors['data.redes_sociais']") {{ errors['data.redes_sociais'][0] }}
                    .enviar
                        p {{ $t('labels.obrigatorio') }}
                        button(type="submit", v-html="btnText", :disabled="btn != 'enviar'")
            .right(v-if="content")
                figure.video(@click="$eventbus.$emit('toggleVideo', content.video)")
                    picture
                        img(src="@images/geral/bg-seja-parceiro.jpg", :alt="$t('titulos.seja-parceiro')")
                    figcaption
                        h3 {{ $t('titulos.seja-parceiro') }}
                        button
                            | {{ $t('botoes.seja-parceiro') }}
                            img(src="@images/icons/play.png", alt="")
                .links
                    div.link
                        div.img
                            include ../../assets/svgs/mail.svg
                        div.info
                            strong {{ $t('contatos.email') }}
                            p indicador@xxxx.com
</template>

<script>
import { mask } from 'vue-the-mask'
import qs from 'qs'

const FORMULARIO = {
    nome: ``,
    email: ``,
    telefone: ``,
    razao_social: ``,
    documento: ``,
    endereco: ``,
    cidade: ``,
    uf: null,
    redes_sociais: [``],
    ramo_atuacao: ``,
}

export default {
    name: "view-seja-um-parceiro",
    loadPageRoute: `/paginas/seja-um-parceiro`,
    directives: {
        mask
    },
    data() {
        return {
            formulario: Object.assign({}, FORMULARIO),
            errors: {},
            estados: [],
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
        },
    },
    created() {
        this.$eventbus.$emit('toggleColor', { isWhite: true })
        this.loadEstados()
    },
    watch: {
        'formulario.redes_sociais': {
            deep: true,
            handler: function () {
                let lastIndex = this.formulario.redes_sociais.length - 1
                let lastRede = this.formulario.redes_sociais[lastIndex].trim()
                if (lastRede)
                    this.formulario.redes_sociais.push('')
            }
        }
    },
    methods: {
        loadEstados() {
            this.$axios.get(`/contato/estados`)
                .then(response => this.estados = response.data)
        },
        send() {
            const localOptions = localStorage.getItem('htt-options')
            const language = Object.assign({}, { language: 'pt-br' }, localOptions ? JSON.parse(localOptions) : {})

            this.errors = {}
            this.btn = 'enviando'

            let form = Object.assign({}, this.formulario, Object.assign({}, { redes_sociais: this.formulario.redes_sociais }))
            let documento = this.formulario.documento.replace(/\D/gi, '')
            form.telefone = form.telefone.replace(/\D/gi, '')
            form.redes_sociais = form.redes_sociais.filter(rede => rede.trim() != "")
            delete form.documento

            this.$axios.post(`seja-parceiro?${qs.stringify(language)}`, { documento, data: form })
                .then(() => {
                    this.btn = 'enviado'
                    this.formulario = Object.assign({}, FORMULARIO, { redes_sociais: [''] })
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
        },
    },
}
</script>

<style lang="stylus" scoped src="./SejaUmParceiro.styl"></style>

<i18n src="@/i18n/geral.json"></i18n>
<i18n src="@/i18n/contato.json"></i18n>
