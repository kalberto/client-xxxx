(function () {
    'use strict';

    angular.module('app').controller('contatoController', contato);
    contato.$inject = ['$scope', 'API'];
    function contato($scope, API) {
        $scope.all = [];
        $scope.assuntos = [];
        $scope.descriptions = [];
        $scope.carregando = false;
        $scope.carregando_assuntos = false;
        $scope.carregando_email = false;
        $scope.carregando_services = false;
        $scope.popup = {
            ativo: false,
            titulo: "",
            mensagem: "",
            botao: "OK"
        };
        $scope.form = {
            email: '',
            assunto: '',
            description: '',
            servico: ''
        };

        $scope.getAssuntos = function () {
            $scope.carregando_assuntos = true;
            $scope.carregando = true;
            API.get('cliente/contato/fields').then(function successCallback(response) {
                $scope.assuntos = Object.keys(response.data.assuntos);
                $scope.all = response.data.assuntos;
                $scope.carregando_assuntos = false;
                if ($scope.carregando_email == false && $scope.carregando_services == false) $scope.carregando = false;
            }, function errorCallback(response) {
                $scope.carregando_assuntos = false;
                if ($scope.carregando_email == false && $scope.carregando_services == false) $scope.carregando = false;
            });
        };

        $scope.getServices = function () {
            $scope.carregando_services = true;
            $scope.carregando = true;
            API.get('cliente/contato/services').then(function successCallback(response) {
                $scope.servicos = response.data;
                if ($scope.servicos.length == 1) $scope.form.servico = $scope.servicos[0];
                $scope.carregando_services = false;
                if ($scope.carregando_assuntos == false && $scope.carregando_email == false) $scope.carregando = false;
            }, function errorCallback(response) {
                $scope.carregando_services = false;
                if ($scope.carregando_assuntos == false && $scope.carregando_email == false) $scope.carregando = false;
            });
        };

        $scope.postContato = function () {
            $scope.carregando = true;
            API.post('cliente/contato', $scope.form).then(function successCallback(response) {
                $scope.popup.ativo = true;
                $scope.popup.titulo = "SUCESSO";
                $scope.popup.mensagem = response.data.msg;
                $scope.carregando = false;
            }, function errorCallback(response) {
                $scope.popup.ativo = true;
                $scope.popup.titulo = "FALHA";
                $scope.popup.mensagem = response.data.msg;
                $scope.carregando = false;
            });
        };

        $scope.getAssuntos();
        $scope.getServices();

        $scope.getDescriptions = function (key) {
            $scope.descriptions = $scope.all[key]['descriptions'];
        };

        $scope.fecharPopup = function () {
            $scope.popup.titulo = "";
            $scope.popup.mensagem = "";
            $scope.popup.ativo = false;
        };
    }
})();