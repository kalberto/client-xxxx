<template lang="pug">
    aside#sidebar(:class="{ white: isWhite }")
        // div.language
        //     img(:src="require('@images/icons/br-flag.png')", alt="Alterar linguagem", v-if="$root.$i18n.locale == 'pt-br'")
        //     img(:src="require('@images/icons/uk-flag.png')", alt="Change language", v-else)
        //     ul.flags
        //         li(:class="{ ativo: $root.$i18n.locale == 'pt-br' }")
        //             button(@click="toggleLanguage('pt-br')", title="Alterar linguagem")
        //                 img(:src="require('@images/icons/br-flag.png')", alt="Alterar linguagem")
        //         li(:class="{ ativo: $root.$i18n.locale == 'en-gb' }")
        //             button(@click="toggleLanguage('en-gb')", title="Change language")
        //                 img(:src="require('@images/icons/uk-flag.png')", alt="Change language")
        ul.redes(v-if="$route.name != 'ceo' && $route.name != 'documentos'")
            li
                a(href="https://pt-br.facebook.com/xxxx/", target="_blank")
                    img(:src="require('@images/icons/facebook.png')")
            li
                router-link(:to="{name : 'documentos'}", v-html=`$t('botoes.documentos-legais')`)
</template>

<script>
export default {
    name: "component-sidebar",
    data() {
        return {
            isWhite: false
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

<style lang="stylus" scoped src="./SideBar.styl"></style>

<i18n src="@/i18n/geral.json"></i18n>
