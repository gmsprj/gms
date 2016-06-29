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

mod.component('guildsView', {
    templateUrl: '/js/template/guilds-view.html',
    controller: ['$http', '$location',
        function GuildsViewCtrl($http, $location) {
            var self = this;
            var path = $location.$$path + '.json';
            //console.log(path);

            $http.get(path).then(function(res) {
                //console.log(res);
                self.guild = res.data.guild;
                self.board = res.data.board;
                self.threads = res.data.threads;
            });
        }
    ]
})

}());

