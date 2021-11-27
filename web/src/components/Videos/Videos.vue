<template lang="pug">
    aside#videos(@click.self="$eventbus.$emit('toggleVideos')")
        button(@click="$eventbus.$emit('toggleVideos')").fechar Fechar
        vuescroll(:ops="vueScrollConfig", ref="vuescroll")
            ul
                li(v-for="video, index in videos", :class="{ playing: playingIndex == index }").video
                    h2(@click="showVideo($event, index)") {{ video.title }}
                    p {{ video.text_description }}
                    .video-wrap(@click="showVideo($event, index)", @keyup.esc="showVideo($event, index)", tabindex="0")
                        video(:controls="playingIndex == index", :poster="video.thumb", preload="none", ref="video", @keyup.esc="showVideo($event, index)")
                            source(:src="video.video", type="video/mp4")
</template>

<script>
import vuescroll from 'vuescroll/dist/vuescroll-native';

export default {
    name: "component-videos",
    components: {
        vuescroll
    },
    data() {
        return {
            videos: [],
            playingIndex: null,
            vueScrollConfig: {
                scrollPanel: {
                    scrollingX: false
                },
                rail: {
                    size: '4px',
                },
                bar: {
                    background: '#ffffff',
                    onlyShowBarOnScroll: false,
                    keepShow: true,
                    size: '2px',
                }
            }
        }
    },
    created() {
        this.loadVideos()
    },
    methods: {
        loadVideos() {
            this.$axios.get(`videos`)
                .then(response => this.videos = response.data)
        },
        showVideo(event, index = null) {
            const playVideo = el => el.play()
            const pauseVideo = el => el.pause()
            if (this.playingIndex == null && event.type != "click")
                return
            if (event.target.classList.contains('video-wrap')) {
                if (this.playingIndex != null) {
                    this.playingIndex = null
                    pauseVideo(this.$refs.video[index])
                } else {
                    this.playingIndex = index
                    playVideo(this.$refs.video[index])
                }
            } else {
                this.playingIndex = index
                playVideo(this.$refs.video[index])
            }
        }
    },
}
</script>

<style lang="stylus" scoped src="./Videos.styl"></style>