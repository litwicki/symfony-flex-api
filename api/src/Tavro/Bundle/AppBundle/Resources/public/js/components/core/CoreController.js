// Core Module Definitions
;
(function () {

  var app = angular.module('tavro.core', ['tavro.services', 'angularUtils.directives.dirPagination']);

  app.controller('CoreController', ['$scope', '$rootScope', '$http', 'TavroApi', 'Alert', '$cookies', function ($scope, $rootScope, $http, TavroApi, Alert, $cookies) {

    $rootScope.user = {};

    $scope.init = function () {

      TavroApi.get('api', 'users', tavro.current_user)
      .success(function (data) {
        $rootScope.current_user = data;
      });

    };

    $scope.$watch(function () {
      return $rootScope.user;
    });

  }]);

})();