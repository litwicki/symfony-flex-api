;
(function () {

  var app = angular.module('tavro.services');

  app.service('Alert', function ($rootScope, $timeout) {

    $rootScope.alerts = [];

    this.add = function (type, message) {
      var alert = {'type': type, 'message': message};

      $rootScope.alerts.push(alert);
      $timeout(function () {
        $rootScope.alerts.splice($rootScope.alerts.indexOf(alert), 1);
      }, 5000);
    };

    //defaults for alert messages/types
    this.errorType = 'danger';
    this.errorMessage = 'Sorry, there was an error while processing your request.';

    this.successType = 'success';
    this.successMessage = 'Saved successfully.';

    this.noticeType = 'info';
    this.noticeMessage = '';

    this.warningType = 'warning';
    this.warningMessage = 'Warning!';

  });

})();