$(document).ready(function() {
  if ($('a[name=postgresql]').length) {
    $.ajax({
      data: {
        proc: 'postgresql'
      },
      success: function(data) {
        $('.postgresql-checkport').prepend(data.checkport);
        $('.postgresql-checkport').find('.loader').remove();
        
        $('.postgresql-versions').prepend(data.versions);
        $('.postgresql-versions').find('.loader').remove();
      }
    });
  }
});
