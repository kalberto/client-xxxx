(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('ListagemVideosSiteController', ListagemVideosSiteController);

	ListagemVideosSiteController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig'];

    function ListagemVideosSiteController(API, $mdDialog, $stateParams, $mdToast, $state, editorConfig){
        var vm = this;

	    vm.cadastrarVideoSite = cadastrarVideoSite;
        vm.carregarVideosSite = carregarVideosSite;
        vm.resetVideosSite = resetVideosSite;
        vm.selected = [];
        vm.query = {
            order: 'id',
            limit: 20,
            page: 1,
	        q:''
        };

	    carregarVideosSite();
        
        // Reseta o array de registros
        function resetVideosSite(){
            vm.registros.data = [];
        }

        // Listagem
        function carregarVideosSite(reset){
           
            reset && resetVideosSite();

            vm.promise = API.videosSiteListar(vm.query)
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
	    function cadastrarVideoSite(){
		    $state.go('painel.videos-site.salvar');
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
