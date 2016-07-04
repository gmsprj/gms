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
                //console.log(self.user);
                self.authUser = res.data.authUser;
                self.viewUser = res.data.viewUser;
                self.viewUserGuilds = res.data.viewUserGuilds;
                self.csrf = res.data.csrf;
            });
        }
    ]
})

}());

