(function() {
'use strict';

var mod = angular.module('users', []);

mod.config(['$locationProvider',
    function config($locationProvider) {
        $locationProvider.hashPrefix('!');

        $locationProvider.html5Mode({
            enabled: true,
            requireBase: false
        });
    }
]);

/**
 * UsersView component
 *
 * /users/view/id のコンポーネント
 */
mod.component('usersView', {
    template:
        '<p>{{ $ctrl.user.name }} のマイページです。</p>',

    controller: ['$http', '$location',
        function UserViewController($http, $location) {
            var self = this;

            // URL から ID を取得して GET 先のパスを作成
            var path = $location.$$path;
            var id= path.substr(path.lastIndexOf('/') + 1);
            var getUrl = '/users/view/' + id + '.json';
            //console.log(getUrl);

            $http.get(getUrl).then(function(res) {
                //console.log(res.data);
                self.user = res.data.user;
                self.threads = res.data.threads;
            });
        }
    ]
});

}());

