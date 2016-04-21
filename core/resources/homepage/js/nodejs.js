$(document).ready(function() {
  if ($('a[name=nodejs]').length) {
    $.ajax({
      data: {
        proc: 'nodejs'
      },
      success: function(data) {
        $('.nodejs-versions').prepend(data.versions);
        $('.nodejs-versions').find('.loader').remove();
      }
    });
  }
});