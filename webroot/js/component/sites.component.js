(function() {
'use strict';

var mod = angular.module('gm');

mod.component('sitesHeader', {
    templateUrl: '/js/template/sites-header.html',
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
    templateUrl: '/js/template/sites-footer.html',
    controller: ['$http',
        function sitesFooterCtrl($http) {
        }
    ]
});

}());

