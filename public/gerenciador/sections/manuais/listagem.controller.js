(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('ListagemManuaisController', ListagemManuaisController);

    ListagemManuaisController.$inject = ['API', '$mdDialog','$mdToast', '$state','$stateParams'];

    function ListagemManuaisController(API, $mdDialog, $mdToast, $state, $stateParams){
        var vm = this;

        vm.manuais = [];
        vm.carregarManuais = carregarManuais;
        vm.cadastrarManual = cadastrarManual;
        vm.deletar = deletar;

        resetManuais();
        if($stateParams.url && $stateParams.nome){
            var nome = $stateParams.nome;
            var url = $stateParams.url;
            carregarManuais(url);
        }else{
            $state.go('painel.servicos.listar');
        }

        ////////////

        function showToast(msg){
            $mdToast.show(
                $mdToast.simple()
                    .parent(angular.element(document.querySelector('#mainContent')))
                    .textContent(msg)
                    .hideDelay(1500)
            );
        }

        function resetManuais(){
            // Reseta o array de usuarios
            vm.manuais = [];
        }

        function carregarManuais(url,reset){
            vm.requestInProgress = true;
            reset && resetManuais();
            API.manuaisServico(url)
                .then(
                    function successCallback (response) {
                        vm.requestInProgress = false;
                        vm.manuais = response.data;
                    },
                    function errorCallback (response) {
                        vm.requestInProgress = false;
                        //TODO Arrumar todos os possiveis erros
                    }
                );
        }

        function cadastrarManual(){
            $state.go('painel.manuais.salvar',{nome:nome, url:url});
        }

        //Deletar
        function deletar(ev, id, index) {
            var confirm = $mdDialog.confirm()
                .parent(angular.element(document.querySelector('#mainContent')))
                .title('Você tem certeza que deseja excluir esse manual?')
                .textContent('Todas as informações sobre esse manual serão removidas e não será possível recuperar.')
                .ariaLabel()
                .targetEvent(ev)
                .ok('Excluir')
                .cancel('Cancelar');

            $mdDialog.show(confirm).then(function(){
                API.manualDeletar(id)
                    .then(
                        function successCallback(response) {
                            // Remove
                            // vm.produtos = data;
                            vm.manuais.data.splice(index, 1);
                            showToast('Manual deletado com sucesso!');
                        },
                        function errorCallback(response) {
                            showToast('Ops! Erro ao deletar');
                            console.log(response);
                        }
                    );
            });
        }
    }
})();
