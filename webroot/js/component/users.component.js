if (typeof gm === 'undefined') {
    var gm = {};
}

(function() {
'use strict';

gm.UsersViewCtrl = function($http, $location) {
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
};

/**
 * Add controllers to module
 *
 */

var mod = angular.module('gm');

mod.component('gmUsersView', {
    templateUrl: '/js/template/users/view.html',
    controller: ['$http', '$location', gm.UsersViewCtrl]
})

}());

