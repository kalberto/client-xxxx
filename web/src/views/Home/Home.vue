<template lang="pug">
    article#home
        section.categoria(v-for="categoria, index in categorias", :class="{ hasHover: categoria.externo }")
            a(:href="categoria.link", target="_blank", v-if="categoria.externo")
                picture
                    img(:src="categoria.thumb", :alt="categoria.title")
                .descricao
                    span 0{{ index + 1 }}
                    h3 {{ categoria.title }}
                    p.subtitulo {{ categoria.sub_title }}
                    .texto(v-if="categoria.externo")
                        p {{ categoria.text_description_1 }}
                        span {{ categoria.text_description_2 }}
                    a(:href="categoria.link", target="_blank") {{ $t('botoes.saiba-mais') }}
            router-link(:to="{ name: 'produtos', params: { categoria: categoria.url } }", v-else)
                picture
                    img(:src="categoria.thumb", :alt="categoria.title")
                .descricao
                    span 0{{ index + 1 }}
                    h3 {{ categoria.title }}
                    p.subtitulo {{ categoria.sub_title }}
        section.assista
            button(@click="$eventbus.$emit('toggleVideos', true)")
                img(src="@images/icons/play.png", alt="")
                | {{ $t('botoes.assista-video') }}
        section.connecting
            //- simular loop infinito
            h2 Connecting Tomorrow
        section.marcaDagua
            picture
                img(src="@images/logos/xxxx_marcaDagua.png", alt="xxxx")

</template>

<script>
const THUMBS = [
    {
        url: `servidor-virtual`,
        thumb: require(`@images/categorias/thumb_servidor-virtual.jpg`)
    },
    {
        url: `dados`,
        thumb: require(`@images/categorias/thumb_dados.jpg`)
    },
    {
        url: `telefonia`,
        thumb: require(`@images/categorias/thumb_telefonia.jpg`)
    },
    {
        url: `data-center`,
        thumb: require(`@images/categorias/thumb_data-center.jpg`)
    },
]

export default {
    name: "view-home",
    loadPageRoute: `/produtos`, // custom option
    created() {
        this.$eventbus.$emit('toggleColor')
    },
    computed: {
        categorias() {
            if(this.content != undefined)
                return this.content.map(categoria => Object.assign({}, categoria, THUMBS.find(img => img.url == categoria.url)))
            return []
        }
    },
}
</script>

<style lang="stylus" scoped src="@views/Home/Home.styl"></style>

<i18n src="@/i18n/geral.json"></i18n>
