(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('EditarOrganizacaoController', EditarOrganizacaoController);

    EditarOrganizacaoController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig','$mdExpansionPanel'];

    function EditarOrganizacaoController(API, $mdDialog, $stateParams, $mdToast, $state, editorConfig,$mdExpansionPanel){
        var vm = this;

        ////////////
        vm.loadingEmpresas = false;
        vm.loadingOrganizacao = false;
        vm.organizacao = {};
        vm.empresas = {};
        vm.editor = editorConfig;
        vm.status = ' ';
        vm.deletar = deletar;
        vm.salvar = salvar;
        vm.showEmpresa = showEmpresa;
        vm.loadServicos = loadServicos;
        vm.loadDocumentos = loadDocumentos;
        vm.closeExpansion = function (id) {
            $mdExpansionPanel(id).collapse();
        };
        vm.getDocumento = getDocumento;
        vm.items = [1,2,3,4,5];
        vm.selected = [1];
        vm.search='';
        vm.query = {
            limit: 50,
            page: 1
        };
        vm.hasMedia = false;
        vm.hasTempMedia = false;

        function showToast(msg){
            $mdToast.show(
                  $mdToast.simple()
                    .parent(angular.element(document.querySelector('#mainContent')))
                    .textContent(msg)
                    .hideDelay(2000)
                );
        }
        
        function deletar(ev) {

            var confirm = $mdDialog.confirm()
                .parent(angular.element(document.querySelector('#mainContent')))
                .title('Você tem certeza que deseja excluir essa organização?')
                .textContent('Todos as informações sobre essa organização serão removidas e não será possível recuperar.')
                .ariaLabel()
                .targetEvent(ev)
                .ok('Excluir')
                .cancel('Cancelar');


            $mdDialog.show(confirm).then(function(){
                API.organizacaoDeletar($stateParams.slug)
                    .then(
                        function successCalback (response) {
                            $state.go('painel.organizacoes.listar');
                        },
                        function errorCallback (response) {
                            showToast('Ops! Erro ao deletar');
                        }
                    );
            });
        }

        function getOrganizacaoInfo(slug){
            API.detalhesOrganizacao(slug)
                .then(
                    function successCallback (response) {
                        vm.organizacao = response.data;
                        vm.loadingOrganizacao = false;
                    },
                    function errorCallback (response) {
                        vm.loadingOrganizacao = false;
                        showToast(response.data.msg);
                    }
                );
        }

        function getDocumento(documento) {
            if(documento.length == 14)
                return documento.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g,"\$1.\$2.\$3\/\$4\-\$5");
            return documento.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g,"\$1.\$2.\$3\-\$4");
        }

        function showEmpresa(id) {
            if(!vm.organizacao.empresas[id].servicos && vm.organizacao.empresas[id].servicos !== true){
                loadServicos(id);
            }
        }
        function loadServicos(id) {
            vm.organizacao.empresas[id].servicos = false;
            API.getServicosDocumento(vm.organizacao.empresas[id].DOCUMENTO)
                .then(
                    function successCallback (response) {
                        vm.organizacao.empresas[id].servicos = response.data;
                    },
                    function errorCallback (response) {
                        vm.organizacao.empresas[id].servicos = true;
                    }
                );
        }

        function loadDocumentos() {
            vm.loadingEmpresas = true;
            API.organizacaoDocumentos(vm.organizacao.slug)
                .then(
                    function successCallback (response) {
                        vm.organizacao.empresas = response.data;
                        vm.loadingEmpresas = false;
                    },
                    function errorCallback (response) {
                        vm.loadingEmpresas = false;
                        showToast(response.data.msg);
                    }
                );
        }

        if($stateParams.slug){
            vm.loadingOrganizacao = true;
            getOrganizacaoInfo($stateParams.slug);
        }

        function salvar(){
            vm.requestInProgress = true;
            API.organizacaoSalvar(vm.organizacao)
                .then(
                    function successCallback (response) {
                        vm.requestInProgress = false;
                        if(!$stateParams.id) $state.go('painel.organizacoes.listar');
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



        /** Empresas**/

        vm.adicionarEmpresa = adicionarEmpresa;
        vm.removerEmpresa = removerEmpresa;

        resetEmpresas();
        carregarEmpresas();

        vm.carregarEmpresas = carregarEmpresas;
        vm.buscarEmpresas = buscarEmpresas;
        vm.isEnter = function (keyEvent) {
            if(keyEvent.which === 13)
                buscarEmpresas();
        };

        // Reseta o array de Empresas
        function resetEmpresas(){
            vm.empresas = [];
        }

        // Listagem
        function carregarEmpresas(reset){
            reset && resetEmpresas();
            vm.promise = API.empresasListar(vm.query)
                .then(
                    function successCallback (response) {
                        vm.empresasTotal = response.data.total;
                        vm.empresas = response.data.data;
                    },
                    function errorCallback (response) {
                        //TODO ARRUMAR TODOS OS POSSIVEIS ERROS
                    }
                );
        }

        function buscarEmpresas(){
            resetEmpresas();
            var query = vm.query;
            query.search = vm.search;
            vm.promise = API.empresasBuscar(query)
                .then(
                    function successCallback (response) {
                        vm.empresasTotal = response.data.total;
                        vm.empresas = response.data.data;
                    },
                    function errorCallback (response) {
                        showToast(response.data.msg);
                    }
                );
        }

        function adicionarEmpresa(documento) {
            if($stateParams.slug){
                API.adicionarEmpresa($stateParams.slug, documento)
                    .then(
                        function successCallback (response) {
                            showToast(response.data.msg);
                            loadDocumentos();
                        },
                        function errorCallback (response) {
                            showToast(response.data.msg);
                        }
                    );
            }else{
                showToast('Ocorreu um erro. Recarregue a página.');
            }
        }
        function removerEmpresa(documento) {
            if($stateParams.slug){
                API.removerEmpresa($stateParams.slug, documento)
                    .then(
                        function successCallback (response) {
                            showToast(response.data.msg);
                            loadDocumentos();
                        },
                        function errorCallback (response) {
                            showToast(response.data.msg);
                        }
                    );
            }else{
                showToast('Ocorreu um erro. Recarregue a página.');
            }
        }

    }

})();
