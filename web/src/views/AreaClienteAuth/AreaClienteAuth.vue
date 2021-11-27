<template lang="pug">
    article#areaClienteAuth
        .forms
            form(@submit.prevent="sendLogin", v-if="isLoginActive", ref="form").form.form-login
                .texto
                    h2 Bem-vindo, faça o login
                .input
                    input(name="input_login", id="input_login", type="text", v-model="formulario.login")
                    label(for="input_login", :class="{ ativo: formulario.login }") Usuário
                    span.erro(:title="errors.login[0]", v-if="errors.login") {{ errors.login[0] }}
                .input
                    input(name="input_password", id="input_password", type="password", v-model="formulario.password")
                    label(for="input_password", :class="{ ativo: formulario.password }") Senha
                    span.erro(:title="errors.password[0]", v-if="errors.password") {{ errors.password[0] }}
                .texto
                    p 
                        | Esqueceu sua senha? 
                        router-link(:to="{ name: 'area-cliente-redefinicao' }") Clique aqui
                .button
                    button(type="submit", :disabled="disabled", v-html="btnText")
            
            form(@submit.prevent="sendReset", v-else, ref="form").form.form-redefinicao
                .texto
                    h2 Recuperação de Senha
                    p Preencha o campo abaixo para que possamos enviar o link para a recuperação da sua senha
                .input
                    input(name="input_login", id="input_login", type="text", v-model="formulario.login")
                    label(for="input_login", :class="{ ativo: formulario.login }") Usuário
                    span.erro(:title="errors.login[0]", v-if="errors.login") {{ errors.login[0] }}
                .texto
                    p 
                        | Possui uma senha? 
                        router-link(:to="{ name: 'area-cliente-login' }") Clique aqui
                .button
                    button(type="submit", :disabled="disabled", v-html="btnText")
        .bg
            picture
                img(src="@images/geral/bg_areacliente.png")
</template>

<script>
import Axios from 'axios'

const FORMULARIO_LOGIN = {
    login: '',
    password: ''
}

const FORMULARIO_REDEFINIR = {
    login: '',
}

export default {
    name: "view-area-cliente-login",
    data() {
        let formulario = this.$route.name == 'area-cliente-login' ? Object.assign({}, FORMULARIO_LOGIN) : Object.assign({}, FORMULARIO_REDEFINIR)
        return {
            isLoginActive: this.$route.name == 'area-cliente-login',
            disabled: false,
            btnText: 'Enviar',
            formulario,
            errors: {}
        }
    },
    created() {
        this.$eventbus.$emit('toggleColor')
    },
    mounted() {
        setTimeout(() => {
            this.$refs.form.focus()
        }, 1)
    },
    beforeRouteEnter(to, from, next) {
        Axios.get(`${process.env.VUE_APP_API_ROUTE}/api/clientes/auth`)
            .then(response => {
                if (response.data != false) {
                    next(false)
                    return window.location.pathname = "/cliente"
                }
                return next(vm => {
                    if (from.name == 'area-cliente-login' || from.name == 'area-cliente-redefinicao') {
                        vm.formulario = to.name == 'area-cliente-login' ? Object.assign({}, FORMULARIO_LOGIN) : Object.assign({}, FORMULARIO_REDEFINIR)
                        vm.isLoginActive = to.name == 'area-cliente-login'
                        vm.disabled = false
                        vm.btnText = 'Enviar'
                        vm.errors = {}
                    }
                })
            })
    },
    methods: {
        send(route) {
            this.disabled = true
            this.btnText = 'Enviando...'
            this.errors = {}

            return this.$axios.post(route, this.formulario)
                .then(response => {
                    this.formulario = this.$route.name == 'area-cliente-login' ? Object.assign({}, FORMULARIO_LOGIN) : Object.assign({}, FORMULARIO_REDEFINIR)
                    this.disabled = false
                    this.btnText = 'Enviado!'
                    return Promise.resolve(response)
                })
                .catch(error => {
                    if (error.response && error.response.status == 422) {
                        this.errors = error.response.data.errors ? error.response.data.errors : {}
                    }
                    this.btnText = 'Ocorreu um erro!'
                    setTimeout(() => {
                        this.btnText = 'Enviar'
                        this.disabled = false
                    }, 3500)
                    return Promise.reject(error)
                })
        },
        sendLogin() {
            this.send(`/clientes/auth/login`)
                .then(() => {
                    window.location.pathname = "/cliente"
                })
                .catch(error => {
                    if (error.response && !this.errors.login && (error.response.status == 422 || error.response.status == 401)) {
                        this.errors = { login: [error.response.data.msg] }
                    }
                })
        },
        sendReset() {
            this.send(`/clientes/auth/password/email`)
            .then(response => {
                    this.btnText = response.data.msg
                    setTimeout(() => {
                        this.$router.push({ name: 'area-cliente-login' })
                    }, 3500)
                })
                .catch(error => {
                    if (error.response && !this.errors.login && (error.response.status == 422 || error.response.status == 401)) {
                        this.errors = { login: [error.response.data.msg] }
                    }
                })
        },
    },
}
</script>

<style lang="stylus" scoped src="./AreaClienteAuth.styl"></style>

<i18n src="@/i18n/area-cliente/login.json"></i18n>