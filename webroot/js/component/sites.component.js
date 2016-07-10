(function() {
'use strict';

var mod = angular.module('gm');


mod.component('sitesIndex', {
    templateUrl: '/js/template/sites-index.html',
    controller: ['$http',
        function sitesIndexCtrl($http) {
            var self = this;

            $http.get('/api/v1/guilds').then(function(res) {
                //console.log(res);
                self.guilds = res.data.guilds;
            });

            $http.get('/api/v1/sites/1').then(function(res) {
                //console.log(res);
                self.site = res.data.site;
                self.image = self.site.images[0];
            });

            $http.get('/api/v1/news?limit=5').then(function(res) {
                //console.log(res);
                self.news = res.data.news;
            });

            $http.get('/api/v1/boards?owners=sites').then(function(res) {
                //console.log(res);
                self.board = res.data.boards[0];
                if (self.board) {
                    var url = '/api/v1/boards/' + self.board.id + '/threads';
                    $http.get(url).then(function(res) {
                        self.threads = res.data.threads;
                        console.log(self.threads);
                    });
                }
            });

            $http.get('api/v1/docs?owners=guilds').then(function(res) {
               // console.log(res.data);
                self.docs = res.data.docs;
            });
        }
    ]
});

mod.component('sitesHeader', {
    templateUrl: '/js/template/sites-header.html',
    controller: ['$http',
        function sitesHeaderCtrl($http) {
            var self = this;

            $http.get('/api/v1/users/0').then(function(res) {
                //console.log(res);
                self.authUser = res.data.user;
            });

            $http.get('/api/v1/sites/1').then(function(res) {
                //console.log(res);
                self.site = res.data.site;
            });
        }
    ]
});

mod.component('sitesFooter', {
    templateUrl: '/js/template/sites-footer.html',
    controller: ['$http',
        function sitesFooterCtrl($http) {
        }
    ]
});

}());

