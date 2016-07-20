if (typeof gm === 'undefined') {
    var gm = {};
}

(function() {
'use strict';

gm.SitesIndexCtrl = function($http) {
    var self = this;

    /**
     * Post method for ajax from trigger of html button
     */
    self.post = function() {
        self.threadId = self.thread.id; // TODO: Where is thread?

        if (self.postName == null || self.content == null || self.threadId == null) {
            alert('Invalid input data');
            return;
        }

        var data = {
            name: self.postName,
            content: self.content,
            userId: (self.authUser ? self.authUser.id : 1),
            threadId: self.threadId,
        };
        var conf = {
            headers: {
                'X-CSRF-Token': self.csrf,
            },
        };
        var url = '/api/v1/posts';

        $http.post(url, data, conf).then(function(res) {
            //console.log(res.data); 
            self.posts.unshift(res.data.post);
        }, function(res) {
            alert('Failed');
            //console.error(res);
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
    $http.get('/api/v1/guilds?owners=sites').then(function(res) {
        //console.log(res);
        self.guild = res.data.guilds[0];

        // Threads
        if (self.guild) {
            var url = '/api/v1/guilds/' + self.guild.id + '/threads';
            $http.get(url).then(function(res) {
                self.threads = res.data.threads;
                self.thread = res.data.threads[0];
                //console.log(self.threads);

                // Posts
                var url = '/api/v1/posts?order=created&owners=threads&ownerId=' + self.thread.id;
                $http.get(url).then(function(res) {
                    self.posts = res.data.posts;
                    //console.log(self.posts);
                });
            });
        }
    });

    // Events
    self.msEvent = 2000;
    self.eventIntervalId = setInterval(function() {
        console.log(self.msEvent);
    }, self.msEvent);
};

gm.SitesHeaderCtrl = function($http) {
    var self = this;

    $http.get('/api/v1/users/0').then(function(res) {
        //console.log(res);
        self.authUser = res.data.user;
    });

    $http.get('/api/v1/sites/1').then(function(res) {
        //console.log(res);
        self.site = res.data.site;
    });
};

gm.SitesFooterCtrl = function($http) {
    var self = this;
};

gm.SitesDescriptionCtrl = function($http) {
    var self = this;

    self.description = 'ギルドに参加して自分たちの仕事について話し合おう！';
};

gm.SitesNewsListCtrl = function($http) {
    var self = this;

    $http.get('/api/v1/news?limit=5').then(function(res) {
        self.news = res.data.news;
    });
};

/**
 * Add controllers to module.
 *
 */

var mod = angular.module('gm');

mod.component('sitesIndex', {
    templateUrl: '/js/template/sites/index.html',
    controller: ['$http', gm.SitesIndexCtrl]
});

mod.component('sitesHeader', {
    templateUrl: '/js/template/sites/header.html',
    controller: ['$http', gm.SitesHeaderCtrl]
});

mod.component('sitesFooter', {
    templateUrl: '/js/template/sites/footer.html',
    controller: ['$http', gm.SitesFooterCtrl]
});

mod.component('gmSitesDescription', {
    templateUrl: '/js/template/sites/description.html',
    controller: ['$http', gm.SitesDescriptionCtrl],
});

mod.component('gmSitesNewsList', {
    templateUrl: '/js/template/sites/news-list.html',
    controller: ['$http', gm.SitesNewsListCtrl],
});

}());

