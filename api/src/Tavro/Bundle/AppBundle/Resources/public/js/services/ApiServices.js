;
(function () {

  var app = angular.module('tavro.services');

  app.factory('TavroApi', function ($http, $cookies) {

    //console.log($cookies.getAll());
    //console.log($cookies.get('api_key'));
    $http.defaults.headers.common['Tavro-Api-Key'] = btoa($cookies.get('api_key'));

    var prefix = '/api/v1/';

    return {
      getAll: function (group, entity) {
        var url = prefix + group + '/' + entity;
        return $http.get(url);
      },
      get: function (group, entity, id) {
        var url = prefix + group + '/' + entity + '/' + id;
        return $http.get(url);
      },
      create: function (group, entity, data) {
        var url = prefix + group + '/' + entity;
        return $http.post(url, data);
      },
      remove: function (group, entity, id) {
        var url = prefix + entity + '/' + id;
        return $http.delete(url);
      },
      patch: function (group, entity, data, id) {
        var url = prefix + group + '/' + entity + '/' + id;
        return $http.patch(url, data);
      },
      update: function (group, entity, data, id) {
        var url = prefix + group + '/' + entity + '/' + id;
        return $http.put(url, data);
      },
      request: function (method, data, url) {
        return $http({
          url: url,
          data: data,
          method: method
        });
      }
    }
  });

})();