$(document).ready(function() {
  if ($('a[name=mysql]').length) {
    $.ajax({
      data: {
        proc: 'mysql'
      },
      success: function(data) {
        $('.mysql-checkport').prepend(data.checkport);
        $('.mysql-checkport').find('.loader').remove();
        
        $('.mysql-versions').prepend(data.versions);
        $('.mysql-versions').find('.loader').remove();
      }
    });
  }
});
