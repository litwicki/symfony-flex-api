jQuery(document).ready(function ($) {

  $('a#clear-data').click(function() {
    var data = {};
    $('#api-data').JSONView(data);
  });

  $('#api-submit').click(function() {
    $('#api-submit').attr('disabled', 'disabled');
    $('#api-form').submit();
  });

  $('#api-form').ajaxForm({
    dataType: 'json',
    beforeSubmit: function(arr, $form, options) {
      // The array of form data takes the following form:
      // [ { name: 'username', value: 'jresig' }, { name: 'password', value: 'secret' } ]

      // return false to cancel submit

      var spinner = '<i class="fa fa-spinner fa-spin fa-4x"></i>';
      var html = $.parseHTML(spinner);
      $('#api-data').html(html);
    },
    success: function(data) {
      $('#api-data').JSONView(data);
      $('#api-submit').removeAttr('disabled');
    },
    error: function (request, status, error) {
      $('#api-data').JSONView(request.responseText);
      $('#api-data').JSONView('collapse', 2);
      $('#api-submit').removeAttr('disabled');
    }
  });

  $('.dropdown-submenu > a').submenupicker();

});