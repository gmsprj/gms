(function() {
'use strict';

var mod = angular.module('gm', []);

mod.config(['$locationProvider',
    function config($locationProvider) {
        $locationProvider.hashPrefix('!');

        $locationProvider.html5Mode({
            enabled: true,
            requireBase: false
        });
    }
]);

}());

