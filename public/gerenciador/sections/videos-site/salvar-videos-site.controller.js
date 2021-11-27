(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('EditarVideosSiteController', EditarVideosSiteController);

	EditarVideosSiteController.$inject = ['$scope','API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig', '$filter', 'Upload', '$timeout'];

    function EditarVideosSiteController($scope,API, $mdDialog, $stateParams, $mdToast, $state, editorConfig, $filter, Upload, $timeout){
        var vm = this;
        vm.registro = {};
        vm.languages = [];
        vm.videos = {};
        vm.salvar = salvar;
        vm.tynconf = editorConfig;
	    vm.enviarImagem = enviarImagem;
	    vm.enviarVideo = enviarVideo;
	    vm.removerImagem = removerImagem;
	    vm.removerVideo = removerVideo;

        function getLanguages(){
            API.getLanguages()
                .then(
	                function successCallback (response) {
		                vm.languages = response.data.registros;
	                },
	                function errorCallback (response) {
		                //TODO Arrumar todos os possiveis erros
	                }
                );
        }

        function getVideosSiteInfo(id){
            API.videosSiteDetalhe(id)
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
	    if($stateParams.id) getVideosSiteInfo($stateParams.id);



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
            API.videosSiteSalvar(vm.registro)
                .then(
                    function successCallback(response) {
                        vm.requestInProgress = false;
                        if(!$stateParams.id) $state.go('painel.videos-site.listar');
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
		    upload_data.alias = 'videos-site-thumb';
		    API.mediaUpload(upload_data)
			    .success(function(response){
				    vm.registro.thumb_id = response.media_id;
				    vm.requestInProgress = false;
			    })
			    .error(function(){
				    showToast('Erro ao enviar imagem, tente novamente.');
				    vm.requestInProgress = false;
			    });
	    }

		function enviarVideo() {
			vm.requestInProgress = true;
			let upload_data = vm.video;
			upload_data.alias = 'videos-site';
			API.mediaUpload(upload_data)
				.success(function(response){
					vm.registro.video_id = response.media_id;
					vm.requestInProgress = false;
				})
				.error(function(){
					showToast('Erro ao enviar imagem, tente novamente.');
					vm.requestInProgress = false;
				});
		}

	    function removerImagem(){
		    vm.registro.thumb_id = null;
		    vm.file = null;
	    }

	    function removerVideo() {
		    vm.registro.video_id = null;
		    vm.video = null;
		    vm.registro.video = null;
	    }
    }
})();