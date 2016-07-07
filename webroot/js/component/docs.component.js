(function() {
'use strict';

var mod = angular.module('gm');

mod.component('docsIndex', {
    templateUrl: '/js/template/docs-index.html',
    controller: ['$http',
        function DocsIndexCtrl($http) {
            var self = this;
            var q = '';

            q = '/api/v1/users/0';
            $http.get(q).then(function(res) {
                self.authUser = res.data.user;
                self.csrf = res.data.csrf;
                self.isPostable = self.authUser && self.authUser.guilds.length;

                q = '/api/v1/docs?owners=guilds';
                $http.get(q).then(function(res) {
                    //console.log(res.data);
                    self.docs = res.data.docs;
                });
            });
        }
    ]
});

mod.component('docsView', {
    templateUrl: '/js/template/docs-view.html',
    controller: ['$http', '$location',
        function DocsViewCtrl($http, $location) {
            var self = this;
            var id = $location.$$path.substr($location.$$path.lastIndexOf('/') + 1);
            var q = '';

            q = '/api/v1/users/0';
            $http.get(q).then(function(res) {
                //console.log(res.data);
                self.authUser = res.data.user;
                self.csrf = res.data.csrf;

                q = '/api/v1/docs/' + id;
                $http.get(q).then(function(res) {
                    //console.log(res.data);
                    self.doc = res.data.doc;

                    q = '/api/v1/threads?refs=docs&refId=' + self.doc.id;
                    $http.get(q).then(function(res) {
                        //console.log(res.data);
                        self.threads = res.data.threads;

                        for (var i = 0, len = self.threads.length; i < len; ++i) {
                            (function() {
                                var _thread = self.threads[i];
                                return function() {
                                    //console.log(_thread);
                                    $http.get('/api/v1/posts?owners=threads&ownerId=' + _thread.id).then(function(res) {
                                        //console.log(res.data);
                                        _thread.posts = res.data.posts;
                                    });
                                };
                            }())();
                        }
                    });
                });
            });
        }
    ]
})

mod.component('docsEdit', {
    templateUrl: '/js/template/docs-edit.html',
    controller: ['$http', '$location',
        function GuildsViewCtrl($http, $location) {
            var self = this;
            var id = $location.$$path.substr($location.$$path.lastIndexOf('/') + 1);
            var q = '';

            q = '/api/v1/users/0';
            $http.get(q).then(function(res) {
                //console.log(res.data);
                self.authUser = res.data.user;
                if (self.authUser == null) {
                    return;
                }
                self.csrf = res.data.csrf;

                q = '/api/v1/docs/' + id;
                $http.get(q).then(function(res) {
                    //console.log(res.data);
                    self.doc = res.data.doc;

                    for (var i = 0, len = self.authUser.guilds.length; i < len; ++i) {
                        var el = self.authUser.guilds[i];
                        if (el.id == self.doc.ownerId) {
                            self.selectedGuildIndex = i;
                            //console.log(el);
                            break;
                        }
                    }
                });

                q = '/api/v1/threads?refs=docs&refId=' + id;
                $http.get(q).then(function(res) {
                    //console.log(res.data);
                    if (res.data.threads && res.data.threads.length > 0) {
                        self.thread = res.data.threads[0];
                    }
                });
            });
        }
    ]
})
}());

