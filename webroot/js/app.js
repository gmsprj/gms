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
            'Accept' : 'application/json'
        };
    }
]);

}());

