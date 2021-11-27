(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('ListagemPaginasSiteController', ListagemPaginasSiteController);

	ListagemPaginasSiteController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig'];

    function ListagemPaginasSiteController(API, $mdDialog, $stateParams, $mdToast, $state, editorConfig){
        var vm = this;

	    vm.cadastrarPaginaSite = cadastrarPaginaSite;
        vm.carregarPaginasSite = carregarPaginasSite;
        vm.resetPaginasSite = resetPaginasSite;
        vm.selected = [];
        vm.query = {
            order: 'id',
            limit: 20,
            page: 1,
	        q:''
        };

	    carregarPaginasSite();
        
        // Reseta o array de registros
        function resetPaginasSite(){
            vm.registros.data = [];
        }

        // Listagem
        function carregarPaginasSite(reset){
           
            reset && resetPaginasSite();

            vm.promise = API.paginasSiteListar(vm.query)
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
	    function cadastrarPaginaSite(){
		    $state.go('painel.paginas-site.salvar');
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
