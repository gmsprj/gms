(function() {
'use strict';

var mod = angular.module('guilds', []);

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
 * /guilds/ のコンポーネント
 */
mod.component('guilds', {
    template:
        '<h3>{{ $ctrl.site.name }}</h3>' +
        '<p>{{ $ctrl.site.description }}</p>' +
        '<hr/>' +
        '<h3><a href="/guilds">ギルド一覧</a></h3>' + 
        '<ul>' +
            '<li ng-repeat="el in $ctrl.guilds">' +
                '<a target="_self" href="/guilds/view/{{ el.id }}">{{ el.name }}</a>' + 
            '</li>' +
        '</ul>' +
        '<hr/>' +
        '<h3><a href="/boards/view/{{ $ctrl.guestBoard.id }}">{{ $ctrl.guestBoard.name }}</a></h3>' +
        '<a href="/threads/view/{{ $ctrl.guestThread.id }}">{{ $ctrl.guestThread.name }}</a>' +
        '',

    controller: ['$http',
        function GuildListController($http) {
            //console.log($routeParams);
            var self = this;

            $http.get('/guilds.json').then(function(res) {
                //console.log(res.data);
                self.guilds = res.data.guilds;
            });

            /* パラメータによる絞り込みが必要 */
            $http.get('/sites/view/1.json').then(function(res) {
                //console.log(res.data);
                self.site = res.data.site;
            });

            /**
             * TODO: パラメータによる絞り込みが必要
             *
             * boards/?name="ロビー"&parent_name="null"
             * threads/?board_id=xxx
             */
            $http.get('/threads/view/3.json').then(function(res) {
                //console.log(res.data);
                self.guestThread = res.data.thread;
                self.guestBoard = res.data.board;
            });
        }
    ]
});

/**
 * GuildsView component
 *
 * /guilds/view/id のコンポーネント
 */
mod.component('guildsView', {
    template:
        '<h3>{{ $ctrl.guild.name }}</h3>' +
        '<p>{{ $ctrl.guild.description }}</p>' +
        '<hr/>' +
        '<h4>入会受付</h4>' +
        '<p>入会には<a target="_self" href="/users/signin">サインイン</a>が必要です。</p>' + 
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
            var getUrl = '/guilds/view/' + id + '.json';
            //console.log(getUrl);

            $http.get(getUrl).then(function(res) {
                //console.log(res.data);
                self.guild = res.data.guild;
                self.board = res.data.board;
                self.threads = res.data.threads;
            });
        }
    ]
});

}());

