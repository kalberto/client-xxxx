<template lang="pug">
    header#header(:class="{ white: isWhite }")
        router-link(:to="{ name: 'home' }")
            h1.logo xxxx Business
                include ../../assets/svgs/logo_xxxx.svg
        button(@click="openMenu()" :class="{'ativo': menuOpen}")
            i.first
            i.second
        div.nav(:class="{'ativo': menuOpen}")
            ul.menu
                li
                    router-link(:to="{ name: 'home' }") {{ $t('menu.home') }}
                li
                    router-link(:to="{ name: 'institucional' }") {{ $t('menu.institucional') }}
                li
                    router-link(:to="{ name: 'contato' }") {{ $t('menu.contato') }}
                li
                    a(href="https://blog.xxxx.com/", target="_blank") {{ $t('menu.blog') }}
            ul.menu.secundario
                li.cliente
                    router-link(to="/cliente", target="_blank") {{ $t('menu.area-cliente') }}
                li.cliente
                    router-link(:to="{ name: 'seja-parceiro' }") {{ $t('menu.seja-parceiro') }}
                li.cliente
                    a(href="https://xxxx.gupy.io/", target="_blank") {{ $t('menu.trabalhe-conosco') }}

            div.language
                img(:src="require('@images/icons/br-flag.png')", alt="Alterar linguagem", v-if="$root.$i18n.locale == 'pt-br'")
                img(:src="require('@images/icons/uk-flag.png')", alt="Change language", v-else)
                ul.flags
                    li(:class="{ ativo: $root.$i18n.locale == 'pt-br' }")
                        button(@click="toggleLanguage('pt-br')", title="Alterar linguagem")
                            img(:src="require('@images/icons/br-flag.png')", alt="Alterar linguagem")
                    li(:class="{ ativo: $root.$i18n.locale == 'en-gb' }")
                        button(@click="toggleLanguage('en-gb')", title="Change language")
                            img(:src="require('@images/icons/uk-flag.png')", alt="Change language")
            ul.redes.mobile
                li
                    a(href="#")
                        img(:src="require('@images/icons/facebook.png')")
                li
                    a(href="#")
                        img(:src="require('@images/icons/twitter.png')")

</template>

<script>
export default {
    name: "component-header",
    data() {
        return {
            isWhite: false,
            menuOpen: false,
        }
    },
    created() {
        this.$eventbus.$on('toggleColor', this.toggleColor)
    },
    updated() {
        this.$eventbus.$on('toggleColor', this.toggleColor)
    },
    methods: {
        toggleColor(event = { isWhite: false }) {
            this.isWhite = event.isWhite
        },
        openMenu(){
            this.menuOpen = !this.menuOpen
        },
        toggleLanguage(language = 'pt-br') {
            let localOptions = localStorage.getItem('htt-options')
            let options = Object.assign({}, localOptions ? JSON.parse(localOptions) : {}, { language })
            localStorage.setItem('htt-options', JSON.stringify(options))

            this.$root.$i18n.locale = language
            this.$eventbus.$emit('change-language', this.$root.$i18n.locale)
        }
    },
}
</script>

<style lang="stylus" scoped src="./Header.styl"></style>

<i18n src="@/i18n/header.json"></i18n>
