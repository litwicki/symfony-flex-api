// // Angular Application Setup

;
(function () {

  //Changing interpolation to avoid conflict with Symfony
  var app = angular.module('tavro', [
        'tavro.directives',
        'tavro.filters',
        'tavro.components',
        'ngCookies',
        'angularUtils.directives.dirPagination',
        'ngSanitize',
        'ngtimeago',
        'ngRoute',
        'chart.js',
        'textAngular',
        'smart-table',
        'ngFileUpload',
        'mgcrea.ngStrap',
        'checklist-model'
    ],
    function ($interpolateProvider) {
      $interpolateProvider.startSymbol('{[');
      $interpolateProvider.endSymbol(']}');
    }
  );

  app.config(function (paginationTemplateProvider) {
    paginationTemplateProvider.setPath('/bundles/tavroapp/js/directives/dirPagination.tpl.html');
  });

  app.controller('AppController', function ($scope, $modal, $timeout) {

  });

})();
