(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('listagemOrganizacoesController', listagemOrganizacoesController);

    listagemOrganizacoesController.$inject = ['API', '$stateParams', '$mdToast', '$state', 'editorConfig'];

    function listagemOrganizacoesController(API, $stateParams, $mdToast, $state, editorConfig){
        var vm = this;

        vm.cadastrarOrganizacao = cadastrarOrganizacao;
        vm.carregarOrganizacoes = carregarOrganizacoes;
        vm.buscarOrganizacoes = buscarOrganizacoes;
        vm.resetOrganizacoes = resetOrganizacoes;
        vm.selected = [];
        vm.query = {
            limit: 50,
            page: 1
        };

        resetOrganizacoes();
        carregarOrganizacoes();

        function buscarOrganizacoes()
        {
            resetOrganizacoes();
            var query = vm.query;
            query.q = vm.search;
            vm.promise = API.organizacoesListar(query)
                .then(
                    function successCallback (response) {
                        vm.organizacoesTotal = response.data.total;
                        vm.organizacoes = response.data.data;
                    },
                    function errorCallback (response) {
                        //TODO ARRUMAR TODOS OS POSSIVEIS ERROS
                    }
                );
        }


        //Cadastro
        function cadastrarOrganizacao(){
            $state.go('painel.organizacoes.criar');
        }
        
        // Reseta o array de Organizacoes
        function resetOrganizacoes(){
            vm.organizacoes = [];
        }

        // Listagem
        function carregarOrganizacoes(reset){
            reset && resetOrganizacoes();
            vm.promise = API.organizacoesListar(vm.query)
                .then(
                    function successCallback (response) {
                        vm.organizacoesTotal = response.data.total;
                        vm.organizacoes = response.data.data;
                    },
                    function errorCallback (response) {
                        //TODO ARRUMAR TODOS OS POSSIVEIS ERROS
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
