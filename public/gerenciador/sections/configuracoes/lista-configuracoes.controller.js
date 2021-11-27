(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('listagemConfiguracoesController', listagemConfiguracoesController);

    listagemConfiguracoesController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig'];

    function listagemConfiguracoesController(API, $mdDialog, $stateParams, $mdToast, $state, editorConfig){
        var vm = this;
        vm.configuracao = {};

       	vm.salvar = salvar;


        function getConfiguracaoInfo(){
            API.configuracoesDetalhe()
                .then(
                    function successCallback (response) {
                        vm.configuracao = response.data;
                    },
                    function errorCallback (response) {
                        console.log(response);
                        //TODO ARRUMAR POSSIVEIS ERROS
                    }
                );
        }
       	getConfiguracaoInfo();

   
        function showToast(msg){
            $mdToast.show(
              $mdToast.simple()
                .parent(angular.element(document.querySelector('#mainContent')))
                .textContent(msg)
                .hideDelay(1500)
            );
        }

   		function salvar(){
            vm.requestInProgress = true;
            API.configuracoesSalvar(vm.configuracao)
                .then(
                    function successCallback (response) {
                        vm.requestInProgress = false;
                        showToast('Configurações salvas com sucesso!');
                    },
                    function errorCallback (response) {
                        vm.requestInProgress = false;
                        showToast('Ops! Erro');
                    }
                );
        }
      
    }

})();
