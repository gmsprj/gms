(function() {
'use strict';

var gm = angular.module('gm');

gm.component('sitesHeader', {
    templateUrl: '/js/template/sites-header.html',
    controller: ['$http',
        function sitesHeaderController($http) {
            var self = this;

            $http.get('/sites/view/1.json').then(function(res) {
                //console.log(res);
                self.site = res.data.site;
            });
        }
    ]
});

gm.component('sitesFooter', {
    templateUrl: '/js/template/sites-footer.html',
    controller: ['$http',
        function sitesFooterController($http) {
            var self = this;
        }
    ]
});

}());

