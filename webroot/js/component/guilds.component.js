if (typeof gm === 'undefined') {
    var gm = {};
}

(function() {
'use strict';

var mod = angular.module('gm');

gm.GuildsIndexCtrl = function($http) {
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

    $http.get('/api/v1/guilds').then(function(res) {
        //console.log(res);
        self.guild = res.data.guilds[0];
        if (self.guild) {
            var url = '/api/v1/guilds/' + self.guild.id + '/threads';
            $http.get(url).then(function(res) {
                self.threads = res.data.threads;
                console.log(self.threads);
            });
        }
    });
};

gm.GuildsViewCtrl = function($http, $location) {
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

        q = '/api/v1/news?owners=guilds&ownerId=' + id;
        $http.get(q).then(function(res) {
            //console.log(res.data);
            self.news = res.data.news;
        });
    });
};

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

/**
 * 
 *
 */
gm.GuildsThumbsCtrl = function($http) {
    var self = this;

    $http.get('/guilds').then(function(res) {
        self.guilds = res.data.guilds;
        if (!self.guilds) {
            return;
        }

        for (var i = 0, len = self.guilds.length; i < len; ++i) {
            (function() {
                var _guild = self.guilds[i];
                return function() {
                    $http.get('/api/v1/guilds/' + _guild.id).then(function(res) {
                        _guild.symbol = res.data.guild.images[0];
                    });
                };
            }())();
        }
    });
};

mod.component('gmGuildsIndex', {
    templateUrl: '/js/template/guilds/index.html',
    controller: ['$http', gm.GuildsIndexCtrl]
});

mod.component('gmGuildsView', {
    templateUrl: '/js/template/guilds/view.html',
    controller: ['$http', '$location', gm.GuildsViewCtrl]
})

mod.component('gmGuildsList', {
    templateUrl: '/js/template/guilds/list.html',
    controller: ['$http',  gm.GuildsListCtrl],
});

mod.component('gmGuildsThumbs', {
    templateUrl: '/js/template/guilds/thumbs.html',
    controller: ['$http',  gm.GuildsThumbsCtrl],
});

}());

