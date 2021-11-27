<template lang="pug">
    article#documentos
        h2 {{ titulo }}
        p {{ $t('titulos.h2') }}
        ul
            li(v-for="documento, index in documentos")
                a(:href="documento.src", target="_blank", v-if="!documento.documentos") - {{ documento.nome }}
                button(v-else, @click="ativo = documento.nome") - {{ documento.nome }}
            li(v-if="ativo != null")
                button(@click="ativo = null") {{ $t('botoes.voltar') }}
</template>

<script>
export default {
    name: "view-documentos",
    loadPageRoute: `/seo/documentoslegais`,
    data() {
        return {
            rawDocumentos: [],
            ativo: null
        }
    },
    created() {
        this.$eventbus.$emit('toggleColor')
        this.loadDocumentos()
    },
    computed: {
        titulo() {
            if (this.ativo)
                return `${ this.$t('titulos.h1') } - ${ this.ativo }`
            return this.$t('titulos.h1')
        },
        documentosByLang() {
            if (this.rawDocumentos && this.rawDocumentos[this.$root.$i18n.locale])
                return this.rawDocumentos[this.$root.$i18n.locale]
            return []
        },
        documentos() {
            let documentos = []
            documentos = this.parseDocumentos( this.documentosByLang )
            if (this.ativo)
                return documentos.find(documento => documento.nome.includes(this.ativo)).documentos
            return documentos
        }
    },
    methods: {
        loadDocumentos() {
            this.$axios
                .get(`documentoslegais/documentos_legais.json`, { baseURL: `${process.env.VUE_APP_API_ROUTE}` })
                .then(response => {
                    if (response.headers['content-type'].includes("application/json"))
                        return this.rawDocumentos = response.data
                    return Promise.reject(response)
                })
        },
        parseDocumento(documento = []) {
            let ret = {}
            if (documento.length) {
                if (documento[1] instanceof Array) {
                    ret = Object({
                        nome: documento[0],
                        src: documento[0].toLowerCase().replace(/\s/gi, '-')
                    })
                } else {
                    ret = Object({
                        nome: documento[0],
                        src: documento[1][0] == '/' ? documento[1] : '/' + documento[1]
                    })
                }
            }
            return ret
        },
        parseDocumentos(documentos = []) {
            let ret = []
            documentos.forEach(documento => {
                if (documento[1] instanceof Array) {
                    let tempDoc = this.parseDocumento(documento)
                    tempDoc.documentos = this.parseDocumentos(documento[1])
                    ret.push(tempDoc)
                } else {
                    ret.push(this.parseDocumento(documento))
                }
            })
            return ret
        }
    },
}
</script>

<style lang="stylus" scoped src="./Documentos.styl"></style>

<i18n src="@/i18n/documentos-legais.json"></i18n>