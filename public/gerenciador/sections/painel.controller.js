(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('PainelController', PainelController);

    PainelController.$inject = ['API', '$mdSidenav', '$state', 'EnvironmentConfig','$cookies'];

    function PainelController(API, $mdSidenav, $state, EnvironmentConfig, $cookies){
        var vm = this;


        vm.baseUrl = EnvironmentConfig.baseUrl;
        vm.toggleSideNav = toggleSideNav;
        vm.logout = logout;
        vm.user = {id: false};

        ////////////
        
        function toggleSideNav() {
            $mdSidenav('left').toggle();
        }

        function logout(){
            API.authLogout()
                .then(
                    function successCallback(response) {
                        $state.go('auth-login');
                    },
                    function errorCallback(response) {
                        $state.go('auth-login');
                    }
                );
        }

        function getUserInfo(){
            var token = $cookies.get('api_token');
	        if(token !== undefined && token !== null && token !== '')
		        token = localStorage.getItem('api_token');
            if(token !== undefined && token !== null && token !== ''){
                API.authGetCurrentUser()
                    .then(
                        function successCallback(response) {
                            vm.user = response.data;
                            getMenuByUser(vm.user.id);

                        },
                        function errorCallback(response) {
                            vm.requestInProgress = false;
                            //showToast('Usuário e/ou senha incorretos');

                            //TODO analisar os possiveis erros e desenvolver soluções

                        }
                    );
            }else{
                $state.go('auth-login');
            }
        }
        getUserInfo();

        function getMenuByUser(id){
            API.administradorMenu(id)
                .then(
                    function successCallback(response) {
                        vm.menus = response.data;
                    },
                    function errorCallback(response) {
                        //TODO analisar os possiveis erros e desenvolver soluções
                    }
                );
        }
    }

})();