(function() {
'use strict';

var mod = angular.module('gm');

mod.component('guildsIndex', {
    templateUrl: '/js/template/guilds-index.html',
    controller: ['$http',
        function GuildsIndexCtrl($http) {
            var self = this;

            $http.get('/sites/view/1.json').then(function(res) {
                //console.log(res);
                self.site = res.data.site;
            });

            $http.get('/guilds.json').then(function(res) {
                //console.log(res);
                self.guilds = res.data.guilds;
                self.news = res.data.news;
                self.symbol = res.data.symbol;
                console.log(res.data.symbol);
            });

            $http.get('/threads.json').then(function(res) {
                //console.log(res);
                self.guestThreads = res.data.threads;
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
                console.log(res.data.guildSymbols);
                self.guild = res.data.guild;
                self.guildSymbolUrl = res.data.guildSymbols[0].url;
                self.board = res.data.board;
                self.threads = res.data.threads;
                self.pubDocs = res.data.pubDocs;
            });
        }
    ]
})

}());

