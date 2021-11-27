(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('salvarFaqsController', salvarFaqsController);

    salvarFaqsController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig', '$filter', 'Upload', '$timeout'];

    function salvarFaqsController(API, $mdDialog, $stateParams, $mdToast, $state, editorConfig, $filter, Upload, $timeout){
        var vm = this;
        var nome = '';
        var url = '';
        vm.faq = {};

        vm.salvar = salvar;
        vm.tynconf = editorConfig;
       
    

        function getFaqsInfo(id){
            API.faqsDetalhe(id)
                .then(
                    function successCallback (response) {
                        vm.faq = response.data;
                    },
                    function errorCallback (response) {
                        //TODO Arrumar todos os possiveis erros
                    }
                );
        }
        if($stateParams.id){
            getFaqsInfo($stateParams.id)
        }else{
            if($stateParams.url && $stateParams.nome){
                nome = $stateParams.nome;
                url = $stateParams.url;
                vm.faq.tipo_servico = nome;
                vm.faq.tipo_servico_url = url;
            }else{
                $state.go('painel.servicos.listar');
            }
        }


        function showToast(msg){
        $mdToast.show(
              $mdToast.simple()
                .parent(angular.element(document.querySelector('#mainContent')))
                .textContent(msg)
                .hideDelay(4500)
            );
        }

        function salvar(){
            vm.requestInProgress = true;
            API.faqsSalvar(vm.faq)
                .then(
                    function successCallback(response) {
                        vm.requestInProgress = false;
                        if(!$stateParams.id) $state.go('painel.faqs.listar',{url:url, nome:nome});
                        else showToast('Editado com sucesso!');
                    },
                    function errorCallback(response) {
                        vm.requestInProgress = false;
                        if(data.error_validate[0]){
                            showToast(data.error_validate[0]);
                        }else{
                            showToast(data.msg);
                        }
                    }
                );
        }
    }
})();