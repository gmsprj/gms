if (typeof gm === 'undefined') {
    var gm = {};
}

(function() {
'use strict';

gm.ThreadsIndexCtrl = function($http) {
    var self = this;
    var q = '';

    q = '/api/v1/threads';
    $http.get(q).then(function(res) {
        //console.log(res.data);
        self.threads = res.data.threads;
    });
};

gm.ThreadsViewCtrl = function($http, $location) {
    var self = this;
    var id = $location.$$path.substr($location.$$path.lastIndexOf('/') + 1);
    var q = '';

    q = '/api/v1/users/0';
    $http.get(q).then(function(res) {
        //console.log(res.data);
        self.authUser = res.data.user;
        self.csrf = res.data.csrf;
        self.postName = res.data.postName;

        q = '/api/v1/threads/' + id;
        $http.get(q).then(function(res) {
            //console.log(res.data);
            self.thread = res.data.thread;

            q = '/api/v1/guilds/' + self.thread.guild_id;
            $http.get(q).then(function(res) {
                //console.log(res.data);
                self.guild = res.data.guild;

                q = '/api/v1/threads?owners=guilds&ownerId=' + self.guild.id;
                $http.get(q).then(function(res) {
                    //console.log(res.data);
                    self.threads = res.data.threads;
                });
            });

            q = '/api/v1/posts?owners=threads&ownerId=' + self.thread.id;
            $http.get(q).then(function(res) {
                self.posts = res.data.posts; 
            });
        });
    });
};

var mod = angular.module('gm');

mod.component('gmThreadsIndex', {
    templateUrl: '/js/template/threads/index.html',
    controller: ['$http', gm.ThreadsIndexCtrl]
});

mod.component('gmThreadsView', {
    templateUrl: '/js/template/threads/view.html',
    controller: ['$http', '$location', gm.ThreadsViewCtrl]
});

}());

