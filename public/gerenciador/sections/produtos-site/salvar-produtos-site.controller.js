(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('EditarProdutosSiteController', EditarProdutosSiteController);

	EditarProdutosSiteController.$inject = ['$scope','API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig', '$filter', 'Upload', '$timeout'];

    function EditarProdutosSiteController($scope,API, $mdDialog, $stateParams, $mdToast, $state, editorConfig, $filter, Upload, $timeout){
        var vm = this;
        vm.registro = {
	        languages:[]
        };
        vm.languages = [];
        vm.servicos = {};
	    vm.videos = {};
        vm.salvar = salvar;
        vm.tynconf = editorConfig;
       
        function getLanguages(){
            API.getLanguages()
                .then(
	                function successCallback (response) {
		                vm.languages = response.data.registros;
		                for(var i = 0; i < vm.languages.length; i++){
			                getServicosSite(vm.languages[i].locale);
			                getVideosSite(vm.languages[i].locale);
		                }
	                },
	                function errorCallback (response) {
		                //TODO Arrumar todos os possiveis erros
	                }
                );
        }
        
        function getServicosSite(locale) {
        	API.getServicosSite(locale)
		        .then(
			        function successCallback (response) {
				        vm.servicos[locale] = response.data.registros;
			        },
			        function errorCallback (response) {
				        //TODO Arrumar todos os possiveis erros
			        }
		        );
        }

	    function getVideosSite(locale) {
		    API.getVideosSite(locale)
			    .then(
				    function successCallback (response) {
					    vm.videos[locale] = response.data.registros;
				    },
				    function errorCallback (response) {
					    //TODO Arrumar todos os possiveis erros
				    }
			    );
	    }

        function getProdutoSiteInfo(id){
            API.produtosSiteDetalhe(id)
                .then(
                    function successCallback (response) {
                        vm.registro = response.data;
                    },
                    function errorCallback (response) {
                        //TODO Arrumar todos os possiveis erros
                    }
                );
        }
	    getLanguages();
        getProdutoSiteInfo($stateParams.id);

        function showToast(msg){
        $mdToast.show(
              $mdToast.simple()
                .parent(angular.element(document.querySelector('#mainContent')))
                .textContent(msg)
                .hideDelay(4500)
            );
        }

        function salvar(ev){
            var confirm = $mdDialog.confirm()
                .parent(angular.element(document.querySelector('#mainContent')))
                .title('Você deseja alterar este conteúdo na versão em inglês também?')
                //.textContent('')
                .ariaLabel()
                .targetEvent(ev)
                .ok('Sim')
                .cancel('Não');
            $mdDialog.show(confirm).then(function(){
                vm.selectedTab = 1;
            },function () {
                sendBack();
            });
        }

        function sendBack() {
            vm.requestInProgress = true;
            API.produtosSiteSalvar(vm.registro)
                .then(
                    function successCallback(response) {
                        vm.requestInProgress = false;
                        if(!$stateParams.id) $state.go('painel.produtos-site.listar');
                        else showToast('Editado com sucesso!');
                    },
                    function errorCallback(response) {
                        vm.requestInProgress = false;
                        if(response.data.error_validate[0]){
                            showToast(response.data.error_validate[0]);
                        }else{
                            showToast(response.msg);
                        }
                    }
                );
        }

	    $scope.changeExterno = function () {
        	if(vm.registro && !vm.registro.externo){
		        for(var i = 0; i < vm.languages.length; i++){
			        vm.registro.languages[vm.languages[i].locale].link = '';
		        }
	        }
        }
    }
})();