(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .config(configureInterceptor);

    function configureInterceptor($httpProvider){

        var interceptor = ['$q', '$injector', function($q, $injector){
            return {
                'responseError': function (response){
                    if(response.status === 401){
                        $injector.get('$state').transitionTo('auth-login');
                    }
                    return $q.reject(response);
                }
            };
        }];

        $httpProvider.interceptors.push(interceptor);
        $httpProvider.defaults.withCredentials = true;

    }

})();
