<template lang="pug">
    main#app(:class="{ white: isWhite, loaded: isLoaded }")
        Header
        .container
            Sidebar
            vuescroll(:ops="vueScrollConfig", ref="vuescroll")
                router-view
        transition(name="fade")
            Videos(v-if="videosActive")
            Video(v-if="video.active", :video="video.content")
        .lgpd(v-if="popup")
            p Este site utiliza cookies para uma experiência personalizada de navegação. 
                router-link(:to="{name: 'politica-de-privacidade'}") Saiba mais.
            button(@click="togglePopup()") Prosseguir
</template>

<script>
import Header from '@components/Header/Header'
import Sidebar from '@components/SideBar/SideBar'
import Videos from '@components/Videos/Videos'
import Video from '@components/Video/Video'
import vuescroll from 'vuescroll/dist/vuescroll-native';

export default {
    name: "app",
    components: {
        Header,
        Sidebar, 
        Videos,
        Video,
        vuescroll
    },
    data() {
        return {
            isWhite: false,
            isLoaded: false,
            videosActive: false,
            video: {
                active: false,
                content: {}
            },
            popup: true,
            vueScrollConfig: {
                rail: {
                    size: '6px',
                },
                bar: {
                    background: '#ffffff',
                    onlyShowBarOnScroll: false,
                    size: '4px',
                }
            }
        }
    },
    created() {
        this.$eventbus.$on('toggleColor', this.toggleColor)
        this.$eventbus.$on('toggleVideos', this.toggleVideos)
        this.$eventbus.$on('toggleVideo', this.toggleVideo)
        setTimeout(() => { this.isLoaded = true }, 600)
    },
    updated() {
        this.$eventbus.$on('toggleColor', this.toggleColor)
    },
    methods: {
        togglePopup(){
            this.popup = false
        },
        toggleColor(event = { isWhite: false }) {
            this.isWhite = event.isWhite
            if (this.isWhite)
                this.vueScrollConfig.bar.background = "#1b1a1b"
            else
                this.vueScrollConfig.bar.background = "#ffffff"
        },
        toggleVideos(event = null) {
            if (event)
                this.videosActive = event
            else
                this.videosActive = !this.videosActive
        },
        toggleVideo(event = null) {
            if (event) {
                this.video.active = true
                this.video.content = event
            } else {
                this.video.active = false
                this.video.content = {}
            }
        },
    },
}
</script>

<style lang="stylus" scoped src="@templates/App/App.styl"></style>