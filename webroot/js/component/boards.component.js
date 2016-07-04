(function() {
'use strict';

var mod = angular.module('gm');

mod.component('boardsIndex', {
    templateUrl: '/js/template/boards-index.html',
    controller: ['$http',
        function BoardsIndexController($http) {
            var self = this;

            $http.get('/boards.json').then(function(res) {
                //console.log(res.data);
                self.boards = res.data.boards;
            });
        }
    ]
});

mod.component('boardsView', {
    templateUrl: '/js/template/boards-view.html',
    controller: ['$http', '$location',
        function BoardsViewController($http, $location) {
            var self = this;
            var path = $location.$$path + '.json';
            //console.log(path);

            $http.get(path).then(function(res) {
                self.user = res.data.user;
                self.board = res.data.board;
                self.threads = res.data.threads;
                self.postName = res.data.postName;
                self.csrf = res.data.csrf;
            });
        }
    ]
});

}());

