<template lang="pug">
    .BtnSolicite
        router-link(:to="{ name: routeName, query: query }").btn {{ textContent }}
</template>

<script>
export default {
    name: "component-btn-solicite",
    props: {
        routeName: {
            required: false,
            default: `contato`
        },
        text: {
            required: false,
            default: ``
        }
    },
    computed: {
        textContent() {
            return this.$props.text || this.$t('botoes.solicite')
        },
        query() {
            return {
                produto: this.$route.params.slug || ``,
                assunto: `Produtos/Servicos`
            }
        }
    },
}
</script>

<style lang="stylus" scoped src="./BtnSolicite.styl"></style>

<i18n src="@/i18n/geral.json"></i18n>