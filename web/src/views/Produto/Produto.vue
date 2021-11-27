<template lang="pug">
    article#produto(v-if="content")
        h1 {{ content.title }}
        section#sobre
            .conteudo
                h2 {{ content.title }}
                h3 {{ content.sub_title }}
                p {{ content.text_description_1 }}
                p {{ content.text_description_2 }}
            BtnSolicite
        section#vantagens
            h2 Vantagens {{ content.title }}
            vuescroll(:ops="vueScrollConfig", @handle-scroll="handleScroll", @handle-resize="handleScroll", ref="vuescroll")
                div(v-html="content.benefits")
            .moreText(v-if="hasMoreText")
                include ../../assets/svgs/icon_mouse.svg
        section#diferencial
            p(v-html="content.differentials")
            button(@click="$eventbus.$emit('toggleVideos', true)")
                img(src="@images/icons/play.png", alt="")
                | {{ $t('botoes.assista-video') }}
            ul.produtos
                li(v-for="produto in content.servicos")
                    router-link(:to="{ name: 'produto', params: { slug: produto.url } }")
                        span {{ produto.title }}
                        picture
                            img(:src="produto.file", :alt="produto.title")
</template>

<script>
import BtnSolicite from '@components/BtnSolicite/BtnSolicite'
import vuescroll from 'vuescroll/dist/vuescroll-native';

export default {
    name: "view-produto",
    components: {
        BtnSolicite,
        vuescroll
    },
    data() {
        return {
            hasMoreText: false,
            loadPageRoute: `/servicos/${this.$route.params.slug}`,
            vueScrollConfig: {
                rail: {
                    size: '4px',
                },
                bar: {
                    background: '#ffffff',
                    onlyShowBarOnScroll: false,
                    size: '2px',
                }
            }
        }
    },
    created() {
        this.$eventbus.$emit('toggleColor')
    },
    mounted() {
        if (this.$refs.vuescroll)
            this.$refs.vuescroll.scrollTo({ y: 1 })
    },
    beforeRouteUpdate (to, from, next) {
        if (from.params.slug != to.params.slug) {
            this.loadPageRoute = `/servicos/${to.params.slug}`
            this.content = undefined
            this.loadPage()
        }
        next()
    },
    methods: {
        handleScroll(vertical) {
            if (vertical && vertical.barSize)
                this.hasMoreText = true
            else
                this.hasMoreText = false
        }
    },
}
</script>

<style lang="stylus" scoped src="./Produto.styl"></style>

<i18n src="@/i18n/geral.json"></i18n>