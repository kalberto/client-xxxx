(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .directive('mdMediaManager', function () {
		    return {
		        restrict: 'E',
		        replace: true,
		        transclude: true,
				controller: 'mediaManagerController',
				controllerAs: 'vm',
				bindToController: true,// because the scope is isolated
		        scope: { modulo: '@',tipo: '@', 'subgrupo': '@', 'maxfile': '@' },
		        templateUrl: 'common/directives/media-manager/template.html',
		        link: function (scope, element, attrs) {
		            // Manipulação e Eventos DOM aqui!
		        }
		    };
		});
})();