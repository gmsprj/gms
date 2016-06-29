(function() {
'use strict';

var mod = angular.module('gm');

mod.component('threadsIndex', {
    templateUrl: '/js/template/threads-index.html',
    controller: ['$http',
        function ThreadsIndexController($http) {
            var self = this;

            $http.get('/threads.json').then(function(res) {
                //console.log(res.data);
                self.threads = res.data.threads;
            });
        }
    ]
});

mod.component('threadsView', {
    templateUrl: '/js/gm/template/threads-view.html',
    controller: ['$http', '$location',
        function ThreadsViewController($http, $location) {
            var self = this;
            var path = $location.$$path + '.json';
            //console.log(path);

            $http.get(path).then(function(res) {
                //console.log(res.data);
                self.postName = res.data.postName;
                self.board = res.data.board;
                self.thread = res.data.thread;
                self.posts = res.data.posts;
                self.csrf = res.data.csrf;
            });
        }
    ]
});

}());

