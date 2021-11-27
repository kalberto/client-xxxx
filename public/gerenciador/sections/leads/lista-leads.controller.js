(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('listagemLeadsController', listagemLeadsController);

    listagemLeadsController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig'];

    function listagemLeadsController(API, $mdDialog, $stateParams, $mdToast, $state, editorConfig){
        var vm = this;

        vm.carregarLeads = carregarLeads;
        vm.resetLeads = resetLeads;
        vm.selected = [];
        vm.query = {
            sort: 'email',
            limit: 50,
            page: 1
        };

        vm.donwloadLead = function () {
            window.open(API.urlDownloadLead(),'blank');
        };

        resetLeads();
        carregarLeads();

        vm.deletarLead = function (ev,index,id) {
            var confirm = $mdDialog.confirm()
                .parent(angular.element(document.querySelector('#mainContent')))
                .title('Você tem certeza que deseja excluir esse lead?')
                .textContent('Todos as informações sobre esse lead serão removidas.')
                .ariaLabel()
                .targetEvent(ev)
                .ok('Excluir')
                .cancel('Cancelar');
            $mdDialog.show(confirm).then(function () {
                API.deletarLead(id).then(
                    function successCallback (response) {
                        showToast('Lead deletedo!');
                        vm.leads.data.splice(index,1);
                    },
                    function errorCallback (response) {
                        showToast('Ocorreu um erro!');
                    }
                );
            });
        };

        function resetLeads(){
            vm.leads = [];
        }

         // Listagem
        function carregarLeads(reset){
           
            reset && resetLeads();

            vm.promise = API.leadsListar(vm.query)
                .then(
                    function successCallback (response) {
                        vm.leads = response.data;
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
