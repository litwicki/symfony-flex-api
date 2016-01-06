// Profile Module Definitions

;
(function () {

  var app = angular.module('tavro.user', ['tavro.services', 'angularUtils.directives.dirPagination']);

  app.controller('UserController', ['$scope', '$rootScope', '$http', 'TavroApi', 'Alert', function ($scope, $rootScope, $http, TavroApi, Alert) {

    $scope.profile_user = {};
    $scope.roles = [];
    $scope.user_roles = [];

    $scope.loadUser = function () {
      if(tavro.user > 0) {
        TavroApi.get('tavro', 'users', tavro.user)
        .success(function (data)  {
          $scope.profile_user = data;
        });
      }
    };

    $scope.initProfile = function () {

      if(tavro.profile_user == tavro.current_user) {
        $scope.profile_user = $rootScope.user;
      } else {
        TavroApi.get('tavro', 'users', tavro.profile_user)
        .success(function(data) {
          $scope.profile_user = data;
        })
        .error(function (data) {
          Alert.add(Alert.errorType, data.exception.message);
        });
      }

      TavroApi.getAll('tavro', 'roles')
      .success(function(data) {
        $scope.roles = data;
      })
      .error(function (data) {
        Alert.add(Alert.errorType, data.exception.message);
      });

      //var url = '/ajax/tavro/' + tavro.profile_user + '/roles';
      //TavroApi.request('get', null, url)
      //.success(function (data) {
      //  $scope.roles = data;
      //}).error(function (data) {
      //  Alert.add(Alert.errorType, data.exception.message);
      //});

    };

    $scope.userHasRole = function(role) {

      angular.forEach($scope.profile_user.roles, function(role_name, delta) {
        if(role.role == role_name) {
          return true;
        }
      });

      return false;

    };

    /**
     * Update the User's profile.
     * @param user
     * @param event
     */
    this.updateProfile = function (user, event) {

      var roles = [];
      angular.forEach($scope.user_roles, function(value, key) {
        roles.push(key);
      });

      var data = {
        username: user.username,
        email: user.email,
        gender: user.gender,
        enable_daily_digest: user.enable_daily_digest,
        enable_private_messages: user.enable_private_messages,
        enable_notifications: user.enable_notifications,
        roles: roles
      };

      TavroApi.patch('tavro', 'users', JSON.stringify(data), user.id)
      .success(function (data) {
        Alert.add(Alert.successType, 'Profile updated for ' + data.username);
      })
      .error(function (data) {
        Alert.add(Alert.errorType, data.exception.message);
      });

    };

    /**
     * Reset the User's Api Key (v1)
     * @param user
     * @param event
     */
    this.resetApiKey = function(user, event) {

      var postObject = {
        user: user.id
      };

      var url = '/api/v1/tavro/users/'+user.id+'/reset/api-key';
      var method = 'post';

      TavroApi.request(method, JSON.stringify(postObject), url)
      .success(function (data) {
        $rootScope.user = data;
      }).error(function (data) {
        Alert.add(Alert.errorType, data.exception.message);
      });

    };

    /**
     * Reset the User's Api Password (v2)
     * @param user
     * @param event
     */
    this.resetApiPassword = function(user, event) {

      var postObject = {
        user: user.id
      };

      var url = '/api/v1/tavro/users/'+user.id+'/reset/api-password';
      var method = 'post';

      TavroApi.request(method, JSON.stringify(postObject), url)
      .success(function (data) {
        TavroApi.get('users', data.id);
      }).error(function (data) {
        Alert.add(Alert.errorType, data.exception.message);
      });

    }

    /**
     * Toggle a User's Api v2 Access.
     * @param user
     * @param event
     */
    this.toggleUserApiAccess = function(user, event) {

      var api_status = true;

      if($scope.user.api_enabled) {
        api_status = false;
      } else {
        api_status = true;
      }

      var postObject = {
        api_enabled: api_status
      };

      TavroApi.patch('tavro', 'users', JSON.stringify(postObject), user.id)
      .success(function (data) {
        Alert.add(Alert.successType, 'Your profile was saved.');
      }).error(function (data) {
        Alert.add(Alert.errorType, data.exception.message);
      });

    };

    $scope.$watch(function () {
      return $rootScope.user;
    }, function () {
      $scope.initProfile();
    }, true);

  }]);

})();