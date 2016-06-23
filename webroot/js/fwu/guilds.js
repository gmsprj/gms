(function() {
'use strict';

var mod = angular.module('guilds', []);

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
                '<a href="/guilds/view/{{el.id}}">{{el.name}}</a>' + 
            '</li>' +
        '</ul>',
    controller: function GuildListController($http) {
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
});

/**
 * GuildsView component
 *
 * /guilds/view/id のコンポーネント
 */
mod.component('guildsView', {
    template:
        '<h3>guild.name: {{ $ctrl.guild.name }}</h3>' +
        '<p>guild.desc: {{ $ctrl.guild.description }}</p>' +
        '<hr/>' +
        '<h4>入会受付</h4>' +
        '<p>入会には<a href="/users/signin">サインイン</a>が必要です。</p>' + 
        '<hr/>' +
        '<h4>ギルドのスレッド一覧</h4>' +
        '<ul>' +
            '<li>' +
                '<a href="/guilds/view/"></a>' +
            '</li>' +
        '</ul>',

    controller: function GuildViewController($http) {
        var self = this;

        $http.get('/guilds/view/1.json').then(function(res) {
            console.log(res.data);
            self.guilds = res.data.guilds;
        });
    }
});

}());

