// Admin Module Definitions
;
(function () {

  var app = angular.module('tavro.admin', ['tavro.services']);

  app.controller('AdminController', ['$scope', '$rootScope', '$filter', '$http', 'TavroApi', 'Alert', 'TavroNodes', function ($scope, $rootScope, $filter, $http, TavroApi, Alert, TavroNodes) {

    $scope.users = [];
    $scope.nodes = [];
    $scope.usersLoading = true;
    $scope.admin = true;
    $scope.pageSize = 25;

    /**
     * Load *all* Users for the administration page.
     */
    $scope.loadUsers = function (size, page) {

      size = !size ? $scope.pageSize : size;
      page = !page ? 1 : page;
      var url = 'users?size='+size+'&page=' + page;
      TavroApi.getAll('summary', url)
      .success(function (data) {
        if (data.length) {
          $scope.users = $scope.users.concat(data);
          if (data.length == size) {
            page++;
            $scope.loadUsers(size, page);
          }
          else {
            $scope.usersLoading = false;
          }
        }
        else {
          $scope.usersLoading = false;
        }
      });

    };

    /**
     * Load all Nodes for the Admin dashboard.
     *
     * @param size
     * @param page
     */
    $scope.loadNodes = function (size, page) {

      $rootScope.node_types = TavroNodes.getTypes();

      size = !size ? 25 : size;
      page = !page ? 1 : page;

      var url = 'nodes?orderBy=create_date&size='+size+'&page=' + page;

      TavroApi.getAll('summary', url)
      .success(function (data) {
        if (data.length) {
          $scope.nodes = $scope.nodes.concat(data);
          if (data.length == size) {
            page++;
            $scope.loadNodes(size, page);
          }
          else {
            $scope.nodesLoading = false;
          }
        }
        else {
          $scope.nodesLoading = false;
        }
      });

    };

    $scope.delete = function(node, event) {

      TavroApi.remove('api', 'nodes', node.id)
      .success(function (data) {
        $scope.nodes.splice(event, 1);
      })
      .error(function(data) {
        Alert.add(Alert.errorType, data.exception.message);
      });

    };

    $scope.approve = function(node, event) {

      var data = {
        status: 1
      };

      TavroApi.patch('api', 'nodes', JSON.stringify(data), node.id)
      .success(function (data) {
        $scope.loadNodes();
      })
      .error(function(data) {
        Alert.add(Alert.errorType, data.exception.message);
      });

    };

    $scope.changeUserStatus = function(status, event) {

      var data = {
        status: status
      };


      TavroApi.patch('tavro', 'users', JSON.stringify(data), mod.id)
      .success(function (data) {
        this.loadUsers();
      })
      .error(function (data) {
        Alert.add(Alert.errorType, data.exception.message);
      });

    };

    $scope.deleteUser = function(user, event) {

      TavroApi.remove('tavro', 'users', user.id)
      .success(function (data) {
        $scope.users.splice(event, 1);
      })
      .error(function (data) {
        Alert.add(Alert.errorType, data.exception.message);
      });

    };

    $scope.activateUser = function() {
      $scope.changeUserStatus(1);
    };

    $scope.deactivateUser = function() {
      $scope.changeUserStatus(0);
    };

    $scope.suspendUser = function() {
      $scope.changeUserStatus(2);
    };

    $scope.$watch(function () {
      return $rootScope.pending_mods;
    }, function () {
      $scope.initializeData();
    }, true);

  }]);

})();