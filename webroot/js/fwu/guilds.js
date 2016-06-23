(function() {
'use strict';

angular.
    module('guilds', []).

    /**
     * Site component
     *
     * サイトのタイトルと説明文
     */
    component('site', {
        template:
            '<h3>{{$ctrl.site.name}}</h3>' +
            '<p>{{$ctrl.site.description}}</p>',
        controller: function HeaderController($http) {
            var self = this;

            $http.get('/sites/view/1.json').then(function(res) {
                console.log(res.data);
                self.site = res.data.site;
            });
        }
    }).

    /**
     * GuildList Component
     *
     * ギルドへのリンクの一覧
     */
    component('guildList', {
        template:
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
        }
    });

}());

