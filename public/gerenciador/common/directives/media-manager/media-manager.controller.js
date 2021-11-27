(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('mediaManagerController', mediaManagerController);

    mediaManagerController.$inject = ['API', '$mdDialog', '$mdMedia', '$stateParams', '$mdToast', 'Upload', '$timeout', '$state'];

    function mediaManagerController(API, $mdDialog, $mdMedia, $stateParams, $mdToast, Upload, $timeout, $state){
        var vm = this;
        vm.medias = {};
        vm.selected = [];
        vm.media_path = '';

        //vm.getMedias = getMedias;
        vm.openModal = openModal;
        vm.closeModal = closeModal;
        vm.deletar = deletar;
        vm.selectMedia = selectMedia;

        vm.enviarImagem = enviarImagem;
  
        //Função de busca de medias via API
        function getMedias(modulo){
			API.mediasByModylo(modulo)
			.success(function (data){
			    vm.medias = data.medias.data;
                vm.media_path = data.media_path;

                console.log('media path'+ vm.media_path)
			});
        }


        getMedias(vm.modulo);


        //Open Modal Mediamanager
       	function openModal(ev) {	               
		    $mdDialog.show({
		      controller: 'mediaManagerController',
		      controllerAs: 'vm',
		      templateUrl: 'common/directives/media-manager/modal.html',
		      parent: angular.element(document.body),
		      targetEvent: ev,
		      clickOutsideToClose:true,
		      fullscreen: ($mdMedia('sm') || $mdMedia('xs')) || false
		    });

		};

		//Close modal mediamanager
		function closeModal(){
			$mdDialog.cancel();
		}


		//Deletar MEDIA
        function deletar(ev, id, index) {
            var confirm = $mdDialog.confirm()
                //.parent(angular.element(document.querySelector('#mainContent')))
                .title('Você tem certeza que deseja excluir essa imagem?')
                .textContent('A imagem irá desaparecer de todos locais que ela está vinculada atualmente e essa operação não poderá ser desfeita depois.')
                .ariaLabel()
                .targetEvent(ev)
                .ok('Excluir')
                .cancel('Cancelar');

            $mdDialog.show(confirm).then(function(){
                API.mediaDeletar(id)
                    .success(function(data){
                        showToast('media deletada com sucesso!');
                    })
                    .error(function(data){
                        showToast('Ops! Erro ao deletar');
                        console.log(data);
                    });
                openModal(ev);
            }, function() {
				console.log('O Usuário cancelou a ação');
				openModal(ev);
		    });

        };

        function selectMedia(ev, id, index){
            console.log('Media '+id+' Selecionada!');

            var media_id = id;
            vm.selected.push(media_id);


        }


        // enviarImagem - Faz o upload de imagem para o servidor
        function enviarImagem(ev, modulo, files) {

            vm.requestInProgress = true;
            
            vm.file.upload = Upload.upload({
                url: '../api/medias/upload/'+modulo,
                data: {file: files},
            });

            vm.file.upload.then(function(response) {
                vm.requestInProgress = false;
                console.log('upload com sucesso!');
                openModal(ev);
            }, function(response) {
                showToast('Erro ao enviar imagem, tente novamente.');
                vm.requestInProgress = false;
            });
        }




        
    }

})();
