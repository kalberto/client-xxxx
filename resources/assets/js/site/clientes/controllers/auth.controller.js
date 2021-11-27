(function () {
    'use strict';
    angular.module('app').controller('authController',auth);
    auth.$inject = ['$scope', 'API', '$window'];
    function auth($scope, API, $window) {
        // Scope Vars
        $scope.error = false;
        //$scope.auth = {};
        $scope.popup = {
            ativo: false,
            titulo: "",
            mensagem: "",
            botao: ""
        };

        // Vars
        const routes = {
            login: "clientes/auth/login",
            recuperacao: "clientes/auth/password/email",
            alterar: 'clientes/auth/password/reset',
            dashboard: "cliente"
        };

        // Scope Methods
        $scope.login = function() {
            API.post(routes.login, $scope.auth).then(
                function successCallback(response) {
                    $window.location.pathname = $window.location.pathname.replace(routes.login, routes.dashboard);
                    return false;
                },
                function errorCallback(response) {
                    $scope.popup.ativo = true;
                    $scope.popup.titulo = "Falha!";
                    if(response.data && response.data.msg != undefined)
                        $scope.popup.mensagem = response.data.msg;
                    else
                        $scope.popup.mensagem = "Tente novamente mais tarde";
                    $scope.popup.botao = "Tente novamente";
                }
            )
        };

        $scope.recuperar = function() {
            API.post(routes.recuperacao, $scope.auth).then(
                function successCallback(response) {
                    $scope.popup.ativo = true;
                    $scope.popup.titulo = "Sucesso!";
                    $scope.popup.mensagem = response.data.msg;
                    $scope.popup.botao = "Voltar";
                    return false;
                },
                function errorCallback(response) {
                    $scope.popup.ativo = true;
                    $scope.popup.titulo = "Falha!";
                    $scope.popup.mensagem = response.data.msg;
                    $scope.popup.botao = "Tente novamente";
                }
            )
        };

        $scope.alterarSenha = function() {
            if ($scope.auth.password === $scope.auth.password_confirmation) {
                $scope.auth.token = token_usr ;
                API.post(routes.alterar,$scope.auth).then(
                    function successCallback(response) {
                        window.location.replace(response.data.url);
                    },
                    function errorCallback (response) {
                        $scope.popup.ativo = true;
                        $scope.popup.titulo = "Falha!";
                        $scope.popup.mensagem = response.data.msg;
                        $scope.popup.botao = "Tente novamente";
                    }
                );

                // TODO
            } else {
                console.log("Senhas Diferentes");
                $scope.popup.ativo = true;
                $scope.popup.titulo = "Falha!";
                $scope.popup.mensagem = "As senhas que você digitou não são iguais.";
                $scope.popup.botao = "Tente novamente";
            }
        };

        $scope.fecharPopup = function() {
            if($('#password').length > 0)
                $scope.auth.password = "";
            $scope.popup.titulo = "";
            $scope.popup.mensagem = "";
            $scope.popup.botao = "";
            $scope.popup.ativo = false;
        };
    }
})();