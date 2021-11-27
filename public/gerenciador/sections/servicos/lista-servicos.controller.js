(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('listagemServicosController', listagemServicosController);

    listagemServicosController.$inject = ['API', '$stateParams', '$mdToast', '$state', 'editorConfig'];

    function listagemServicosController(API, $stateParams, $mdToast, $state, editorConfig){
        var vm = this;

        vm.cadastrarServico = cadastrarServico;
        vm.carregarServicos = carregarServicos;
        vm.resetServicos = resetServicos;
        vm.selected = [];
        vm.query = {
            limit: 50,
            page: 1
        };

        resetServicos();
        carregarServicos();

        //Cadastro
        function cadastrarServico(){
            $state.go('painel.users.salvar');
        }
        
        // Reseta o array de Servicos
        function resetServicos(){
            vm.servicos = [];
        }

        // Listagem
        function carregarServicos(reset){
            reset && resetServicos();
            vm.promise = API.servicosListar(vm.query)
                .then(
                    function successCallback (response) {
                        vm.servicos = response.data;
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
