(function () {
    'use strict';

    angular.module('app').controller('perfilController', perfil);
    perfil.$inject = ['$scope', 'API', '$http', 'Upload'];
    function perfil($scope, API, $http, Upload) {
        $scope.carregando_endereco = false;
        $scope.carregando_senha = false;
        $scope.carregando_foto = false;
        $scope.hasTempMedia = false;
        $scope.error = false;
        $scope.media = {
            alias: 'usuarios' };
        $scope.errors = {
            password: [],
            new_password: [],
            new_password_confirmation: []
        };
        $scope.editarEndereco = function () {
            $scope.carregando_endereco = true;
            API.post('cliente/perfil/editar', $scope.usuario).then(function successCallback(response) {
                $scope.carregando_endereco = false;
                $('#botao_editar').addClass('sucesso');
                $('#botao_editar').attr('disabled', 'disabled');
                setTimeout(function () {
                    $('#botao_editar').removeClass('sucesso');
                    $('#botao_editar').removeAttr('disabled');
                }, 3000);
            }, function errorCallback(response) {
                $scope.carregando_endereco = false;
                $('#botao_editar').addClass('erro');
                $('#botao_editar').attr('disabled', 'disabled');
                setTimeout(function () {
                    $('#botao_editar').removeClass('erro');
                    $('#botao_editar').removeAttr('disabled');
                }, 3000);
            });
        };

        $scope.editarSenha = function () {
            console.log('click');
            var keys = ['password', 'new_password', 'new_password_confirmation'];
            for (var i = 0; i < keys.length; i++) {
                $scope.errors[keys[i]] = [];
            }
            $scope.carregando_senha = true;
            API.post('cliente/perfil/senha', $scope.auth).then(function successCallback(response) {
                $scope.carregando_senha = false;
                $('#botao_senha').addClass('sucesso');
                $('#botao_senha').attr('disabled', 'disabled');
                setTimeout(function () {
                    $('#botao_senha').removeClass('sucesso');
                    $('#botao_senha').removeAttr('disabled');
                }, 3000);
            }, function errorCallback(response) {
                console.log(response);
                if (response.data && response.data.error_validate) {
                    var errors = response.data.error_validate;
                    var keys = ['password', 'new_password', 'new_password_confirmation'];
                    for (var i = 0; i < keys.length; i++) {
                        if (errors[keys[i]]) {
                            $scope.errors[keys[i]] = errors[keys[i]];
                        }
                    }
                }
                $scope.carregando_senha = false;
                $('#botao_senha').addClass('erro');
                $('#botao_senha').attr('disabled', 'disabled');
                setTimeout(function () {
                    $('#botao_senha').removeClass('erro');
                    $('#botao_senha').removeAttr('disabled');
                }, 3000);
            });
        };

        $scope.autocompletePorCep = function () {
            if ($scope.usuario.endereco.cep.length >= 8) {
                $http.get('https://viacep.com.br/ws/' + $scope.usuario.endereco.cep + '/json/').then(function successCallback(response) {
                    $scope.usuario.endereco.bairro = response.data.bairro;
                    $scope.usuario.endereco.endereco = response.data.logradouro;
                    $scope.usuario.endereco.cidade = response.data.localidade;
                    $scope.usuario.endereco.uf = response.data.uf;
                }, function errorCallback(response) {});
            }
        };

        $scope.enviarArquivo = function (file) {
            $scope.carregando_foto = true;
            if (file) {
                Upload.upload({
                    url: base_url + '/cliente/perfil/media',
                    data: {
                        file: file,
                        alias: 'usuarios'
                    }
                }).then(function successCallback(response) {
                    $scope.usuario.media_id = response.data.media_id;
                    API.get('cliente/perfil/media/' + $scope.usuario.media_id).then(function successCallback(response) {
                        $scope.tempMedia = response.data;
                        $scope.tempMedia.src = base_url + '/' + $scope.tempMedia.media_root.path + $scope.tempMedia.nome;
                        $scope.hasTempMedia = true;
                        $scope.carregando_foto = false;
                    }, function errorCallback(response) {
                        $scope.carregando_foto = false;
                    });
                }, function errorCallback(response) {
                    $scope.carregando_foto = false;
                });
            }
        };
    }
})();