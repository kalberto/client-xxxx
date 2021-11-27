import '@stylus/main.styl'

import Axios from 'axios'
import Vue from 'vue'
import VueI18n from 'vue-i18n'
import { App } from '@/app'
import router from '@/router'
import LoadPage from './lib/LoadPage'

Vue.config.productionTip = false

Vue.prototype.$eventbus = new Vue();
Vue.prototype.$axios = Axios.create({
	baseURL: `${process.env.VUE_APP_API_ROUTE}/api`
})

Vue.use(VueI18n)
Vue.use(LoadPage)

const i18n = new VueI18n({
	locale: 'pt-br',
	fallbackLocale: 'pt-br'
})


new Vue({
	router,
	i18n,
	created() {
		let localOptions = localStorage.getItem('htt-options')
		let options = Object.assign({}, { language: 'pt-br' }, localOptions ? JSON.parse(localOptions) : {})
		this.$root.$i18n.locale = options.language
	},
	data() {
		return {
			documentURL: `${process.env.VUE_APP_PUBLICPATH}/SPA/documentos`,
		}
	},
	render: h => h(App)
}).$mount('#app')
