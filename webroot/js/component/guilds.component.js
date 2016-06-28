(function() {
'use strict';

var mod = angular.module('gm');

mod.component('guildsIndex', {
    templateUrl: '/js/template/guilds-index.html',
    controller: ['$http',
        function GuildsIndexCtrl($http) {
            var self = this;

            $http.get('/sites/view/1.json').then(function(res) {
                //console.log(res);
                self.site = res.data.site;
            });

            $http.get('/guilds.json').then(function(res) {
                //console.log(res);
                self.guilds = res.data.guilds;
            });
        }
    ]
});

}());

