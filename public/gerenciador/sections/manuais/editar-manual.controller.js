(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('EditarManualController', EditarManualController);

    EditarManualController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast','Upload', '$state', 'editorConfig'];

    function EditarManualController(API, $mdDialog, $stateParams, $mdToast,Upload, $state, editorConfig){
        var vm = this;
        var nome = '';
        var url = '';
        ////////////
        vm.manual = {};
        vm.editor = editorConfig;
        vm.status = ' ';
        vm.deletar = deletar;
        vm.salvar = salvar;
        vm.enviarArquivo = enviarArquivo;

        vm.items = [1,2,3,4,5];
        vm.selected = [1];


        function getManualInfo(id){
            API.manualDetalhe(id)
                .then(
                    function successCallback (response) {
                        vm.manual = response.data;
                        vm.media = vm.manual.media;
                    },
                    function errorCallback (response) {
                        //TODO Arrumar todos os possiveis erros
                    }
                );
        }
        if($stateParams.id){
            getManualInfo($stateParams.id);
        }else{
            if($stateParams.url && $stateParams.nome){
                nome = $stateParams.nome;
                url = $stateParams.url;
                vm.manual.tipo_servico = nome;
                vm.manual.tipo_servico_url = url;
            }else{
                $state.go('painel.servicos.listar');
            }
        }

        function showToast(msg){
            $mdToast.show(
                  $mdToast.simple()
                    .parent(angular.element(document.querySelector('#mainContent')))
                    .textContent(msg)
                    .hideDelay(1500)
                );
        }

        function deletar(ev) {
            var confirm = $mdDialog.confirm()
                .parent(angular.element(document.querySelector('#mainContent')))
                .title('Você tem certeza que deseja excluir esse manual?')
                .textContent('Todos as informações sobre esse manual serão removidas e não será possível recuperar.')
                .ariaLabel()
                .targetEvent(ev)
                .ok('Excluir')
                .cancel('Cancelar');
            $mdDialog.show(confirm).then(function(){
                API.manualDeletar($stateParams.id)
                    .then(
                        function successCalback (response) {
                            $state.go('painel.manuais.listar',{url:url, nome:nome});
                        },
                        function errorCallback (response) {
                            showToast('Ops! Erro ao deletar');
                        }
                    );
            });
        }

        function salvar(){
            vm.requestInProgress = true;
            API.manualSalvar(vm.manual)
                .then(
                    function successCallback (response) {
                        vm.requestInProgress = false;
                        if(!$stateParams.id) $state.go('painel.manuais.listar',{url:url, nome:nome});
                        else showToast('Editado com sucesso!');
                    },
                    function errorCallback (response) {
                        vm.requestInProgress = false;
                        if(response.data.error_validate[0]){
                            showToast(response.data.error_validate[0]);
                        }else{
                            showToast(response.data.msg);
                        }
                    }
                );
        }

        function enviarArquivo() {

            vm.requestInProgress = true;
            vm.media.alias = 'manuais';
            API.mediaUpload(vm.media)
                .then(
                    function successCallback(response) {
                        vm.manual.media_id = response.data.media_id;
                        vm.requestInProgress = false;
                    },
                    function errorCallback(response) {
                        showToast('Erro ao enviar o arquivo, tente novamente.');
                        vm.requestInProgress = false;
                    }
                );
        }
    }
})();
