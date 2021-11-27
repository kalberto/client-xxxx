(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('ListagemUsuariosController', ListagemUsuariosController);

    ListagemUsuariosController.$inject = ['API', '$mdToast','$state','$stateParams','$location','$window'];

    function ListagemUsuariosController(API, $mdToast,$state, $stateParams,$location,$window){
        var vm = this;

        vm.usuarios = [];
        vm.carregarUsuarios = carregarUsuarios;
        vm.cadastrarUsuario = cadastrarUsuario;
        vm.acessarSite = acessarSite;

        resetUsuarios();
        if($stateParams.slug){
            var slug = $stateParams.slug;
            vm.slug = slug;
            carregarUsuarios(slug);
        }

        ////////////

        function showToast(msg){
            $mdToast.show(
                $mdToast.simple()
                    .parent(angular.element(document.querySelector('#mainContent')))
                    .textContent(msg)
                    .hideDelay(2500)
            );
        }

        function resetUsuarios(){
            vm.usuarios = [];
        }

        function carregarUsuarios(slug,reset){
            vm.requestInProgress = true;
            reset && resetUsuarios();
            var params = [];
            params.slug = slug;
            API.usuariosOrganizacao(params)
                .then(
                    function successCallback (response) {
                        vm.requestInProgress = false;
                        vm.usuarios = response.data.data;
                        if(response.data.total === 0){
                            $mdToast.show(
                                $mdToast.simple()
                                    .parent(angular.element(document.querySelector('#mainContent')))
                                    .textContent('Nenhum usuario encontrado para esse cliente')
                                    .hideDelay(1500)
                            );
                        }
                    },
                    function errorCallback (response) {
                        vm.requestInProgress = false;
                        //TODO Arrumar todos os possiveis erros
                    }
                );
        }
        function cadastrarUsuario(){
            $state.go('painel.usuarios.salvar',{slug:slug});
        }
        //Ir ao site
        function acessarSite(api_token){
            var open = false;
            var newWindow = $window.open("about:blank");
            var base_url = new $window.URL($location.absUrl()).origin;
            API.usuarioLogin(api_token)
                .then(
                    function successCallback(response) {
                        var url = base_url + '/cliente';
                        open = true;
                        newWindow.location.href = url;
                    },
                    function errorCallback(response) {
                        newWindow.location.href = base_url;
                    }
                );
        }
    }

})();
