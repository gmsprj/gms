(function() {
'use strict';

angular.module('gm').
    component('gmGuildsHeader', {
        template:
            '<h3>{{ $ctrl.site.name }}</h3>' +
            '<p>{{ $ctrl.site.description }}</p>',

        controller: ['$http',
            function gmGuildsHeaderController($http) {
                var self = this;

                $http.get('/sites/view/1.json').then(function(res) {
                    //console.log(res.data);
                    self.site = res.data.site;
                });
            }
        ]
    });

angular.module('gm').
    component('gmGuildsList', {
        template:
            '<ul>' +
                '<li ng-repeat="el in $ctrl.guilds">' +
                    '<a target="_self" href="/guilds/view/{{ el.id }}">{{ el.name }}</a>' + 
                '</li>' +
            '</ul>' +
            '',

        controller: ['$http',
            function GmGuildsListController($http) {
                //console.log($routeParams);
                var self = this;

                $http.get('/guilds.json').then(function(res) {
                    //console.log(res.data);
                    self.guilds = res.data.guilds;
                });

                /**
                 * TODO: パラメータによる絞り込みが必要
                 *
                 * boards/?name="ロビー"&parent_name="null"
                 * threads/?board_id=xxx
                 */
                $http.get('/boards/view/3.json').then(function(res) {
                    //console.log(res.data);
                    self.guestBoard = res.data.board;
                    self.guestThreads = res.data.threads;
                });
            }
        ]
    });

/**
 * GuildsView component
 *
 * /guilds/view/id のコンポーネント
 */
angular.module('gm').
    component('gmGuildsView', {
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
            function GmGuildsViewController($http, $location) {
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

