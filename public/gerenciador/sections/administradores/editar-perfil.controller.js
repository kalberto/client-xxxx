(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('PerfilController', PerfilController);

    PerfilController.$inject = ['API', '$mdToast'];

    function PerfilController(API, $mdToast){
        var vm = this;

        vm.usuario = {};
        vm.auth = {};
        vm.requestInProgress = false;
        vm.salvar = salvar;
        vm.changePass = changePass;
        vm.media = {
            alias: 'usuarios'
        };
        vm.hasMedia = false;
        ////////////

        function showToast(msg){
            $mdToast.show(
                  $mdToast.simple()
                    .parent(angular.element(document.querySelector('#mainContent')))
                    .textContent(msg)
                    .hideDelay(1500)
                );
        }

        function getUserInfo(){
            API.authGetCurrentUser()
                .then(
                    function successCallback(response) {
                        vm.usuario = response.data;

                    },
                    function errorCallback(response) {
                        vm.requestInProgress = false;
                        //showToast('Usuário e/ou senha incorretos');
                        //TODO analisar os possiveis erros e desenvolver soluções
                    }
                );
        }
        getUserInfo();

        function salvar(){
        	vm.requestInProgress = true;
        	API.administradorSalvar(vm.usuario)
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
        function changePass(){
            vm.requestInProgress = true;
            API.administradorChangePass(vm.usuario.id,vm.auth)
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
