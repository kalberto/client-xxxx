<template lang="pug">
    article#ceo
        div.colunas
            div.coluna._01
                div.img
                    img(:src="require('@images/geral/haroldo.png')")
                    p Haroldo Jacobovicz
            div.coluna._02(v-if="content")
                vuescroll(:ops="vueScrollConfig", ref="vuescroll")
                    div
                        h2
                            span {{ content.title }} 
                            | {{ content.sub_title }}
                        p(v-html="content.text_1")
</template>

<script>
import vuescroll from 'vuescroll/dist/vuescroll-native';

export default {
    name: "view-ceo",
    loadPageRoute: `/paginas/haroldojacobovicz`,
    components: {
        vuescroll
    },
    data() {
        return {
            vueScrollConfig: {
                rail: {
                    size: '4px',
                },
                bar: {
                    background: '#d24830',
                    onlyShowBarOnScroll: false,
                    size: '2px',
                }
            }
        }
    },
}
</script>

<style lang="stylus" scoped src="./Ceo.styl"></style>