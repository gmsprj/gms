(function() {
'use strict';

var mod = angular.module('gm');

mod.component('guildsIndex', {
    templateUrl: '/js/template/guilds-index.html',
    controller: ['$http',
        function GuildsIndexCtrl($http) {
            var self = this;

            $http.get('/guilds').then(function(res) {
                //console.log(res);
                self.guilds = res.data.guilds;
            });

            $http.get('/sites/1').then(function(res) {
                //console.log(res);
                self.site = res.data.site;
            });
        }
    ]
});

mod.component('guildsView', {
    templateUrl: '/js/template/guilds-view.html',
    controller: ['$http', '$location',
        function GuildsViewCtrl($http, $location) {
            var self = this;
            var path = $location.$$path + '.json';
            //console.log(path);

            $http.get(path).then(function(res) {
                self.authUser = res.data.authUser;
                self.guild = res.data.guild;
                self.guildSymbolUrl = res.data.guildSymbols[0].url;
                self.boards = res.data.boards;
                self.publishedDocs = res.data.publishedDocs;
                self.draftDocs = res.data.draftDocs;
                self.news = res.data.news;
                self.headlineThread = res.data.headlineThread;
                self.headlinePosts = res.data.headlinePosts;
                self.wasEntry = res.data.wasEntry;
                self.csrf = res.data.csrf;
            });
        }
    ]
})

}());

