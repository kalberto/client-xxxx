(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('ListagemAdministradoresController', ListagemAdministradoresController);

    ListagemAdministradoresController.$inject = ['API', '$state'];

    function ListagemAdministradoresController(API, $state){
        var vm = this;

        vm.query = {
            order: 'id',
            limit: 10,
            page: 1,
            q:''
        };

        vm.registros = [];
        vm.carregarAdministradores = carregarAdministradores;
        vm.cadastrarAdministradores = cadastrarAdministradores;

        resetAdministradores();
        carregarAdministradores();

        ////////////

        // Reseta o array de administradores
        function resetAdministradores(){
            vm.registros.data = [];
        }

        function carregarAdministradores(reset){
            vm.requestInProgress = true;

            reset && resetAdministradores();

            API.administradoresListar(vm.query)
                .then(
                    function successCallback(response) {
                        vm.requestInProgress = false;

                        vm.registros = response.data;
                    },
                    function errorCallback(response) {
                        vm.requestInProgress = false;
                        //TODO arrumar os possiveis erros
                    }
                );
        }

        function cadastrarAdministradores(){
            $state.go('painel.administradores.salvar');
        }

    }

})();
