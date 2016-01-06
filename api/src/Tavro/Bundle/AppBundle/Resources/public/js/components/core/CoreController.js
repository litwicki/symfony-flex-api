// Core Module Definitions
;
(function () {

  var app = angular.module('tavro.core', ['tavro.services', 'angularUtils.directives.dirPagination']);

  app.controller('CoreController', ['$scope', '$rootScope', '$http', 'TavroApi', 'Alert', '$cookies', function ($scope, $rootScope, $http, TavroApi, Alert, $cookies) {

    $rootScope.categories = [];
    $rootScope.realms = [];
    $rootScope.classes = [];
    $rootScope.archetypes = [];
    $rootScope.summary = [];
    $rootScope.user = {};
    $rootScope.spotlightMod = {};
    $rootScope.now = new Date();

    $scope.mod_stat_labels = [];
    $scope.mod_stat_data = [];
    $scope.trend_labels = [];
    $scope.trend_data = [];
    $scope.trend_series = [];
    $scope.mod_downloads = [];
    $scope.mod_uploads = [];

    $scope.status_types = [
      {value: '', title: 'All'},
      {value: 1, title: 'Enabled'},
      {value: 2, title: 'Pending'},
      {value: 0, title: 'Disabled'}
    ];

    $scope.refreshData = function() {

    };

    $rootScope.setCookie = function(key, data) {
      $cookies.put(key, JSON.stringify(data), {
        'path': '/'
      });
    };

    $scope.initializeData = function () {

      var cookies = $cookies.getAll();

      if(!cookies.categories) {
        TavroApi.getAll('summary', 'mod_categories')
        .success(function (data) {
          $rootScope.setCookie('categories', data);
          $rootScope.categories = data;
        });
      }
      else {
        $rootScope.categories = JSON.parse(cookies.categories);
      }

      if(tavro.current_user > 0) {
        TavroApi.get('tavro', 'users', tavro.current_user).success(function (data) {
          $rootScope.user = data;
        });
      }

      if(tavro.current_category > 0) {
        TavroApi.get('summary', 'mod_categories', tavro.current_category)
        .success(function (data) {
          $rootScope.current_category = data;
        });
      }

      TavroApi.request('get', null, '/ajax/summary/core')
      .success(function (data) {
        $rootScope.summary = data;

        angular.forEach(data.mods.categories, function(value, key) {
          $scope.mod_stat_labels.push(key);
          $scope.mod_stat_data.push(value);
        });

        angular.forEach(data.mods.downloads.monthly, function(value, key) {
          $scope.trend_labels.push(key);
          $scope.mod_downloads.push(value);
        });

        $scope.trend_data.push($scope.mod_downloads);
        $scope.trend_series.push('Mod Downloads');

        angular.forEach(data.mods.uploads.monthly, function(value, key) {
          $scope.mod_uploads.push(value);
        });

        $scope.trend_data.push($scope.mod_uploads);
        $scope.trend_series.push('New Mods');

      });

      //console.log($rootScope.realms);
      //console.log($rootScope.classes);
      //console.log($rootScope.archetypes);

    };

    $scope.loadSpotlight = function () {
      $rootScope.spotlightMod = {};
    };

    $scope.$watch(function () {
      return $rootScope.categories;
    });

    $scope.$watch(function () {
      return $rootScope.summary;
    });

    $scope.$watch(function () {
      return $rootScope.user;
    });

    $scope.$watch(function () {
      return $rootScope.spotlightMod;
    }, function () {
      $scope.loadSpotlight();
    }, true);

  }]);

})();