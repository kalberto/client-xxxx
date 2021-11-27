(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('CriarOrganizacaoController', CriarOrganizacaoController);

    CriarOrganizacaoController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig'];

    function CriarOrganizacaoController(API, $mdDialog, $stateParams, $mdToast, $state, editorConfig){
        var vm = this;
        vm.organizacao = {};
        vm.salvar = salvar;

        function salvar(){
            vm.requestInProgress = true;
            API.organizacaoSalvar(vm.organizacao)
                .then(
                    function successCallback (response) {
                        vm.requestInProgress = false;
                        $state.go('painel.organizacoes.salvar',{slug:response.data.slug});
                    },
                    function errorCallback (response) {
                        vm.requestInProgress = false;
                        if(response.status === 500){
                            showToast("Erro no servidor");
                        }else{

                            if(response.data.error_validate){
                                let erros = response.data.error_validate;
                                let mensagens = Object.getOwnPropertyNames(erros);
                                mensagens.forEach(function(c) {
                                    erros[c].forEach(function (item) {
                                        showToast(item);
                                    });
                                });
                            }else{
                                showToast(response.data.msg);
                            }
                        }
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
    }
})();
