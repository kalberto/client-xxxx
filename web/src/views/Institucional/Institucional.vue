<template lang="pug">
    article#institucional(v-if="content")
        div.container
            div.left
                div.img
                    img(:src="require('@images/geral/institucional.jpg')")
                div.assista
                    // button(@click="$eventbus.$emit('toggleVideos', true)")
                    //     img(src="@images/icons/play-preto.png", alt="")
                    //     | {{ $t('botoes.assista-video') }}
                    h3 {{ $t('textos.institucional') }}
            div.center
                vuescroll(:ops="vueScrollConfig", @handle-scroll="handleScroll", @handle-resize="handleScroll", ref="vuescroll")
                    div
                        h2
                            span {{ content.title }}
                            | {{ content.sub_title }}
                        p(v-html="content.text_1")
                        div.logo
                            div.img
                                img(:src="require('@images/logos/xxxx_marcaDagua_branca.png')")
                            p(v-html="content.text_2")
            .right
                // figure.video(@click="$eventbus.$emit('toggleVideo', content.video)")
                //     picture
                //         img(:src="content.video.video_thumb", :alt="content.video.title")
                //     figcaption {{ content.video.title }}
                router-link(:to="{ name: 'responsabilidade' }").responsabilidade
                    span RESPONSABILIDADE SOCIAL
                    br
                    |RESPEITO ÀS COMUNIDADES, VALORIZAÇÃO DA DIVERSIDADE E ATITUDE SOCIALMENTE RESPONSÁVEL FAZEM PARTE DA FORMA DE SER DA xxxx
                a(:href="servico.link", target="_blank").servicos
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
    name: "view-institucional",
    loadPageRoute: `/paginas/institucional`,
    components: {
        vuescroll
    },
    data() {
        return {
            servico: {},
            hasMoreText: false,
            vueScrollConfig: {
                rail: {
                    size: '4px',
                },
                bar: {
                    background: '#1c1c1c',
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

<style lang="stylus" scoped src="./Institucional.styl"></style>

<i18n src="@/i18n/geral.json"></i18n>
