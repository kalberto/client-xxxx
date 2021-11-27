(function () {
    'use strict';

    angular.module('app').controller('abrirChamadosController', chamados);
    chamados.$inject = ['$scope', 'API'];
    function chamados($scope, API) {
        $scope.all = [];
        $scope.assuntos = [];
        $scope.descriptions = [];
        $scope.carregando = true;
        $scope.carregando_novo = false;
        $scope.carregando_assuntos = false;
        $scope.carregando_email = false;
        $scope.carregando_services = false;
        $scope.carregando_chamados = true;
        $scope.chamado_aberto = false;
        $scope.chamado_aberto_url = '';
        $scope.popup_manual = false;
        $scope.popup_reparo = false;
        $scope.error = false;
        $scope.chamados_ativos = [];
        $scope.chamados = [];
        $scope.produto = {
            'nome': 'Place Holder',
            'url': 'url'
        };
        $scope.form = {
            email: '',
            assunto: '',
            description: '',
            servico: ''
        };

        $scope.already_load = {
            servicos: false,
            contatos: false,
            assuntos: false
        };
        $scope.mensagem_chamado_aberto = '';
        $scope.resumo_chamado_aberto = '';

        $scope.getAssuntos = function () {
            $scope.carregando_assuntos = true;
            $scope.carregando_chamados = true;
            API.get('cliente/contato/fields').then(function successCallback(response) {
                $scope.assuntos = Object.keys(response.data.assuntos);
                $scope.all = response.data.assuntos;
                $scope.carregando_assuntos = false;
                if ($scope.carregando_email == false && $scope.carregando_services == false) $scope.carregando_chamados = false;
                $scope.already_load.assuntos = true;
            }, function errorCallback(response) {
                $scope.carregando_assuntos = false;
                if ($scope.carregando_email == false && $scope.carregando_services == false) $scope.carregando_chamados = false;
            });
        };

        $scope.getServices = function (url_produto) {
            $scope.carregando_services = true;
            $scope.carregando_chamados = true;
            API.get('cliente/chamados/services?url_produto=' + url_produto).then(function successCallback(response) {
                $scope.servicos = response.data;
                if ($scope.servicos.length == 1) $scope.form.servico = $scope.servicos[0].id;
                $scope.carregando_services = false;
                if ($scope.carregando_assuntos == false && $scope.carregando_email == false) $scope.carregando_chamados = false;
                $scope.already_load.servicos = true;
            }, function errorCallback(response) {
                $scope.carregando_services = false;
                if ($scope.carregando_assuntos == false && $scope.carregando_email == false) $scope.carregando_chamados = false;
            });
        };

        $scope.getLastChamados = function () {
            $scope.carregando = true;
            API.get('cliente/chamados/last').then(function successCallback(response) {
                $scope.chamados = response.data.chamados;
                $scope.carregando = false;
            }, function errorCallback(response) {
                $scope.carregando = false;
                $scope.error = true;
            });
        };

        $scope.getDescriptions = function (key) {
            if (key == "reparo") {
                $scope.popup_reparo = true;
                $scope.form.assunto = undefined;
            } else $scope.descriptions = $scope.all[key]['descriptions'];
        };

        $scope.popUpManual = function (nome, url) {
            $scope.produto.nome = nome;
            $scope.produto.url_manuais = base_url + '/cliente/manuais/' + url;
            $scope.produto.url_faqs = base_url + '/cliente/faqs/' + url;
            $scope.produto.url = url;
            $scope.popup_manual = true;
        };

        $scope.abrirChamado = function (url_produto) {
            $scope.popup_faq = false;
            $scope.popup_manual = false;
            $scope.falha_faq = true;
            $scope.falha_manual = true;
            if (!$scope.already_load.assuntos) $scope.getAssuntos();
            $scope.getServices(url_produto);
        };

        $scope.fecharChamado = function () {
            $scope.chamado_aberto = false;
        };

        $scope.postContato = function () {
            $scope.carregando_novo = true;
            API.post('cliente/chamado', $scope.form).then(function successCallback(response) {
                $scope.form = {
                    email: '',
                    assunto: '',
                    description: '',
                    servico: ''
                };
                $scope.chamado_aberto = true;
                $scope.falha_manual = false;
                $scope.novo_chamado = response.data.tn;
                $scope.mensagem_chamado_aberto = response.data.msg;
                $scope.resumo_chamado_aberto = response.data.resumo;
                $scope.carregando_novo = false;
                $scope.chamado_aberto_url = response.data.url;
            }, function errorCallback(response) {
                $scope.chamado_aberto = true;
                $scope.novo_chamado = response.data.msg;
                $scope.mensagem_chamado_aberto = response.data.msg;
                $scope.resumo_chamado_aberto = response.data.resumo;
                $scope.carregando_novo = false;
            });
        };

        $scope.getLastChamados();

        $scope.getDocumento = getDocumento;

        function getDocumento(documento) {
            if (documento.length === 14) return documento.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "\$1.\$2.\$3\/\$4\-\$5");
            return documento.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g, "\$1.\$2.\$3\-\$4");
        }
    }
})();