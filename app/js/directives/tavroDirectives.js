;
(function () {

  var Directives = angular.module('tavro.directives', []);

  //Custom Confirmation Dialog
  //add as attribute to button/element to be clicked
  Directives.directive('ngConfirmClick', ['$timeout', 'dialogs',
    function ($timeout, dialogs) {
      return {
        priority: -1,
        restrict: 'A',
        link: function (scope, element, attrs) {
          element.bind('click', function (e) {
            scope.confirmed = false;
            var message = attrs.ngConfirmClick;
            if (message && !scope.confirmed) {
              e.stopImmediatePropagation();
              var dlg = dialogs.confirm('Please Confirm', message);
              dlg.result.then(function (btn) {
                scope.$eval(attrs.ngClick);
              }, function (btn) {
                //canceled out
                e.stopImmediatePropagation();
                e.preventDefault();
              });
            }
            ;
            // if(message && !confirm(message)){
            //   e.stopImmediatePropagation();
            //   e.preventDefault();
            // }
          });
        }
      }
    }
  ]);

  Directives.directive('contenteditable', function () {
    return {
      restrict: 'A',
      priority: -1,
      require: 'ngModel',
      link: function (scope, element, attrs, ngModel) {

        function read() {
          ngModel.$setViewValue(element.html());
        }

        ngModel.$render = function () {
          element.html(ngModel.$viewValue || "");
        };

        element.bind('blur change', function () {
          scope.$apply(read);
        });

        element.bind('keydown', function (event) {
          //Necessary to kill blockage of space key
          if (event.which == 32) {
            event.stopImmediatePropagation();
          }

          var esc = event.which == 27,
              enter = event.which == 13,
              tab = event.which == 9,
              el = event.target;

          if (esc || enter || tab) {
            ngModel.$setViewValue(element.html());
            el.blur();
            event.preventDefault();
          }

        });

      }
    };
  });

  Directives.directive('selectOnClick', function () {
    return {
      restrict: 'A',
      link: function (scope, element, attrs) {
        element.on('click', function () {
          this.select();
        });
      }
    };
  });

  Directives.directive('resetOnBlur', function () {
    return {
      restrict: 'A',
      link: function (scope, element, attrs) {
        element.on('blur', function () {
          //console.log(attrs);
          this.value = attrs.reset;
        });
      }
    };
  });

  Directives.directive('ngEnter', function () {
    return function (scope, element, attrs) {
      element.bind("keydown keypress", function (event) {
        if (event.which === 13) {
          scope.$apply(function () {
            scope.$eval(attrs.ngEnter);
          });

          event.preventDefault();
        }
      });
    };
  });

  Directives.directive('disableEnter', function () {
    return function (scope, element, attrs) {
      element.bind("keydown keypress", function (event) {
        if (event.which === 13) {
          event.preventDefault();
        }
      });
    };
  });

  Directives.directive('fileModel', ['$parse', function ($parse) {
    return {
      restrict: 'A',
      link: function(scope, element, attrs) {
        var model = $parse(attrs.fileModel);
        var modelSetter = model.assign;

        element.bind('change', function(){
          scope.$apply(function(){
            modelSetter(scope, element[0].files[0]);
          });
        });
      }
    };
  }]);


})();