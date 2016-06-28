(function() {
'use strict';

var mod = angular.module('gmGuilds', []);

mod.config(['$locationProvider',
    function config($locationProvider) {
        $locationProvider.hashPrefix('!');

        $locationProvider.html5Mode({
            enabled: true,
            requireBase: false
        });
    }
]);

mod.component('gmNavbar', {
    template: '<h3>gm-navbar</h3>',

    controller: ['$http',
        function NavbarController($http) {
            $http.get('/boards/view/3.json').then(function(res) {
            });
        }
    ]
});

}());

