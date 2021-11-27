(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('DashboardController', DashboardController);

    DashboardController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig','$cookies'];

    function DashboardController(API, $mdDialog, $stateParams, $mdToast, $state, editorConfig,$cookies){
        var vm = this;
        vm.dashboard = {};

        function getDashboardInfo(){
            var token = $cookies.get('api_token');
	        if(token !== undefined && token !== null && token !== '')
		        token = localStorage.getItem('api_token');
            if(token !== undefined && token !== null && token !== '') {
                API.dashboardDetalhe()
                    .then(
                        function successCallback(response) {
                            vm.dashboard = response.data;
                        },
                        function errorCallback(response) {
                            //TODO analisar os possiveis erros e desenvolver soluções
                        });
            }
        }
       	getDashboardInfo();

   
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
