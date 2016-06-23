(function() {
'use strict';

var mod = angular.module('threads', [
    'ngRoute',
]);

mod.config(['$locationProvider', '$routeProvider',
    function config($locationProvider, $routeProvider) {
      $locationProvider.hashPrefix('!');

      //$locationProvider.html5Mode(true); // $location.search()
      $locationProvider.html5Mode({
          enabled: true,
          requireBase: false
      });
    }
]);

/**
 * ThreadsView component
 *
 * /threads/view/id のコンポーネント
 */
mod.component('threadsView', {
    template:
        '<h3>{{ $ctrl.board.name }} &gt; {{ $ctrl.thread.name }}</h3>' +
        '<hr/>' +
        '<ul>' +
            '<li ng-repeat="el in $ctrl.posts">' +
                '<p>{{ el.name }}: {{ el.created }}: {{ el.content }}</p>' + 
            '</li>' +
        '</ul>' +
        '<hr/>',

    controller: ['$http', '$location',
        function ThreadsViewController($http, $location) {
            var self = this;

            // URL から ID を取得して GET 先のパスを作成
            var path = $location.$$path;
            var id = path.substr(path.lastIndexOf('/') + 1);
            var getUrl = '/threads/view/' + id + '.json';
            //console.log(getUrl);

            $http.get(getUrl).then(function(res) {
                //console.log(res.data);
                self.postName = res.data.postName;
                self.board = res.data.board;
                self.thread = res.data.thread;
                self.posts = res.data.posts;
            });
        }
    ]
});

}());

