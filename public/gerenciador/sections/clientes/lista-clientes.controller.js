(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('listagemClientesController', listagemClientesController);

    listagemClientesController.$inject = ['API', '$stateParams', '$mdToast', '$state', 'editorConfig'];

    function listagemClientesController(API, $stateParams, $mdToast, $state, editorConfig){
        var vm = this;

        vm.cadastrarCliente = cadastrarCliente;
        vm.carregarClientes = carregarClientes;
        vm.buscarClientes = buscarClientes;
        vm.resetClientes = resetClientes;
        vm.selected = [];
        vm.query = {
            limit: 50,
            page: 1
        };

        resetClientes();
        carregarClientes();

        function buscarClientes()
        {
            resetClientes();
            var query = vm.query;
            query.search = vm.search;
            vm.promise = API.clientesBuscar(query)
                .then(
                    function successCallback (response) {
                        vm.clientesTotal = response.data.total;
                        vm.clientes = response.data.data;
                    },
                    function errorCallback (response) {
                        //TODO ARRUMAR TODOS OS POSSIVEIS ERROS
                    }
                );
        }


        //Cadastro
        function cadastrarCliente(){
            $state.go('painel.users.salvar');
        }
        
        // Reseta o array de Clientes
        function resetClientes(){
            vm.clientes = [];
        }

        // Listagem
        function carregarClientes(reset){
            reset && resetClientes();
            vm.promise = API.clientesListar(vm.query)
                .then(
                    function successCallback (response) {
                        vm.clientesTotal = response.data.total;
                        vm.clientes = response.data.data;
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
