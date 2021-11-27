<template lang="pug">
    aside#video
        button(@click="close").fechar Fechar
        .video-wrap(@click.self="close")
            video(controls, :poster="video.thumb", preload="meta", ref="video")
                source(:src="video.video", type="video/mp4")
</template>

<script>
export default {
    name: "component-video",
    props: {
        video: {
            type: Object,
            required: true
        }
    },
    mounted() {
        this.$refs.video.play()
    },
    methods: {
        close() {
            this.$refs.video.pause()
            this.$eventbus.$emit('toggleVideo')
        }
    },
}
</script>

<style lang="stylus" scoped src="./Video.styl"></style>