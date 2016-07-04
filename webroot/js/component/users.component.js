(function() {
'use strict';

var mod = angular.module('gm');

mod.component('usersView', {
    templateUrl: '/js/template/users-view.html',
    controller: ['$http', '$location',
        function UsersViewCtrl($http, $location) {
            var self = this;
            var path = $location.$$path + '.json';
            //console.log(path);

            $http.get(path).then(function(res) {
                self.user = res.data.user;
            });
        }
    ]
})

}());

