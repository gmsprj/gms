(function() {
'use strict';

angular.module('gm').
    component('gmSitesHeader', {
        templateUrl: '/js/gm/template/sites-header.html',
        controller: ['$http',
            function gmSitessHeaderController($http) {
                var self = this;

                $http.get('/sites/view/1.json').then(function(res) {
                    console.log(res);
                    self.site = res.data.site;
                });
            }
        ]
    });

angular.module('gm').
    component('gmSitesFooter', {
        template:
            '<h3>Footer</h3>',

        controller: ['$http',
            function GmSitesFooterController($http) {
                var self = this;
            }
        ]
    });

}());

