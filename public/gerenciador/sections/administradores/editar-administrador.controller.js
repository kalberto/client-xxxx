(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('EditarAdministradoresController', EditarAdministradoresController);

    EditarAdministradoresController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig'];

    function EditarAdministradoresController(API, $mdDialog, $stateParams, $mdToast, $state, editorConfig){
        var vm = this;

        ////////////
        vm.administrador = {};
        vm.auth = {};
        vm.modulos = {};
        vm.permissions = {};
        vm.editor = editorConfig;
        vm.status = ' ';
        vm.deletar = deletar;
        vm.salvar = salvar;
        vm.changePass = changePass;
        vm.items = [1,2,3,4,5];
        vm.selected = [1];
        vm.media = {
            alias: 'administradores'
        };

        vm.hasMedia = false;
        vm.hasTempMedia = false;

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
                .title('Você tem certeza que deseja excluir esse administrador?')
                .textContent('Todos as informações sobre esse administrador serão removidas e só será possível a recuperação via banco de dados.')
                .ariaLabel()
                .targetEvent(ev)
                .ok('Excluir')
                .cancel('Cancelar');

            $mdDialog.show(confirm).then(function(){
                API.administradorDeletar($stateParams.id)
                    .then(
                        function successCallback (response) {
                            $state.go('painel.administradores.listar');
                        },
                        function errorCallback (response) {
                            showToast('Ops! Erro ao deletar');
                        }
                    );
            });
        }

        function getUserInfo(id){
            API.administradorDetalhe(id)
                .then(
                    function successCallback (response) {
                        vm.administrador = response.data;
                        if(vm.administrador.media != null && vm.administrador.media_id != null){
                            vm.hasMedia = true;
                        }
                    },
                    function errorCallback (response) {
                        //TODO Arrumar possiveis erros
                    }
                );
        }
        if($stateParams.id) getUserInfo($stateParams.id);

        function salvar(){
            vm.requestInProgress = true;
            API.administradorSalvar(vm.administrador)
                .then(
                    function successCallback (response) {
                        vm.requestInProgress = false;
                        if(!$stateParams.id) $state.go('painel.administradores.listar');
                        else showToast('Editado com sucesso!');
                    },
                    function errorCallback (response) {
                        vm.requestInProgress = false;
                        if(response.status === 500){
                            showToast("Erro no servidor");
                        }else{
                            if(response.data.error_validate[0]){
                                showToast(response.data.error_validate[0]);
                            }else{
                                showToast(response.data.msg);
                            }
                        }
                    }
                );
        }
        function changePass(){
            vm.requestInProgress = true;
            API.administradorChangePass(vm.administrador.id,vm.auth)
                .then(
                    function successCallback (response) {
                        vm.requestInProgress = false;
                        showToast('Editado com sucesso!');
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
        function getModulos(){
            API.modulosGerenciador()
                .then(
                    function successCallback (response) {
                        vm.modulos = response.data;
                    },
                    function errorCallbac (response) {
                        //TODO Arrumar possiveis erros
                    }
                );
        }
        getModulos();

        vm.fileChooser = function () {
            var  element = document.getElementById('imagem-administrador').click();
        };

        vm.enviarArquivo = function () {

            vm.requestInProgress = true;
            vm.media.alias = 'administradores';
            API.mediaUpload(vm.media)
                .then(
                    function successCallback(response) {
                        vm.administrador.media_id = response.data.media_id;
                        API.mediaDetalhe(vm.administrador.media_id).then(
                            function successCallback(response) {
                                vm.tempMedia = response.data;
                                vm.hasTempMedia = true;
                            },
                            function errorCallback(response) {
                                showToast('O arquivo foi salvo, mas ocorreu um erro ao mostrar o arquivo.');
                                vm.requestInProgress = false;
                            }
                        );
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
