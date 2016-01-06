// Profile Module Definitions

;
(function () {

  var app = angular.module('tavro.comment', ['tavro.services', 'angularUtils.directives.dirPagination']);

  app.controller('CommentController', ['$scope', '$rootScope', '$http', 'TavroApi', 'Alert', '$cookies', function ($scope, $rootScope, $http, TavroApi, Alert, $cookies) {

    $scope.saveComment = function(comment, event) {

      var data = {
        body: comment.body,
        title: comment.title,
        updated_by: tavro.current_user
      };

      var commentElement = $('#comment-' + comment.id);

      TavroApi.patch('tavro', 'comments', JSON.stringify(data), comment.id)
      .success(function (data) {
        Alert.add(Alert.successType, data.title + ' saved successfully!');
        $('#comment-' + data.id).addClass('list-group-info').delay(3000).removeClass('list-group-info');
      })
      .error(function (data) {
        Alert.add(Alert.errorType, data.exception.message);
      });

    };

  }]);

})();