(function() {
'use strict';

var mod = angular.module('gm', []);

mod.config(['$locationProvider', '$httpProvider',
    function config($locationProvider, $httpProvider) {
        $locationProvider.hashPrefix('!');
        $locationProvider.html5Mode({
            enabled: true,
            requireBase: false
        });

        $httpProvider.defaults.headers.get = {
            'Accept': 'application/json'
        };
        $httpProvider.defaults.headers.post = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        };

        /*
        $httpProvider.defaults.xsrfCookieName = 'csrftoken';
        $httpProvider.defaults.xsrfHeaderName = 'X-CSRF-Token';
        $httpProvider.defaults.headers.post['X-CSRF-Token'] = 'MY-CSRF-TOKEN';
        */
    }
]);

}());

