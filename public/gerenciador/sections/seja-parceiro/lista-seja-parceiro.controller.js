(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('listagemSejaParceiroController', listagemSejaParceiroController);

	listagemSejaParceiroController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig'];

    function listagemSejaParceiroController(API, $mdDialog, $stateParams, $mdToast, $state, editorConfig){
        var vm = this;

        vm.carregarRegistros = carregarRegistros;
        vm.resetRegistros = resetRegistros;
        vm.selected = [];
        vm.registros = {
        	data:[]
        };
        vm.query = {
            sort: 'created_at',
            limit: 50,
            page: 1
        };

        vm.donwloadSejaParceiro = function () {
            window.open(API.urlDownloadSejaParceiro(),'blank');
        };

        resetRegistros();
	    carregarRegistros();

        vm.deletarRegistro = function (ev,index,id) {
            var confirm = $mdDialog.confirm()
                .parent(angular.element(document.querySelector('#mainContent')))
                .title('Você tem certeza que deseja excluir esse registro?')
                .textContent('Todos as informações sobre esse registro serão removidas.')
                .ariaLabel()
                .targetEvent(ev)
                .ok('Excluir')
                .cancel('Cancelar');
            $mdDialog.show(confirm).then(function () {
                API.deletarSejaParceiro(id).then(
                    function successCallback (response) {
                        showToast('Registro deletedo!');
                        vm.registros.data.splice(index,1);
                    },
                    function errorCallback (response) {
                        showToast('Ocorreu um erro!');
                    }
                );
            });
        };

        function resetRegistros(){
	        vm.registros.data = [];
        }

         // Listagem
        function carregarRegistros(reset){
           
            reset && resetRegistros();

            vm.promise = API.sejaParceiroListar(vm.query)
                .then(
                    function successCallback (response) {
                        vm.registros = response.data;
                    },
                    function errorCallback(response) {
                        //TODO ARRUMAR POSSIVEIS ERROS
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
