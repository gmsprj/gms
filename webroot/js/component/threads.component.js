(function() {
'use strict';

var mod = angular.module('gm');

mod.component('threadsIndex', {
    templateUrl: '/js/template/threads-index.html',
    controller: ['$http',
        function ThreadsIndexController($http) {
            var self = this;
            var q = '';

            q = '/api/v1/threads';
            $http.get(q).then(function(res) {
                //console.log(res.data);
                self.threads = res.data.threads;
            });
        }
    ]
});

mod.component('threadsView', {
    templateUrl: '/js/template/threads-view.html',
    controller: ['$http', '$location',
        function ThreadsViewController($http, $location) {
            var self = this;
            var id = $location.$$path.substr($location.$$path.lastIndexOf('/') + 1);
            var q = '';

            q = '/api/v1/users/0';
            $http.get(q).then(function(res) {
                //console.log(res.data);
                self.authUser = res.data.user;
                self.csrf = res.data.csrf;
                self.postName = res.data.postName;

                q = '/api/v1/threads/' + id;
                $http.get(q).then(function(res) {
                    //console.log(res.data);
                    self.thread = res.data.thread;

                    q = '/api/v1/boards/' + self.thread.board_id;
                    $http.get(q).then(function(res) {
                        //console.log(res.data);
                        self.board = res.data.board;

                        self.isPostable = false;
                        if (self.authUser || self.board.owners === 'sites') {
                            self.isPostable = true;
                        }

                        q = '/api/v1/threads?owners=boards&ownerId=' + self.board.id;
                        $http.get(q).then(function(res) {
                            //console.log(res.data);
                            self.threads = res.data.threads;
                        });
                    });

                    q = '/api/v1/posts?owners=threads&ownerId=' + self.thread.id;
                    $http.get(q).then(function(res) {
                        self.posts = res.data.posts; 
                    });
                });
            });
        }
    ]
});

}());

