(function() {
'use strict';

var mod = angular.module('gm');

mod.component('boardsIndex', {
    templateUrl: '/js/template/boards/index.html',
    controller: ['$http',
        function BoardsIndexController($http) {
            var self = this;
            var q = '';

            q = '/api/v1/users/0';
            $http.get(q).then(function(res) {
                self.authUser = res.data.user;
                self.postName = res.data.postName;

                q = '/api/v1/boards';
                $http.get(q).then(function(res) {
                    //console.log(res.data);
                    self.boards = res.data.boards;
                });
            });
        }
    ]
});

mod.component('boardsView', {
    templateUrl: '/js/template/boards/view.html',
    controller: ['$http', '$location',
        function BoardsViewController($http, $location) {
            var self = this;
            var id = $location.$$path.substr($location.$$path.lastIndexOf('/') + 1);
            var q = '';

            q = '/api/v1/users/0';
            $http.get(q).then(function(res) {
                //console.log(res.data);
                self.authUser = res.data.user;
                self.csrf = res.data.csrf;
                self.postName = res.data.postName;

                q = '/api/v1/boards/' + id;
                $http.get(q).then(function(res) {
                    self.board = res.data.board;

                    self.isPostable = false;
                    if (self.authUser || self.board.owners === 'sites') {
                        self.isPostable = true;
                    }

                    q = '/api/v1/threads?owners=boards&ownerId=' + id;
                    $http.get(q).then(function(res) {
                        self.threads = res.data.threads;
                    });
                });
            });
        }
    ]
});

}());

