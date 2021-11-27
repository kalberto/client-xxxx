(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .service('API', ['$http', '$httpParamSerializerJQLike','EnvironmentConfig', 'Upload','$cookies','$browser','$location','$window', function($http, $httpParamSerializerJQLike, EnvironmentConfig, Upload, $cookies, $browser,$location,$window){

            var config;

            function getUrl(path){
                var token = $cookies.get('api_token');
	            if(token !== undefined && token !== null && token !== '')
	            	token = localStorage.getItem('api_token');
                if(token !== undefined && token !== null && token !== ''){
                    config = {
                      headers: {
                          'Authorization': 'Bearer '+token
                      }
                    };
                }
                return EnvironmentConfig.api + path;
            }

            /**
                AUTH
            **/
            this.authLogin = function(data) {
                return $http.post(getUrl('auth/login'), data);
            };

            this.authGetCurrentUser = function() {
                return $http.get(getUrl('auth/current-user'),config);
            };

            this.authLogout = function() {
                return $http.post(getUrl('auth/logout'));
            };

            this.authRecoverPassword = function(data) {
                return $http.post(getUrl('password/email'), data);
            };

            this.authSetNewPassword = function(data) {
                return $http.post(getUrl('password/email'), data);
            };

            /**
                ADMINISTRADOR
            **/
            this.administradoresListar = function(options) {
                var url = getUrl('administradores');
                config.params = options;
                return $http.get(url,config);
            };
            this.administradorDetalhe = function(id) {
                return $http.get(getUrl('administradores/'+id),config);
            };
            this.administradorSalvar = function(administrador) {
                if(administrador && administrador.id) return $http.put(getUrl('administradores/'+administrador.id), administrador, config);
                else return $http.post(getUrl('administradores'), administrador, config);
            };
            this.administradorChangePass = function(id,auth) {
                return $http.put(getUrl('administradores/'+id+'/editpass'), auth, config);
            };
            this.administradorBuscar = function (key) {
                var url = getUrl('administradores/search');
                config.params.search = key;
                return $http.get(url,config);
            };
            this.administradorDeletar = function(id) {
                return $http.delete(getUrl('administradores/'+id),config);
            };
            this.administradorMenu = function(id) {
                return $http.get(getUrl('administrador/'+id+'/menu'),config);
            };

            /**
                EMPRESAS
            **/
            this.empresasListar = function (options) {
                var url = getUrl('empresas');
                config.params = options;
                return $http.get(url,config);
            };
            this.empresasBuscar = function (options) {
                var url = getUrl('empresas/search');
                config.params = options;
                return $http.get(url,config);
            };
            this.detalhesEmpresa = function (documento) {
              var url = getUrl('empresas/'+documento);
              return $http.get(url,config);
            };
            this.adicionarEmpresa = function (slug,documento) {
                var url = getUrl('organizacoes/'+slug + '/add/'+documento);
                return $http.get(url,config);
            };
            this.removerEmpresa = function (slug,documento) {
                var url = getUrl('organizacoes/'+slug + '/remove/'+documento);
                return $http.get(url,config);
            };

            /**
             ORGANIZACAO
             **/
            this.organizacoesListar = function (options) {
                var url = getUrl('organizacoes');
                config.params = options;
                return $http.get(url,config);
            };
            this.detalhesOrganizacao = function (slug) {
                var url = getUrl('organizacoes/'+slug);
                return $http.get(url,config);
            };
            this.organizacaoSalvar = function(organizacao) {
                if(organizacao && organizacao.id)
                    return $http.put(getUrl('organizacoes/'+organizacao.id), organizacao, config);
                else
                    return $http.post(getUrl('organizacoes'), organizacao, config);
            };
            this.organizacaoDeletar = function(id) {
                return $http.delete(getUrl('organizacoes/'+id),config);
            };
            this.organizacaoDocumentos = function (slug) {
                return $http.get(getUrl('organizacoes/'+slug+'/documento'),config);
            };

            /**
                SERVIÇOS
            **/
            this.servicosListar = function (options) {
                var url = getUrl('servicos');
                config.params = options;
                return $http.get(url,config);
            };
            this.getServicosDocumento = function (documento) {
                var url = getUrl('servicos/'+documento);
                return $http.get(url,config);
            };

            /**
                USUÁRIO
             **/
            this.usuariosListar = function (options) {
              var url = getUrl('usuarios');
              config.params = options;
              return $http.get(url,config);
            };
            this.usuariosOrganizacao = function (options) {
                var url = getUrl('organizacao/'+options.slug+'/usuarios');
                return $http.get(url,config);
            };
            this.usuarioDetalhe = function(id) {
                return $http.get(getUrl('usuarios/'+id),config);
            };
            this.usuarioSalvar = function(usuario) {
                if(usuario && usuario.id) return $http.put(getUrl('usuarios/'+usuario.id), usuario, config);
                else return $http.post(getUrl('usuarios'), usuario, config);
            };
            this.usuarioChangePass = function(id,auth) {
                return $http.put(getUrl('usuarios/'+id+'/editpass'), auth, config);
            };
            this.usuarioDeletar = function(id) {
                return $http.delete(getUrl('usuarios/'+id),config);
            };

            this.usuarioLogin = function (api_token) {
                var token = $cookies.get('api_token');
	            if(token !== undefined && token !== null && token !== '')
		            token = localStorage.getItem('api_token');
                if(token !== undefined && token !== null && token !== ''){
                    config = {
                        headers: {
                            'Authorization': 'Bearer '+token
                        }
                    };
                }
                var base_url = new $window.URL($location.absUrl()).origin;
                var url = base_url + '/clientes/gerenciador/login/' + api_token;
                return $http.get(url,config);
            };

            /**
                MANUAIS
             **/
            this.manuaisServico = function (url_nome) {
                var url = getUrl('servicos/'+url_nome+'/manuais');
                return $http.get(url,config);
            };
            this.manualDetalhe = function(id) {
                var url = getUrl('manuais/'+id);
                config.params =[];
                config.params['media'] = true;
                return $http.get(url,config);
            };
            this.manualSalvar = function(usuario) {
                if(usuario && usuario.id) return $http.put(getUrl('manuais/'+usuario.id), usuario, config);
                else return $http.post(getUrl('manuais'), usuario, config);
            };
            this.manualDeletar = function(id) {
                return $http.delete(getUrl('manuais/'+id),config);
            };

            /**
             * Leads
             */
            this.leadsListar = function(options) {
                var url = getUrl('leads');
                config.params = options;
                return $http.get(url, config);
            };
            this.deletarLead = function (id) {
                return $http.delete(getUrl('leads/'+id),config);
            };
            this.urlDownloadLead = function () {
            	var token = $cookies.get('api_token');
	            if(token !== undefined && token !== null && token !== '')
		            token = localStorage.getItem('api_token');
                var url = getUrl('leads/export?api_token='+token);
                return url;
            };

            /**
            * Configurações
            */
            this.configuracoesDetalhe = function() {
                return $http.get(getUrl('configuracoes'),config);
            };
            this.configuracoesSalvar = function(configuracao) {
                if(configuracao && configuracao.id) return $http.put(getUrl('configuracoes'), configuracao,config);
                else return $http.put(getUrl('configuracoes'), configuracao,config);
            };
            this.modulosGerenciador = function() {
                return $http.get(getUrl('modulos-gerenciador'),config);
            };
            this.modulosCliente = function() {
                return $http.get(getUrl('modulos-cliente'),config);
            };

            /**
            * Dashboard
            **/
            this.dashboardDetalhe = function() {
                return $http.get(getUrl('dashboard'),config);
            };

            /**
             * FAQs
             */
            this.faqsServico = function(params,url_nome) {
                var url = getUrl('servicos/'+url_nome+'/faqs');
                config.params = params;
                return $http.get(url,config);
            };
            this.faqsBuscar = function(key) {
                var url = getUrl('faqs/search');
                config.params['search'] = key;
                return $http.get(url,config);
            };
            this.faqsDetalhe = function(id) {
                return $http.get(getUrl('faqs/'+id),config);
            };
            this.faqsSalvar = function(faq) {
                if(faq && faq.id) return $http.put(getUrl('faqs/'+faq.id), faq,config);
                else return $http.post(getUrl('faqs'), faq,config);
            };
            this.faqsDeletar = function(id) {
                return $http.delete(getUrl('faqs/'+id),config);
            };

            /**
             * Media
             */
            this.mediaDeletar = function(id) {
                return $http.delete(getUrl('medias/'+id),config);
            };
            this.mediaUpload = function(file){
                return Upload.upload({
                    url: getUrl('medias'),
                    data: {file: file, alias : file.alias},
                    headers: config.headers,
                    method: 'POST'});
            };
            this.mediaDetalhe = function(id) {
                return $http.get(getUrl('medias/'+id),config);
            };


	        /**
	         * LANGUAGE
	         */
	        this.getLanguages = function () {
		        return $http.get(getUrl('languages'),config);
	        };

	        /**
             * Produtos Site
	         */
	        this.produtosSiteListar = function (options) {
		        var url = getUrl('produtos-site');
		        config.params = options;
		        return $http.get(url,config);
	        };
	        this.produtosSiteDetalhe = function(id) {
		        return $http.get(getUrl('produtos-site/'+id),config);
	        };
	        this.produtosSiteSalvar = function(registro) {
		        if(registro && registro.id) return $http.post(getUrl('produtos-site/'+registro.id), registro,config);
		        else return $http.post(getUrl('produtos-site'), registro,config);
	        };

	        /**
	         * Servicos Site
	         */
	        this.getServicosSite = function (locale) {
		        return $http.get(getUrl('servicos-site/' + locale),config);
	        };
	        this.servicosSiteListar = function (options) {
		        var url = getUrl('servicos-site');
		        config.params = options;
		        return $http.get(url,config);
	        };
	        this.servicosSiteDetalhe = function(id) {
		        return $http.get(getUrl('servicos-site/'+id),config);
	        };
	        this.servicosSiteSalvar = function(registro) {
		        if(registro && registro.id) return $http.post(getUrl('servicos-site/'+registro.id), registro,config);
		        else return $http.post(getUrl('servicos-site'), registro,config);
	        };

	        /**
             * Video Site
	         */
	        this.getVideosSite = function (locale) {
		        return $http.get(getUrl('videos-site/' + locale),config);
	        };
	        this.videosSiteListar = function (options) {
		        var url = getUrl('videos-site');
		        config.params = options;
		        return $http.get(url,config);
	        };
	        this.videosSiteDetalhe = function(id) {
		        return $http.get(getUrl('videos-site/'+id),config);
	        };
	        this.videosSiteSalvar = function(registro) {
		        if(registro && registro.id) return $http.post(getUrl('videos-site/'+registro.id), registro,config);
		        else return $http.post(getUrl('videos-site'), registro,config);
	        };

	        /**
	         * Paginas Site
	         */
	        this.paginasSiteListar = function (options) {
		        var url = getUrl('paginas-site');
		        config.params = options;
		        return $http.get(url,config);
	        };
	        this.paginasSiteDetalhe = function(id) {
		        return $http.get(getUrl('paginas-site/'+id),config);
	        };
	        this.paginasSiteSalvar = function(registro) {
		        if(registro && registro.id) return $http.post(getUrl('paginas-site/'+registro.id), registro,config);
		        else return $http.post(getUrl('paginas-site'), registro,config);
	        };

	        /**
	         * Leads
	         */
	        this.sejaParceiroListar = function(options) {
		        var url = getUrl('seja-parceiro');
		        config.params = options;
		        return $http.get(url, config);
	        };
	        this.deletarSejaParceiro = function (id) {
		        return $http.delete(getUrl('seja-parceiro/'+id),config);
	        };
	        this.urlDownloadSejaParceiro = function () {
		        var token = $cookies.get('api_token');
		        if(token !== undefined && token !== null && token !== '')
			        token = localStorage.getItem('api_token');
		        var url = getUrl('seja-parceiro/export?api_token='+token);
		        return url;
	        };

        }]);
})();
