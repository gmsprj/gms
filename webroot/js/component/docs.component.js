(function() {
'use strict';

var mod = angular.module('gm');

mod.component('docsIndex', {
    templateUrl: '/js/template/docs-index.html',
    controller: ['$http',
        function GuildsIndexCtrl($http) {
            var self = this;

            $http.get('/docs.json').then(function(res) {
                //console.log(res);
                self.nd = res.data.nd;
                self.customDocs = res.data.customDocs;
                self.csrf = res.data.csrf;
            });
        }
    ]
});

mod.component('docsView', {
    templateUrl: '/js/template/docs-view.html',
    controller: ['$http', '$location',
        function GuildsViewCtrl($http, $location) {
            var self = this;
            var path = $location.$$path + '.json';
            //console.log(path);

            $http.get(path).then(function(res) {
                //console.log(res);
                self.doc = res.data.doc;
                self.guild = res.data.guild;
            });
        }
    ]
})

}());

