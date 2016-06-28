(function() {
'use strict';

/**
 * Boards Component
 *
 * /boards/ のコンポーネント
 */
angular.module('gm').
    component('gmBoardsList', {
        template:
            '<h3>板一覧</h3>' + 
            '<ul>' +
                '<li ng-repeat="el in $ctrl.boards">' +
                    '<a target="_self" href="/boards/view/{{ el.id }}">{{ el.name }}</a>' + 
                '</li>' +
            '</ul>',

        controller: ['$http',
            function GmBoardsListController($http) {
                //console.log($routeParams);
                var self = this;

                $http.get('/boards.json').then(function(res) {
                    console.log(res.data);
                    self.boards = res.data.boards;
                });
            }
        ]
    });

/**
 * BoardsView component
 *
 * /boards/view/id のコンポーネント
 */
angular.module('gm').
    component('gmBoardsView', {
        template:
            '<h3>{{ $ctrl.board.name }}</h3>' +
            '<p>{{ $ctrl.board.description }}</p>' +
            '<hr/>' +
            '<h4>スレッド一覧(<a target="_self" href="/boards/view/{{ $ctrl.board.id }}">{{ $ctrl.board.name }}</a>)</h4>' +
            '<ul>' +
                '<li ng-repeat="el in $ctrl.threads">' +
                    '<a target="_self" href="/threads/view/{{ el.id }}">{{ el.name }}</a>' + 
                '</li>' +
            '</ul>' +
            '<hr/>',

        controller: ['$http', '$location',
            function GmBoardsViewController($http, $location) {
                var self = this;

                // URL から ID を取得して GET 先のパスを作成
                var path = $location.$$path;
                var id= path.substr(path.lastIndexOf('/') + 1);
                var getUrl = '/boards/view/' + id + '.json';
                //console.log(getUrl);

                $http.get(getUrl).then(function(res) {
                    //console.log(res.data);
                    self.board = res.data.board;
                    self.threads = res.data.threads;
                });
            }
        ]
    });

}());

