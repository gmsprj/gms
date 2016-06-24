(function() {
'use strict';

var mod = angular.module('boards', []);

mod.config(['$locationProvider',
    function config($locationProvider) {
        $locationProvider.hashPrefix('!');

        $locationProvider.html5Mode({
            enabled: true,
            requireBase: false
        });
    }
]);

/**
 * Guilds Component
 *
 * /boards/ のコンポーネント
 */
mod.component('boards', {
    template:
        '<h3>{{ $ctrl.site.name }}</h3>' +
        '<p>{{ $ctrl.site.description }}</p>' +
        '<hr/>' +
        '<h3>ギルド一覧</h3>' + 
        '<ul>' +
            '<li ng-repeat="el in $ctrl.boards">' +
                '<a target="_self" href="/boards/view/{{ el.id }}">{{ el.name }}</a>' + 
            '</li>' +
        '</ul>',

    controller: ['$http',
        function GuildListController($http) {
            //console.log($routeParams);
            var self = this;

            $http.get('/boards.json').then(function(res) {
                //console.log(res.data);
                self.boards = res.data.boards;
            });

            $http.get('/sites/view/1.json').then(function(res) {
                //console.log(res.data);
                self.site = res.data.site;
            });
        }
    ]
});

/**
 * GuildsView component
 *
 * /boards/view/id のコンポーネント
 */
mod.component('boardsView', {
    template:
        '<h3>{{ $ctrl.board.name }}</h3>' +
        '<p>{{ $ctrl.board.description }}</p>' +
        '<hr/>' +
        '<h4>スレッド一覧(<a target="_self" href="/boards/view/{{ $ctrl.board.id }}">{{ $ctrl.board.name }}</a>)</h4>' +
        '<ul>' +
            '<li ng-repeat="el in $ctrl.threads">' +
                '<a target="_self" href="/threads/view/{{ el.id }}">{{ el.name }}</a>' + 
            '</li>' +
        '</ul>',

    controller: ['$http', '$location',
        function GuildViewController($http, $location) {
            var self = this;

            // URL からギルド ID を取得して GET 先のパスを作成
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

