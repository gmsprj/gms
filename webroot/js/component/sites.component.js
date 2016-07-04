(function() {
'use strict';

var mod = angular.module('gm');

mod.component('sitesHeader', {
    templateUrl: '/js/template/sites-header.html',
    controller: ['$http',
        function sitesHeaderCtrl($http) {
            var self = this;

            $http.get('/sites/view/1.json').then(function(res) {
                //console.log(res);
                //console.log(res.data.user);
                self.site = res.data.site;
                self.user = res.data.user;
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

