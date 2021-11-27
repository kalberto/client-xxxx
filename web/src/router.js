import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

let routes = [
	{
		path: '/',
		name: 'home',
		component: () => import( /* webpackChunkName: "home" */ './views/Home/Home.vue')
	},
	{
		path: '/empresa',
		redirect: '/institucional'
	},
	{
		path: '/institucional',
		name: 'institucional',
		component: () => import( /* webpackChunkName: "institucional" */ './views/Institucional/Institucional.vue')
	},
	{
		path: '/responsabilidade-social',
		name: 'responsabilidade',
		component: () => import( /* webpackChunkName: "responsabilidade" */ './views/Responsabilidade/Responsabilidade.vue')
	},
	{
		path: '/politica-de-privacidade',
		name: 'politica-de-privacidade',
		component: () => import( /* webpackChunkName: "politica-de-privacidade" */ './views/Privacidade/Privacidade.vue')
	},
	{
		path: '/haroldojacobovicz',
		name: 'ceo',
		component: () => import( /* webpackChunkName: "ceo" */ './views/Ceo/Ceo.vue')
	},
	{
		path: '/contato',
		name: 'contato',
		component: () => import( /* webpackChunkName: "contato" */ './views/Contato/Contato.vue')
	},
	{
		path: '/documentoslegais/',
		name: 'documentos',
		component: () => import( /* webpackChunkName: "documentos" */ './views/Documentos/Documentos.vue')
	},
	// {
	// 	path: '/documentoslegais/empresarial',
	// 	name: 'documentosEmpresariais',
	// 	component: () => import( /* webpackChunkName: "documentosEmpresariais" */ './views/DocumentosEmpresariais/DocumentosEmpresariais.vue')
	// },
	{
		path: '/cliente/login',
		name: 'area-cliente-login',
		component: () => import( /* webpackChunkName: "area-cliente-auth" */ './views/AreaClienteAuth/AreaClienteAuth.vue')
	},
	{
		path: '/cliente/esqueci-minha-senha',
		name: 'area-cliente-redefinicao',
		component: () => import( /* webpackChunkName: "area-cliente-auth" */ './views/AreaClienteAuth/AreaClienteAuth.vue')
	},
	{
		path: '/seja-parceiro',
		name: 'seja-parceiro',
		component: () => import( /* webpackChunkName: "seja-parceiro" */ './views/SejaUmParceiro/SejaUmParceiro.vue')
	},
	{
		path: '/:categoria',
		name: 'produtos',
		component: () => import( /* webpackChunkName: "produtos" */ './views/Produtos/Produtos.vue')
	},
	{
		path: '/:categoria/:slug',
		name: 'produto',
		component: () => import( /* webpackChunkName: "produto" */ './views/Produto/Produto.vue')
	},
]

// routes.push({
// 	path: '/*',
// 	name: '404',
// 	component: () => import( /* webpackChunkName: "404" */ './views/404/404.vue')
// })

let router = new Router({
	mode: 'history',
	base: '',
	routes
})

export default router