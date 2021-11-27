<template lang="pug">
    article#produtos(v-if="content")
        section.banner
            figure
                picture
                    img(:src="require(`@images/categorias/thumb_${content.url}.jpg`)", :alt="content.title")
                figcaption
                    //- span 02
                    h3 {{ content.title }}
                    p.subtitulo {{ content.sub_title }}
            .descricao 
                .conteudo
                    p {{ content.text_description_1 }}
                    p {{ content.text_description_2 }}
            .anim 
                h2 {{ content.title }}
        // section.assista 
        //     button(@click="$eventbus.$emit('toggleVideos', true)")
        //         img(src="@images/icons/play.png", alt="")
        //         | {{ $t('botoes.assista-video') }}
        // section.produto(v-if="$route.params.categoria == 'data-center' && servico")._4
        //     a(:href="servico.link", target="_blank")
        //         figure
        //             picture
        //                 img(:src="require(`@images/categorias/thumb_${servico.url}.jpg`)", :alt="servico.title")
        //             figcaption {{ servico.title }}
        section.produto(v-for="produto, index in content.servicos", :class="`_${index + 1}`")
            a(:href="produto.link", target="_blank" v-if="produto.link && produto.link != ''")
                figure
                    picture
                        img(:src="produto.file", :alt="produto.title")
                    figcaption {{ produto.title }}
            router-link(:to="{ name: 'produto', params: { slug: produto.url } }" v-else)
                figure
                    picture
                        img(:src="produto.file", :alt="produto.title")
                    figcaption {{ produto.title }}
</template>

<script>
import qs from 'qs'
export default {
    name: "view-produtos",
    data() {
        return {
            loadPageRoute: `/produtos/${this.$route.params.categoria}`,
            servico: null,
        }
    },
    created() {
        this.$eventbus.$emit('toggleColor')
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
    },
}
</script>

<style lang="stylus" scoped src="./Produtos.styl"></style>

<i18n src="@/i18n/geral.json"></i18n>