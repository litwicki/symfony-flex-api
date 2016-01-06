;
(function () {

  var app = angular.module('tavro.services');

	app.factory('TavroAJAX', function($http) {
		return {
			update: function(group, func) {
				return $http.post('/ajax/' + group + '/' + func);
			},
			updateWithObject: function(group, func, object) {
				return $http.post('/ajax/' + group + '/' + func, object);
			},
			upload: function(group, func, file) {
				return $http.post('/ajax/' + group + '/' + func, file);
			}
		}
	}); 

})();