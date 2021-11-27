(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('AuthController', AuthController);

    AuthController.$inject = ['API', '$mdDialog', '$mdToast', '$state','$cookies'];

    function AuthController(API, $mdDialog, $mdToast, $state,$cookies){
        var vm = this;

        vm.requestInProgress = false;
        vm.login = login;
        vm.recoverPassword = recoverPassword;

        ////////////

        function showToast(msg){
            $mdToast.show(
                  $mdToast.simple()
                    .parent(angular.element(document.querySelector('.form-content')))
                    .textContent(msg)
                    .position('bottom left right')
                    .hideDelay(1500)
                );
        }

        function login(){
            if(!vm.formLogin.$valid){
                return showToast('O campo email e senha são obrigatórios');
            }

            vm.requestInProgress = true;
            API.authLogin(vm.auth)
                .then(
                    function successCallback(response) {
                        if(typeof(response.data.auth) !== "undefined" && response.data.auth !== null){
                            localStorage.setItem('api_token',response.data.auth);

                            $cookies.put('api_token',response.data.auth);
                            $state.go('painel.dashboard');
                        }
                        vm.requestInProgress = false;
                        showToast('Usuário e/ou senha incorretos');
                    },
                    function errorCallback(response) {
                        vm.requestInProgress = false;
                        showToast('Usuário e/ou senha incorretos');

                    }
            );
        }

        function recoverPassword(){

            if(!vm.formRecuperarSenha.$valid){
                return showToast('O campo email é obrigatório');
            }

            vm.requestInProgress = true;
            API.authRecoverPassword(vm.auth)
                .success(function(data){
                    vm.requestInProgress = false;
                    $mdDialog.show(
                        $mdDialog.alert()
                        .parent(angular.element(document.querySelector('#popupContainer')))
                        .clickOutsideToClose(true)
                        .title('Enviamos um e-mail')
                        .textContent('Por favor, verifique sua caixa de entrada')
                        .ariaLabel('Alert Dialog Demo')
                        .ok('OK!')
                    );
                })
                .error(function(error){
                    vm.requestInProgress = false;
                    if(error.error === 'USER_NOT_FOUND'){
                        showToast('Nenhum usuário cadastrado com o email informado.');
                    }
                    else{
                        showToast('Erro ao enviar email de recuperação, contate o administrador');
                    }
                });
        }
        
    }

})();