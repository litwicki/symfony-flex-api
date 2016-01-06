/** User */
jQuery(document).ready(function ($) {

  $('#user-avatar-form').ajaxForm({
    dataType: 'json',
    beforeSubmit: function() {
      $('#user_avatar_type_submit').prepend('<i id="user_avatar_type_spinner" class="fa fa-fw fa-spinner fa-spin"></i> ');
      $('#user_avatar_type_submit').attr('disabled', 'disabled');
    },
    success: function(data) {

      var type = data.id ? 'success': 'danger';
      var message = data.id ? 'Your avatar was updated!' : data.exception.message;

      var alert = '<div class="alert alert-' + type + '">' + message + '</div>';
      $('#user-avatar-form').prepend(alert);

      $('.user-avatar-' + data.id).attr('src', data.avatar.aws_url);

    }
  });

});