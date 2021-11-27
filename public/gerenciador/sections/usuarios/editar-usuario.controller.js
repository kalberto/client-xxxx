(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('EditarUsuarioController', EditarUsuarioController);

    EditarUsuarioController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig'];

    function EditarUsuarioController(API, $mdDialog, $stateParams, $mdToast, $state, editorConfig){
        var vm = this;

        ////////////
        vm.usuario = {};
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
            alias: 'usuarios'
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
                .title('Você tem certeza que deseja excluir esse usuário?')
                .textContent('Todos as informações sobre esse usuário serão removidas e não será possível recuperar.')
                .ariaLabel()
                .targetEvent(ev)
                .ok('Excluir')
                .cancel('Cancelar');


            $mdDialog.show(confirm).then(function(){
                API.usuarioDeletar($stateParams.id)
                    .then(
                        function successCalback (response) {
                            $state.go('painel.usuarios.listar',{slug:$stateParams.slug});
                        },
                        function errorCallback (response) {
                            showToast('Ops! Erro ao deletar');
                        }
                    );
            });
        }

        function getUserInfo(id){
            API.usuarioDetalhe(id)
                .then(
                    function successCallback (response) {
                        vm.usuario = response.data;
                        if(vm.usuario.media != null && vm.usuario.media_id != null){
                            vm.hasMedia = true;
                        }
                    },
                    function errorCallback (response) {
                        //TODO Arrumar todos os possiveis erros
                    }
                );
        }
        if($stateParams.id) getUserInfo($stateParams.id);

        function salvar(){
            vm.requestInProgress = true;
            if($stateParams.slug)
                vm.usuario.slug = $stateParams.slug;
            API.usuarioSalvar(vm.usuario)
                .then(
                    function successCallback (response) {
                        vm.requestInProgress = false;
                        if(!$stateParams.id) $state.go('painel.usuarios.listar',{slug:$stateParams.slug});
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
        function changePass(){
            vm.requestInProgress = true;
            API.usuarioChangePass(vm.usuario.id,vm.auth)
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
            API.modulosCliente()
                .then(
                    function succesCallback (response) {
                        vm.modulos = response.data;
                    },
                    function errorCallback (response) {
                        //TODO Arrumar todos os possiveis erros
                    }
                );
        }
        getModulos();

        vm.fileChooser = function () {
            var  element = document.getElementById('imagem-usuario').click();
        };

        vm.enviarArquivo = function () {
            vm.requestInProgress = true;
            vm.media.alias = 'usuarios';
            API.mediaUpload(vm.media)
                .then(
                    function successCallback(response) {
                        vm.usuario.media_id = response.data.media_id;
                        API.mediaDetalhe(vm.usuario.media_id).then(
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
