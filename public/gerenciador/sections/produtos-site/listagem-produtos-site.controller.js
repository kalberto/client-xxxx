(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('ListagemProdutosSiteController', ListagemProdutosSiteController);

	ListagemProdutosSiteController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig'];

    function ListagemProdutosSiteController(API, $mdDialog, $stateParams, $mdToast, $state, editorConfig){
        var vm = this;

        vm.carregarProdutosSite = carregarProdutosSite;
        vm.resetProdutosSite = resetProdutosSite;
        vm.selected = [];
        vm.query = {
            order: 'id',
            limit: 20,
            page: 1,
	        q:''
        };

        carregarProdutosSite();
        
        // Reseta o array de registros
        function resetProdutosSite(){
            vm.registros.data = [];
        }

        // Listagem
        function carregarProdutosSite(reset){
           
            reset && resetProdutosSite();

            vm.promise = API.produtosSiteListar(vm.query)
                .then(
                    function successCallback (response) {
                        vm.registros = response.data;
                    },
                    function errorCallback (response) {
                        console.log('ERRO');
                    }
                );
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
