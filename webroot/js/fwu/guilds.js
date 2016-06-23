(function() {
'use strict';

var mod = angular.module('guilds', [
    'ngRoute',
]);

mod.config(['$locationProvider', '$routeProvider',
    function config($locationProvider, $routeProvider) {
      $locationProvider.hashPrefix('!');

      //$locationProvider.html5Mode(true); // $location.search()
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
        '<h3>{{$ctrl.site.name}}</h3>' +
        '<p>{{$ctrl.site.description}}</p>' +
        '<hr/>' +
        '<h3>ギルド一覧</h3>' + 
        '<ul>' +
            '<li ng-repeat="el in $ctrl.guilds">' +
                '<a target="_self" href="/guilds/view/{{el.id}}">{{el.name}}</a>' + 
            '</li>' +
        '</ul>',

    controller: ['$http',
        function GuildListController($http) {
            //console.log($routeParams);
            var self = this;

            $http.get('/guilds.json').then(function(res) {
                //console.log(res.data);
                self.guilds = res.data.guilds;
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
 * /guilds/view/id のコンポーネント
 */
mod.component('guildsView', {
    template:
        '<h3>{{ $ctrl.guild.name }}</h3>' +
        '<p>{{ $ctrl.guild.description }}</p>' +
        '<hr/>' +
        '<h4>入会受付</h4>' +
        '<p>入会には<a href="/users/signin">サインイン</a>が必要です。</p>' + 
        '<hr/>' +
        '<h4>ギルドのスレッド一覧</h4>' +
        '<ul>' +
            '<li ng-repeat="el in $ctrl.threads">' +
                '<a target="_self" href="/threads/view/{{el.id}}">{{el.name}}</a>' + 
            '</li>' +
        '</ul>',

    controller: ['$http', '$location',
        function GuildViewController($http, $location) {
            var self = this;

            // URL からギルド ID を取得して GET 先のパスを作成
            var path = $location.$$path;
            var guildId = path.substr(path.lastIndexOf('/') + 1);
            var getUrl = '/guilds/view/' + guildId + '.json';
            //console.log(getUrl);

            $http.get(getUrl).then(function(res) {
                //console.log(res.data);
                self.guild = res.data.guild;
                self.threads = res.data.threads;
                self.board = res.data.board;
            });
        }
    ]
});

}());

