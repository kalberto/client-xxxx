(function () {
    'use strict';
    angular.module('app').service('API', ['$http', function ($http) {
        function getUrl(path) {
            return base_url+'/'+path;
        }

        this.get = function (path) {
            let url = getUrl(path);
            return $http.get(url);
        };
        this.post = function (path,data) {
            return $http.post(getUrl(path),data);
        };
    }]);
})();