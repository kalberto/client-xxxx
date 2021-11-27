import qs from 'qs'
import { alterTags } from './seo'

class LoadPage {
    install(Vue) {
        Vue.mixin({
            data() {
                return {
                    loadPageRoute: ``,
                    content: undefined
                }
            },
            mounted() {
                this.$eventbus.$on('change-language', () => {
                    if (this.loadPageRoute)
                        this.loadPage()
                })
                this.loadPageRoute = this.loadPageRoute || this.$options.loadPageRoute
                if (this.loadPageRoute) {
                    this.loadPage()
                }
            },
            methods: {
                loadPage(language) {
                    const localOptions = localStorage.getItem('htt-options')
                    language = language || Object.assign({}, { language: 'pt-br' }, localOptions ? JSON.parse(localOptions) : {}).language
                    this.$axios.get(`${this.loadPageRoute}?${qs.stringify({ language })}`)
                        .then(response => {
                            this.content = response.data
                            if (this.content.seo)
                                alterTags(this.content.seo.title, this.content.seo.description)
                            else
                                alterTags(`xxxx`, ``)

                            this.$eventbus.$emit('page-loaded')
                        })
                        .catch(error => {
                            if (error.response && error.response.status == 404)
                                this.$router.replace({ name: 'home' })
                        })
                }
            }
        })
    }
}

export default new LoadPage()
