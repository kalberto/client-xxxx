(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .config(routes);

    function routes($stateProvider, $urlRouterProvider, $locationProvider){

        $locationProvider.html5Mode(true);

        $urlRouterProvider.otherwise("/dashboard");

        $stateProvider
            .state('auth-login', {
                url: '/auth/login',
                templateUrl: 'sections/autenticacao/login.html',
                controller: 'AuthController as vm',
                resolve: {
                    $title: function() { return 'Login'; }
                }
            })
            .state('auth-recuperar-senha', {
                url: '/auth/recuperar-senha',
                templateUrl: 'sections/autenticacao/recuperar-senha.html',
                controller: 'AuthController as vm',
                resolve: {
                    $title: function() { return 'Recuperar senha'; }
                }
            })
            .state('painel', {
                templateUrl: 'sections/estrutura-painel.html'
            })
            .state('painel.editar-perfil', {
                url: '/administrador/editar-perfil',
                templateUrl: 'sections/administradores/editar-perfil.html',
                controller: 'PerfilController as vm',
                resolve: {
                    $title: function() { return 'Editar Perfil'; }
                }
            })
            .state('painel.dashboard', {
                url: '/dashboard',
                templateUrl: 'sections/dashboard/index.html',
                controller: 'DashboardController as vm',
                resolve: {
                    $title: function() { return 'Dashboard'; }
                }
            })
           .state('painel.configuracoes', {
                url: '/configuracoes',
                templateUrl: 'sections/configuracoes/listagem.html',
                controller: 'listagemConfiguracoesController as vm',
                resolve: {
                    $title: function() { return 'Configurações'; }
                }
            });

        //FAQ
        $stateProvider
            .state('painel.faqs', {
                abstract: true,
                url: '/faqs',
                template: '<ui-view/>',
                defaultChild: 'painel.faqs.listar'
            })
            .state('painel.faqs.listar', {
                url: '/listar/:url?',
                params:{
                    nome:null
                },
                templateUrl: 'sections/faqs/listagem.html',
                controller: 'listagemFaqsController as vm',
                resolve: {
                    $title: function() { return 'Faqs'; }
                }
            })
            .state('painel.faqs.salvar', {
                url: '/salvar/:url/:id?',
                params:{
                    nome:null
                },
                templateUrl: 'sections/faqs/salvar.html',
                controller: 'salvarFaqsController as vm',
                resolve: {
                    $title: function() { return 'Salvar Faqs'; }
                }
            });

        //MANUAL
        $stateProvider
            .state('painel.manuais', {
                abstract: true,
                url: '/manuais',
                template: '<ui-view/>',
                defaultChild: 'painel.manuais.listar'
            })
            .state('painel.manuais.listar', {
                url: '/listar/:url?',
                params:{
                    nome:null
                },
                templateUrl: 'sections/manuais/listagem.html',
                controller: 'ListagemManuaisController as vm',
                resolve: {
                    $title: function() { return 'Manuais'; }
                }
            })
            .state('painel.manuais.salvar', {
                url: '/salvar/:url/:id?',
                params:{
                    nome:null
                },
                templateUrl: 'sections/manuais/editar-manual.html',
                controller: 'EditarManualController as vm',
                resolve: {
                    $title: function() { return 'Salvar Manual'; }
                }
            });

        $stateProvider
            .state('painel.servicos', {
                abstract: true,
                url: '/servicos',
                template: '<ui-view/>',
                defaultChild: 'painel.servicos.listar'
            })
            .state('painel.servicos.listar', {
                url: '/servicos',
                templateUrl: 'sections/servicos/listagem.html',
                controller: 'listagemServicosController as vm',
                resolve: {
                    $title: function() { return 'Serviços'; }
                }
            });

        $stateProvider
            .state('painel.clientes', {
                abstract: true,
                url: '/clientes',
                template: '<ui-view/>',
                defaultChild: 'painel.clientes.listar'
            })
            .state('painel.clientes.listar', {
                url: '/listar',
                templateUrl: 'sections/clientes/listagem.html',
                controller: 'listagemClientesController as vm',
                resolve: {
                    $title: function() { return 'Clientes'; }
                }
            })
            .state('painel.clientes.detalhe', {
                url: '/clientes/:documento?',
                templateUrl: 'sections/clientes/detalhe.html',
                controller: 'detalheClientesController as vm',
                resolve: {
                    $title: function() { return 'Cliente'; }
                }
            });

        $stateProvider
            .state('painel.organizacoes', {
                abstract: true,
                url: '/organizacoes',
                template: '<ui-view/>',
                defaultChild: 'painel.organizacoes.listar'
            })
            .state('painel.organizacoes.listar', {
                url: '/listar',
                templateUrl: 'sections/organizacoes/listagem.html',
                controller: 'listagemOrganizacoesController as vm',
                resolve: {
                    $title: function() { return 'Organizações'; }
                }
            })
            .state('painel.organizacoes.criar', {
                url: '/criar',
                templateUrl: 'sections/organizacoes/criar-organizacao.html',
                controller: 'CriarOrganizacaoController as vm',
                resolve: {
                    $title: function() { return 'Organizações'; }
                }
            })
            .state('painel.organizacoes.salvar', {
                url: '/:slug?',
                templateUrl: 'sections/organizacoes/editar-organizacao.html',
                controller: 'EditarOrganizacaoController as vm',
                resolve: {
                    $title: function() { return 'Organizações'; }
                }
            });

        //Users
        $stateProvider
            .state('painel.users', {
                abstract: true,
                url: '/usuarios',
                template: '<ui-view/>',
                defaultChild: 'painel.leads.listar'
            })
            .state('painel.users.listar', {
                url: '/listar',
                templateUrl: 'sections/users/listagem.html',
                controller: 'listagemUsersController as vm',
                resolve: {
                    $title: function() { return 'Usuários'; }
                }
            });

        //Leads
        $stateProvider
            .state('painel.leads', {
                abstract: true,
                url: '/leads',
                template: '<ui-view/>',
                defaultChild: 'painel.leads.listar'
            })
            .state('painel.leads.listar', {
                url: '/listar',
                templateUrl: 'sections/leads/listagem.html',
                controller: 'listagemLeadsController as vm',
                resolve: {
                    $title: function() { return 'Leads'; }
                }
            });

        //Usuários
        $stateProvider
            .state('painel.usuarios', {
                abstract: true,
                url: '/usuarios',
                template: '<ui-view/>',
                defaultChild: 'painel.usuarios.listar'
            })
            .state('painel.usuarios.listar', {
                url: '/listar/:slug',
                templateUrl: 'sections/usuarios/listagem.html',
                controller: 'ListagemUsuariosController as vm',
                resolve: {
                    $title: function() { return 'Usuários'; }
                }
            })
            .state('painel.usuarios.salvar', {
                url: '/salvar/:slug/:id?',
                templateUrl: 'sections/usuarios/editar-usuario.html',
                controller: 'EditarUsuarioController as vm',
                resolve: {
                    $title: function() { return 'Salvar Usuário'; }
                }
            });

        //Administradores
        $stateProvider
            .state('painel.administradores', {
                abstract: true,
                url: '/administradores',
                template: '<ui-view/>',
                defaultChild: 'painel.administradores.listar'
            })
            .state('painel.administradores.listar', {
                url: '/listar',
                templateUrl: 'sections/administradores/listagem.html',
                controller: 'ListagemAdministradoresController as vm',
                resolve: {
                    $title: function() { return 'Administradores'; }
                }
            })
            .state('painel.administradores.salvar', {
                url: '/salvar/:id?',
                templateUrl: 'sections/administradores/editar-administrador.html',
                controller: 'EditarAdministradoresController as vm',
                resolve: {
                    $title: function() { return 'Salvar Administrador'; }
                }
            });

        //PRODUTOS SITE
        $stateProvider
            .state('painel.produtos-site', {
	        abstract: true,
	        url: '/produtos-site',
	        template: '<ui-view/>',
	        defaultChild: 'painel.produtos-site.listar'
            })
            .state('painel.produtos-site.listar', {
	        url: '/listar',
	        templateUrl: 'sections/produtos-site/listagem.html',
	        controller: 'ListagemProdutosSiteController as vm',
	        resolve: {
		        $title: function() { return 'Produtos Site'; }
	        }
            })
	        .state('painel.produtos-site.salvar', {
		        url: '/salvar/:id',
		        templateUrl: 'sections/produtos-site/salvar.html',
		        controller: 'EditarProdutosSiteController as vm',
		        resolve: {
			        $title: function() { return 'Salvar Produto Site'; }
		        }
	        });

	    //SERVICOS SITE
	    $stateProvider
		    .state('painel.servicos-site', {
			    abstract: true,
			    url: '/servicos-site',
			    template: '<ui-view/>',
			    defaultChild: 'painel.servicos-site.listar'
		    })
		    .state('painel.servicos-site.listar', {
			    url: '/listar',
			    templateUrl: 'sections/servicos-site/listagem.html',
			    controller: 'ListagemServicosSiteController as vm',
			    resolve: {
				    $title: function() { return 'Serviço Site'; }
			    }
		    })
		    .state('painel.servicos-site.salvar', {
			    url: '/salvar/:id',
			    templateUrl: 'sections/servicos-site/salvar.html',
			    controller: 'EditarServicosSiteController as vm',
			    resolve: {
				    $title: function() { return 'Salvar Serviço Site'; }
			    }
		    });

	    //VIDEOS SITE
	    $stateProvider
		    .state('painel.videos-site', {
			    abstract: true,
			    url: '/videos-site',
			    template: '<ui-view/>',
			    defaultChild: 'painel.videos-site.listar'
		    })
		    .state('painel.videos-site.listar', {
			    url: '/listar',
			    templateUrl: 'sections/videos-site/listagem.html',
			    controller: 'ListagemVideosSiteController as vm',
			    resolve: {
				    $title: function() { return 'Vídeo Site'; }
			    }
		    })
		    .state('painel.videos-site.salvar', {
			    url: '/salvar/:id',
			    templateUrl: 'sections/videos-site/salvar.html',
			    controller: 'EditarVideosSiteController as vm',
			    resolve: {
				    $title: function() { return 'Salvar Vídeo Site'; }
			    }
		    });

	    //PAGiNAS SITE
	    $stateProvider
		    .state('painel.paginas-site', {
			    abstract: true,
			    url: '/paginas-site',
			    template: '<ui-view/>',
			    defaultChild: 'painel.paginas-site.listar'
		    })
		    .state('painel.paginas-site.listar', {
			    url: '/listar',
			    templateUrl: 'sections/paginas-site/listagem.html',
			    controller: 'ListagemPaginasSiteController as vm',
			    resolve: {
				    $title: function() { return 'Página Site'; }
			    }
		    })
		    .state('painel.paginas-site.salvar', {
			    url: '/salvar/:id',
			    templateUrl: 'sections/paginas-site/salvar.html',
			    controller: 'EditarPaginasSiteController as vm',
			    resolve: {
				    $title: function() { return 'Salvar Página Site'; }
			    }
		    });

	    //Seja um parceiro
	    $stateProvider
		    .state('painel.seja-parceiro', {
			    abstract: true,
			    url: '/seja-parceiro',
			    template: '<ui-view/>',
			    defaultChild: 'painel.seja-parceiro.listar'
		    })
		    .state('painel.seja-parceiro.listar', {
			    url: '/listar',
			    templateUrl: 'sections/seja-parceiro/listagem.html',
			    controller: 'listagemSejaParceiroController as vm',
			    resolve: {
				    $title: function() { return 'Seja um parceiro'; }
			    }
		    });
    }
})();
