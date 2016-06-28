(function() {
'use strict';

var mod = angular.module('gm');

mod.component('guildsIndex', {
    templateUrl: '/js/gm/template/guilds-index.html',
    controller: ['$http',
        function guildsIndexController($http) {
            var self = this;

            $http.get('/sites/view/1.json').then(function(res) {
                //console.log(res.data);
                self.site = res.data.site;
            });
        }
    ]
});

}());

