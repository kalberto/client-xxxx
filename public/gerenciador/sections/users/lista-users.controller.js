(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('listagemUsersController', listagemUsersController);

    listagemUsersController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig','$location','$window'];

    function listagemUsersController(API, $mdDialog, $stateParams, $mdToast, $state, editorConfig,$location,$window){
        var vm = this;

        vm.carregarUsers = carregarUsers;
        vm.resetUsers = resetUsers;
        vm.acessarSite = acessarSite;

        vm.query = {
            sort: 'email',
            limit: 20,
            page: 1,
            q: ''
        };

        resetUsers();
        carregarUsers();

        function resetUsers(){
            vm.leads = [];
        }

         // Listagem
        function carregarUsers(reset){
           
            reset && resetUsers();

            vm.promise = API.usuariosListar(vm.query)
                .then(
                    function successCallback (response) {
                        vm.usuarios = response.data;
                    },
                    function errorCallback(response) {
                        showToast('Ecorreu um erro.');
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
