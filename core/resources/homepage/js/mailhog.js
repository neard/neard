$(document).ready(function() {
  if ($('a[name=mailhog]').length) {
    $.ajax({
      data: {
        proc: 'mailhog'
      },
      success: function(data) {
        $('.mailhog-checkport').prepend(data.checkport);
        $('.mailhog-checkport').find('.loader').remove();
        
        $('.mailhog-versions').prepend(data.versions);
        $('.mailhog-versions').find('.loader').remove();
      }
    });
  }
});
