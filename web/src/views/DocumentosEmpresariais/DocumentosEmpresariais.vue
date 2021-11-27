<template lang="pug">
    article#documentosEmpresariais
        h2 Documentos Legais - Empresarial
        p Clique no documento desejado e faça download.
        ul
            li
                a(:href="`${$root.documentURL}/empresariais/Contrato_Prestação_de_Serviço_FAST_PACK.pdf`" target="_blank") - CONTRATO PRESTAÇÃO DE SERVIÇO
            li
                a(:href="`${$root.documentURL}/empresariais/Contrato_de_Permanência_FAST_PACK.pdf`" target="_blank") - CONTRATO DE PERMANÊNCIA
            li
                a(:href="`${$root.documentURL}/empresariais/Informe_Precos_Voz_FAST.pdf`" target="_blank") - PLANO DE VOZ
            li
                a(:href="`${$root.documentURL}/empresariais/Plano_50Mbps_FAST.pdf`" target="_blank") - PLANO 50MBPS
            li
                a(:href="`${$root.documentURL}/empresariais/Plano_100Mbps_FAST.pdf`" target="_blank") - PLANO 100MBPS FAST
            li
                a(:href="`${$root.documentURL}/empresariais/Plano_150Mbps_FAST.pdf`" target="_blank") - PLANO 150MBPS FAST
            li
                a(:href="`${$root.documentURL}/empresariais/Informe_Precos_Voz_FAST.pdf`" target="_blank") - INFORME DE PREÇOS FASTPACK VOZ
            li
                a(:href="`${$root.documentURL}/empresariais/Informe_Precos_Internet_FAST_PACK.pdf`" target="_blank") - INFORME DE PREÇOS FASTPACK INTERNET
            li
                a(:href="`${$root.documentURL}/empresariais/Termo_Promocional_FAST_PACK_50.pdf`" target="_blank") - TERMO PROMOCIONAL FAST PACK 50
            li
                a(:href="`${$root.documentURL}/empresariais/Termo_Promocional_FAST_PACK_100.pdf`" target="_blank") - TERMO PROMOCIONAL FAST PACK 100
            li
                a(:href="`${$root.documentURL}/empresariais/Termo_Promocional_FAST_PACK_150.pdf`" target="_blank") - TERMO PROMOCIONAL FAST PACK 150
            li
                a(:href="`${$root.documentURL}/empresariais/Termo_Promocional_FAST_PACK_ILIMITADO_VOZ.pdf`" target="_blank") - TERMO PROMOCIONAL FAST PACK ILIMITADO VOZ
</template>

<script>
export default {
    name: "view-documentos-empresariais",
    created() {
        this.$eventbus.$emit('toggleColor')
    },
}
</script>

<style lang="stylus" scoped src="./DocumentosEmpresariais.styl"></style>