<template lang="pug">
    article#responsabilidade
        div.container(v-if="content")
            div.left
                div.img
                    img(:src="require('@images/logos/selo-pequeno.png')")
                div.assista
                    // button(@click="$eventbus.$emit('toggleVideos', true)")
                    //     img(src="@images/icons/play-preto.png", alt="")
                    // //     | {{ $t('botoes.assista-video') }}
                    // h3 {{ $t('textos.institucional') }}
            div.center
                vuescroll(:ops="vueScrollConfig", @handle-scroll="handleScroll", @handle-resize="handleScroll", ref="vuescroll")
                    div
                        h2
                            span {{ content.title }},
                            br
                            |{{ content.sub_title }}
                        p(v-html="content.text_1")
            .right(v-if="servico")
                a(:href="servico.link", target="_blank")
                    figure.servico
                        picture
                            img(:src="require(`@images/categorias/thumb_${servico.url}.jpg`)", :alt="servico.title")
                        figcaption 
                            span 01
                            | {{ servico.title }}
</template>

<script>
import qs from 'qs'
import vuescroll from 'vuescroll/dist/vuescroll-native';

export default {
    name: "view-responsabilidade",
    loadPageRoute: `/paginas/responsabilidade-social`,
    components: {
        vuescroll
    },
    data() {
        return {
            servico: {},
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
        this.$eventbus.$emit('toggleColor', { isWhite: true })
        this.$eventbus.$on('change-language', this.loadServico)
        this.loadServico()
    },
    mounted() {
        if (this.$refs.vuescroll)
            this.$refs.vuescroll.scrollTo({ y: 1 })
    },
    methods: {
        loadServico() {
            const localOptions = localStorage.getItem('htt-options')
            const language = Object.assign({}, { language: 'pt-br' }, localOptions ? JSON.parse(localOptions) : {})

            this.$axios.get(`/produtos/servidor-virtual?${qs.stringify(language)}`)
                .then(response => this.servico = response.data)
        },
        handleScroll(vertical) {
            if (vertical && vertical.barSize)
                this.hasMoreText = true
            else
                this.hasMoreText = false
        }
    },
}
</script>

<style lang="stylus" scoped src="./Responsabilidade.styl"></style>

<i18n src="@/i18n/geral.json"></i18n>