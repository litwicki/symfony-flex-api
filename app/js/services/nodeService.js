;
(function () {

  var app = angular.module('tavro.services');

  app.factory('TavroNodes', function ($http) {

    return {

      getLatest: function(type) {
        var url = prefix + group + '/' + entity;
        return $http.get(url);
      },

      getHottest: function(type) {
        var url = prefix + group + '/' + entity;
        return $http.get(url);
      },

      getTypes: function() {

        var types = [];

        types.push({
          value: '',
          title: 'All'
        });
        types.push({
          value: 'article',
          title: 'Article'
        });
        types.push({
          value: 'guide',
          title: 'Guide'
        });
        types.push({
          value: 'wiki',
          title: 'Wiki'
        });

        return types;

      }

    }
  });

})();