<template lang="pug">
    article#privacidade
        div(v-html="content.text_1")
</template>

<script>
export default {
    name: "view-privacidade",
    loadPageRoute: `/paginas/politica-de-privacidade`,
}
</script>

<style lang="stylus" scoped src="./Privacidade.styl"></style>