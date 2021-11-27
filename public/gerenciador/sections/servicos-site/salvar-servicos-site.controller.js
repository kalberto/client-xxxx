(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('EditarServicosSiteController', EditarServicosSiteController);

	EditarServicosSiteController.$inject = ['$scope','API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig', '$filter', 'Upload', '$timeout'];

    function EditarServicosSiteController($scope,API, $mdDialog, $stateParams, $mdToast, $state, editorConfig, $filter, Upload, $timeout){
        var vm = this;
        vm.registro = {};
        vm.languages = [];
	    vm.servicos = {};
        vm.videos = {};
        vm.salvar = salvar;
        vm.tynconf = editorConfig;
	    vm.enviarImagem = enviarImagem;
	    vm.removerImagem = removerImagem;

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

        function getServicoSiteInfo(id){
            API.servicosSiteDetalhe(id)
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
	    if($stateParams.id) getServicoSiteInfo($stateParams.id);



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
            API.servicosSiteSalvar(vm.registro)
                .then(
                    function successCallback(response) {
                        vm.requestInProgress = false;
                        if(!$stateParams.id) $state.go('painel.servicos-site.listar');
                        else showToast('Editado com sucesso!');

                    },
                    function errorCallback(response) {
                        vm.requestInProgress = false;
                        if(response.data.error_validate[0]){
                            showToast(response.data.error_validate[0]);
                        }else{
                            showToast(response.data.msg);
                        }
                    }
                );
        }

	    function enviarImagem(){
		    vm.requestInProgress = true;
		    let upload_data = vm.file;
		    upload_data.alias = 'servicos-site';
		    API.mediaUpload(upload_data)
			    .success(function(response){
				    vm.registro.media_id = response.media_id;
				    vm.requestInProgress = false;
			    })
			    .error(function(){
				    showToast('Erro ao enviar imagem, tente novamente.');
				    vm.requestInProgress = false;
			    });
	    }

	    function removerImagem(){
		    vm.registro.media_id = null;
		    vm.file = null;
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