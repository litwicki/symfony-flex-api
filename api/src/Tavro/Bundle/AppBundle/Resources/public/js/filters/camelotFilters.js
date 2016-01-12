;
(function () {

  var Filters = angular.module('tavro.filters', []);

  Filters.filter('truncate', function() {

      return function (content, maxCharacters) {

          if (content == null) return "";

          content = "" + content;

          content = content.trim();

          if (content.length <= maxCharacters) return content;

          content = content.substring(0, maxCharacters);

          var lastSpace = content.lastIndexOf(" ");

          if (lastSpace > -1) content = content.substr(0, lastSpace);

          return content + '...';
      };
  });


  Filters.filter('isempty', function() {
      return function(input, replaceText) {
          if(input) return input;
          return replaceText;
      }
  });

  Filters.filter('getByName', function() {
    return function(input, name) {
      var i=0, len=input.length;
      for (; i<len; i++) {
        if (+input[i].name == +name) {
          return input[i];
        }
      }
      return null;
    }
  });

  Filters.filter('plainText', function() {
      return function(text) {
        return angular.element(text).text();
      }
    }
  );

  Filters.filter('capitalize', function() {
    return function(input, all) {
      return (!!input) ? input.replace(/([^\W_]+[^\s-]*) */g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) : '';
    }
  });
    
})();