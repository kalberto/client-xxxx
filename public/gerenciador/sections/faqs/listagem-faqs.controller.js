(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('listagemFaqsController', listagemFaqsController);

    listagemFaqsController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig'];

    function listagemFaqsController(API, $mdDialog, $stateParams, $mdToast, $state, editorConfig){
        var vm = this;

        vm.cadastrarFaq = cadastrarFaq;
        vm.carregarFaqs = carregarFaqs;
        vm.buscarFaqs = buscarFaqs;
        vm.resetFaqs = resetFaqs;
        vm.deletar = deletar;
        vm.selected = [];
        vm.query = {
            order: 'pergunta',
            limit: 50,
            page: 1
        };

        if($stateParams.url && $stateParams.nome){
            var nome = $stateParams.nome;
            var url = $stateParams.url;
            carregarFaqs(url);
        }else{
            $state.go('painel.servicos.listar');
        }

        function buscarFaqs()
        {   
            resetFaqs();
            vm.promise = API.faqsBuscar(vm.search)
                .then(
                    function successCallback (response) {
                        vm.faqs = response.data;
                    },
                    function errorCallback (response) {
                        console.log('ERRO');
                    }
                );
        }


        //Cadastro
        function cadastrarFaq(){
            $state.go('painel.faqs.salvar',{nome:nome, url:url});
        }
        
        // Reseta o array de usuarios
        function resetFaqs(){
            vm.faqs = [];
        }

        // Listagem
        function carregarFaqs(url,reset){
           
            reset && resetFaqs();

            vm.promise = API.faqsServico(vm.query,url)
                .then(
                    function successCallback (response) {
                        vm.faqs = response.data;
                    },
                    function errorCallback (response) {
                        console.log('ERRO');
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

        //Deletar
        function deletar(ev, id, index) {

            var confirm = $mdDialog.confirm()
                .parent(angular.element(document.querySelector('#mainContent')))
                .title('Você tem certeza que deseja excluir esse FAQ?')
                .textContent('Todos as informações sobre esse FAQ serão removidas e não será possível recuperar.')
                .ariaLabel()
                .targetEvent(ev)
                .ok('Excluir')
                .cancel('Cancelar');

            $mdDialog.show(confirm).then(function(){
                API.faqsDeletar(id)
                    .then(
                        function successCallback (response) {
                            vm.faqs.data.splice(index, 1);
                            showToast('FAQ deletada com sucesso!');
                        },
                        function errorCallback (response) {
                            showToast('Ops! Erro ao deletar');
                            console.log(response);
                        }
                    );
            });
        }
    }
})();
