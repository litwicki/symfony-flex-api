;
(function () {

  var app = angular.module('tavro.services');

  app.factory('TavroUsers', function ($http) {
    return {
      findAllByUsername: function(username) {
        var url = '/api/v1/api/users?username=' + username;
        return $http.get(url);
      }
    }
  });

})();