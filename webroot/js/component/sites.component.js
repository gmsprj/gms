(function() {
'use strict';

var mod = angular.module('gm');

mod.component('sitesIndex', {
    templateUrl: '/js/template/sites/index.html',
    controller: ['$http',
        function sitesIndexCtrl($http) {
            var self = this;

            /**
             * Post method for ajax from trigger of html button
             */
            self.post = function() {
                if (self.postName == null || self.content == null) {
                    alert('Invalid input data');
                    return;
                }

                var data = {
                    name: 'my name',//self.postName,
                    content: 'content',//self.content,
                    userId: 1,//(self.authUser ? self.authUser.id : 1),
                    threadId: 1,//self.threadId,
                    data: {abe:'maria'},
                };
                var conf = {
                    headers: {
                        'X-CSRF-Token': self.csrf,
                    },
                };
                var url = '/api/v1/posts';
                $http.post(url, data, conf).then(function(res) {
                    alert('success!');
                    console.log(res); 
                }, function(res) {
                    console.error(self);
                    console.error(res);
                });
            };

            $http.get('/api/v1/users/0').then(function(res) {
                //console.log(res);
                self.authUser = res.data.user;
                self.csrf = res.data.csrf;
                self.postName = res.data.postName;
            });

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

            // Boards
            $http.get('/api/v1/boards?owners=sites').then(function(res) {
                //console.log(res);
                self.board = res.data.boards[0];

                // Threads
                if (self.board) {
                    var url = '/api/v1/boards/' + self.board.id + '/threads';
                    $http.get(url).then(function(res) {
                        self.threads = res.data.threads;
                        self.thread = res.data.threads[0];
                        //console.log(self.threads);

                        // Posts
                        var url = '/api/v1/posts?owners=threads&ownerId=' + self.thread.id;
                        $http.get(url).then(function(res) {
                            self.posts = res.data.posts;
                            //console.log(self.posts);
                        });
                    });
                }
            });

            $http.get('api/v1/docs?owners=guilds').then(function(res) {
               // console.log(res.data);
                self.docs = res.data.docs;
            });

            // Events
            self.msEvent = 2000;
            self.eventIntervalId = setInterval(function() {
                console.log(self.msEvent);
            }, self.msEvent);
        }
    ]
});

mod.component('sitesHeader', {
    templateUrl: '/js/template/sites/header.html',
    controller: ['$http',
        function sitesHeaderCtrl($http) {
            var self = this;

            $http.get('/api/v1/users/0').then(function(res) {
                //console.log(res);
                self.authUser = res.data.user;
            });

            $http.get('/api/v1/sites/1').then(function(res) {
                //console.log(res);
                self.site = res.data.site;
            });
        }
    ]
});

mod.component('sitesFooter', {
    templateUrl: '/js/template/sites/footer.html',
    controller: ['$http',
        function sitesFooterCtrl($http) {
        }
    ]
});

}());

