(function() {
'use strict';

var mod = angular.module('gm');

mod.component('usersView', {
    templateUrl: '/js/template/users-view.html',
    controller: ['$http', '$location',
        function UsersViewCtrl($http, $location) {
            var self = this;
            var id = $location.$$path.substr($location.$$path.lastIndexOf('/') + 1);
            var q = '';

            q = '/api/v1/users/0';
            $http.get(q).then(function(res) {
                console.log(res.data);
                self.authUser = res.data.user;

                q = '/api/v1/users/' + id;
                $http.get(q).then(function(res) {
                    //console.log(res.data);
                    self.viewUser = res.data.user;
                });
            });
        }
    ]
})

}());

