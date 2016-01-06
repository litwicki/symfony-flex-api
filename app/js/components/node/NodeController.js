// Profile Module Definitions

;
(function () {

  var app = angular.module('tavro.node', ['tavro.services', 'angularUtils.directives.dirPagination']);

  app.controller('NodeController', ['$scope', '$rootScope', '$http', 'TavroApi', 'Alert', '$cookies', '$templateCache', 'TavroNodes',
    function ($scope, $rootScope, $http, TavroApi, Alert, $cookies, $templateCache, TavroNodes) {

    $rootScope.node = {};
    $scope.editMode = false;
    $scope.pageSize = 10;
    $scope.latest = [];
    $scope.popular = [];
    $scope.tag = {};
    $scope.filter_node_type = '';
    $scope.nodesLoading = true;
    $scope.nodes = [];
    $scope.node_type = false;

    $scope.loadLatest = function () {

      var url = '';

      if(tavro.node_type === undefined) {
        url = 'nodes?status=1&size=5&orderBy=id&sort=desc';
      }
      else {
        $scope.node_type = tavro.node_type;
        url = 'nodes?status=1&type=' + tavro.node_type + '&size=5&orderBy=id&sort=desc';
      }

      TavroApi.getAll('api', url)
      .success(function (data) {
        $scope.latest = data;
      });

    };

    $scope.loadPopular = function() {

      var url = '';

      if(tavro.node_type === undefined) {
        url = 'nodes?status=1&size=5&orderBy=views&sort=desc';
      }
      else {
        $scope.node_type = tavro.node_type;
        url = 'nodes?status=1&type=' + tavro.node_type + '&size=5&orderBy=views&sort=desc';
      }

      TavroApi.getAll('api', url)
      .success(function (data) {
        $scope.popular = data;
      });

    };

    $scope.saveNode = function(node, event) {

      var user_id = null;
      if(undefined === node.user) {
        user_id = tavro.current_user;
      }
      else {
        user_id = node.user.id;
      }

      var data = {

        title: node.title,
        body: node.body,
        type: node.type,
        user: user_id,
        node_tags: node.node_tags

      };

      if(node.id === undefined) {

        return TavroApi.create('tavro', 'nodes', JSON.stringify(data))
        .success(function (data) {
          Alert.add(Alert.successType, data.title + ' created successfully!');
        })
        .error(function (data) {
          Alert.add(Alert.errorType, data.exception.message);
        });

      }
      else {

        return TavroApi.update('tavro', 'nodes', JSON.stringify(data), node.id)
        .success(function (data) {
          Alert.add(Alert.successType, data.title + ' saved successfully!');
        })
        .error(function (data) {
          Alert.add(Alert.errorType, data.exception.message);
        });

      }

    };

    $scope.initializeData = function () {

      if(tavro.current_node > 0) {
        TavroApi.get('tavro', 'nodes', tavro.current_node)
        .success(function (data) {

          $rootScope.node = data;

          /**
          * With the loaded Mod, increment the view count
          */
          var data = {
            'views': data.views + 1
          };

          TavroApi.patch('api', 'nodes', JSON.stringify(data), tavro.current_node)
          .success(function (data) {

          })
          .error(function (data) {
            Alert.add(Alert.errorType, data.exception.message);
          });

        });
      }

    };

    $scope.reloadNode = function() {
      TavroApi.get('tavro', 'nodes', tavro.current_node)
      .success(function (data) {
        $rootScope.node = data;
      });
    };

    $rootScope.commentsLoading = true;
    $rootScope.comments = [];

    $scope.loadComments = function(size, page) {
      /**
       * Load Comments by streaming them in for performance sake.
       */
      size = !size ? $scope.pageSize : size;
      page = !page ? 1 : page;
      var url = 'comments?node=' + tavro.current_node + '&size='+size+'&page=' + page;
      TavroApi.getAll('tavro', url)
      .success(function (data) {
        if (data.length) {
          $rootScope.comments = $rootScope.comments.concat(data);
          if (data.length == size) {
            page++;
            $scope.loadComments(size, page);
          }
          else {
            $rootScope.commentsLoading = false;
          }
        }
        else {
          $rootScope.commentsLoading = false;
        }
      });
      //console.log($rootScope.comments);
    };

    $scope.refreshNodes = function () {
      $scope.nodes = [];
      $scope.initializeNodes();
    };

    $scope.refreshComments = function() {
      $rootScope.comments = [];
      $scope.loadComments($scope.pageSize, 1);
    };

    $scope.loadNodes = function (size, page) {

      $rootScope.node_types = TavroNodes.getTypes();

      size = !size ? 25 : size;
      page = !page ? 1 : page;

      var url = '';

      if(tavro.node_type === undefined) {
        url = 'nodes?status=1&size='+size+'&page=' + page;
      }
      else {
        $scope.node_type = tavro.node_type;
        url = 'nodes?status=1&type='+tavro.node_type+'&size='+size+'&page=' + page;
      }

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

    $scope.removeComment = function(comment, event) {

      var data = {
        status: 0,
        removed_by: tavro.current_user
      };

      TavroApi.patch('tavro', 'comments', JSON.stringify(data), comment.id)
      .success(function (data) {
        $scope.refreshComments();
      })
      .error(function (data) {
         Alert.add(Alert.errorType, data.exception.message);
      });

    };

    $scope.createComment = function (comment, event) {

      var data = {
        body: comment.body,
        user: tavro.current_user,
        node: tavro.current_node
      };

      TavroApi.create('api', 'comments', JSON.stringify(data))
      .success(function (data) {
         Alert.add(Alert.successType, 'Comment submitted successfully');
         $scope.refreshComments();
      })
      .error(function (data) {
         Alert.add(Alert.errorType, data.exception.message);
      });

    };
    
    $scope.setNodeStatus = function (node, status, event) {

      var data = {
        status: status
      };

      TavroApi.patch('tavro', 'nodes', JSON.stringify(data), tavro.current_node)
      .success(function (data) {
        Alert.add(Alert.successType, data.title + ' saved successfully!');
        $scope.loadMod();
        $scope.loadSpotlight();
      })
      .error(function (data) {
        Alert.add(Alert.errorType, data.exception.message);
      });

    };

    $scope.removeTag = function(tag, event) {
      var index = $scope.node.node_tags.indexOf(tag);
      $scope.node.node_tags.splice(index, 1);
    };

    $scope.getTags = function(viewValue) {
      var params = { title: viewValue };
      return $http.get('/api/v1/typeahead/tags', { params: params })
      .then(function(res) {
        return res.data;
      });
    };

    /**
     * When a tag is selected from the autocomplete, automatically
     * add it to the Node as a new NodeTag.
     */
    $scope.$on('$typeahead.select', function(value, index) {

      var unique = true;

      angular.forEach($rootScope.node.node_tags, function(tag, key) {
        if(tag.title === index && unique) {
          console.log(index + ' is already tagged to ' + $rootScope.node.title);
          unique = false;
        }
      });

      if(unique) {

        var node_tags = [];
        if(undefined != $rootScope.node.node_tags) {
          node_tags = $rootScope.node.node_tags;
        }

        $rootScope.node.node_tags = node_tags.concat({
          title: index,
          body: index
        });

        console.log($rootScope.node);

      }

    });

    $scope.enable = function (node, event) {
      $scope.setNodeStatus(node, 1, event);
    };

    $scope.disable = function (node, event) {
      $scope.setNodeStatus(node, 2, event);
    };

    $scope.delete = function (node, event) {

      TavroApi.remove('api', 'nodes', node.id)
      .success(function (data) {

        /**
         * If we're on a node page that we're deleting, redirect the User to
         * the Administration panel for Nodes.
         */
        if(tavro.current_node !== undefined && tavro.current_node == node.id) {
          var url = '/admin/nodes';
          $timeout(function() {
            if(url || url !== undefined) { window.location = url; }
          }, 10000);
        }
        else {
          var index = $scope.nodes.indexOf(node);
          $scope.nodes.splice(index, 1);
        }

      })
      .error(function (data) {
        Alert.add(Alert.errorType, data.exception.message);
      });

    };

    $scope.publish = function (node, event) {

      var data = {
        display_date: new Date(),
        status: 1
      };

      TavroApi.patch('api', 'nodes', JSON.stringify(data), node.id)
      .success(function (data) {

      })
      .error(function (data) {
        Alert.add(Alert.errorType, data.exception.message);
      });

    };

  }]);

})();