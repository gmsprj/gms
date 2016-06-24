(function() {
'use strict';

var mod = angular.module('threads', []);

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
 * Threads component
 *
 * /threads のコンポーネント
 */
mod.component('threads', {
    template:
        '<ul>' +
            '<li ng-repeat="el in $ctrl.threads">' +
                '<a target="_self" href="/threads/view/{{ el.id }}">{{ el.name }}</a>' + 
            '</li>' +
        '</ul>',

    controller: ['$http',
        function ThreadsViewController($http) {
            var self = this;

            $http.get('/threads.json').then(function(res) {
                //console.log(res.data);
                self.threads = res.data.threads;
            });
        }
    ]
});

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

