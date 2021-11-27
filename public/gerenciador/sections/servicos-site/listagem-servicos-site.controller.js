(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('ListagemServicosSiteController', ListagemServicosSiteController);

	ListagemServicosSiteController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig'];

    function ListagemServicosSiteController(API, $mdDialog, $stateParams, $mdToast, $state, editorConfig){
        var vm = this;

	    vm.cadastrarServicoSite = cadastrarServicoSite;
        vm.carregarServicosSite = carregarServicosSite;
        vm.resetServicosSite = resetServicosSite;
        vm.selected = [];
        vm.query = {
            order: 'id',
            limit: 20,
            page: 1,
	        q:''
        };

	    carregarServicosSite();
        
        // Reseta o array de registros
        function resetServicosSite(){
            vm.registros.data = [];
        }

        // Listagem
        function carregarServicosSite(reset){
           
            reset && resetServicosSite();

            vm.promise = API.servicosSiteListar(vm.query)
                .then(
                    function successCallback (response) {
                        vm.registros = response.data;
                    },
                    function errorCallback (response) {
                        console.log('ERRO');
                    }
                );
        }

	    //Cadastro
	    function cadastrarServicoSite(){
		    $state.go('painel.servicos-site.salvar');
	    }

        function showToast(msg){
            $mdToast.show(
                  $mdToast.simple()
                    .parent(angular.element(document.querySelector('#mainContent')))
                    .textContent(msg)
                    .hideDelay(1500)
                );
        }
    }
})();
