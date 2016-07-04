(function() {
'use strict';

var mod = angular.module('gm');

mod.component('guildsIndex', {
    templateUrl: '/js/template/guilds-index.html',
    controller: ['$http',
        function GuildsIndexCtrl($http) {
            var self = this;

            $http.get('/guilds.json').then(function(res) {
                //console.log(res);
                self.guilds = res.data.guilds;
                self.news = res.data.news;
                self.symbol = res.data.symbol;
                self.customDocs = res.data.customDocs;
                self.site = res.data.site;
                self.threads = res.data.threads;
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
                self.user = res.data.user;
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

