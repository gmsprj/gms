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
            '<h3><a href="/boards/view/{{ $ctrl.guestBoard.id }}">{{ $ctrl.guestBoard.name }}</a></h3>' +
            '<ul>' +
                '<li ng-repeat="el in $ctrl.guestThreads">' +
                    '<a target="_self" href="/threads/view/{{ el.id }}">{{ el.name }}</a>' + 
                '</li>' +
            '</ul>' +
            '',

        controller: ['$http',
            function GmSitesFooterController($http) {
                var self = this;
            }
        ]
    });

}());

