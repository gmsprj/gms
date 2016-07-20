if (typeof gm === 'undefined') {
    var gm = {};
}

(function() {
'use strict';

var mod = angular.module('gm');

mod.component('guildsIndex', {
    templateUrl: '/js/template/guilds/index.html',
    controller: ['$http',
        function GuildsIndexCtrl($http) {
            var self = this;

            $http.get('/api/v1/guilds').then(function(res) {
                //console.log(res);
                self.guilds = res.data.guilds;
            });

            $http.get('/api/v1/sites/1').then(function(res) {
                //console.log(res);
                self.site = res.data.site;
                self.image = self.site.images[0];
            });

            $http.get('/api/v1/news?limit=5').then(function(res) {
                //console.log(res);
                self.news = res.data.news;
            });

            $http.get('/api/v1/boards?owners=sites').then(function(res) {
                //console.log(res);
                self.board = res.data.boards[0];
                if (self.board) {
                    var url = '/api/v1/boards/' + self.board.id + '/threads';
                    $http.get(url).then(function(res) {
                        self.threads = res.data.threads;
                        console.log(self.threads);
                    });
                }
            });

            $http.get('api/v1/docs?owners=guilds').then(function(res) {
               // console.log(res.data);
                self.docs = res.data.docs;
            });
        }
    ]
});

mod.component('guildsView', {
    templateUrl: '/js/template/guilds/view.html',
    controller: ['$http', '$location',
        function GuildsViewCtrl($http, $location) {
            var self = this;
            var id = $location.$$path.substr($location.$$path.lastIndexOf('/') + 1);
            var q = '';

            $http.get('/api/v1/users/0').then(function(res) {
                //console.log(res.data);
                self.authUser = res.data.user;
                self.csrf = res.data.csrf;
                
                if (self.authUser) {
                    self.wasEntry = false;
                    for (var i = 0, len = self.authUser.guilds.length; i < len; ++i) {
                        var el = self.authUser.guilds[i];
                        if (el.id == id) {
                            self.wasEntry = true;
                            break;
                        }
                    }
                }
                
                q = '/api/v1/guilds/' + id;
                $http.get(q).then(function(res) {
                    //console.log(res.data);
                    self.guild = res.data.guild;
                    self.symbol = self.guild.images[0];
                });

                q = '/api/v1/docs?owners=guilds&ownerId=' + id + '&state=published';
                $http.get(q).then(function(res) {
                    //console.log(res.data);
                    self.publishedDocs = res.data.docs;
                });

                q = '/api/v1/docs?owners=guilds&ownerId=' + id + '&state=draft';
                $http.get(q).then(function(res) {
                    //console.log(res.data);
                    self.draftDocs = res.data.docs;
                });

                q = '/api/v1/boards?owners=guilds&ownerId=' + id;
                $http.get(q).then(function(res) {
                    //console.log(res.data);
                    self.boards = res.data.boards;

                    // Headline of thread
                    q = '/api/v1/threads?owners=boards&ownerId=' + self.boards[0].id + '&limit=1';
                    $http.get(q).then(function(res) {
                        self.thread = (res.data.threads ? res.data.threads[0] : null);
                        if (self.thread) {
                            q = '/api/v1/posts?owners=threads&ownerId=' + self.thread.id + '&limit=5';
                            $http.get(q).then(function(res) {
                                self.posts = res.data.posts;
                            });
                        }
                    });
                });

                q = '/api/v1/news?owners=guilds&ownerId=' + id;
                $http.get(q).then(function(res) {
                    //console.log(res.data);
                    self.news = res.data.news;
                });
            });
        }
    ]
})

/**
 * gm.GuildsListCtrl
 *
 */
gm.GuildsListCtrl = function($http) {
    var self = this;

    $http.get('/guilds').then(function(res) {
        self.guilds = res.data.guilds;
    });
};

mod.component('gmGuildsList', {
    templateUrl: '/js/template/guilds/list.html',
    controller: ['$http',  gm.GuildsListCtrl],
});

}());

