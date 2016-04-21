$(document).ready(function() {
  if ($('a[name=mariadb]').length) {
    $.ajax({
      data: {
        proc: 'mariadb'
      },
      success: function(data) {
        $('.mariadb-checkport').prepend(data.checkport);
        $('.mariadb-checkport').find('.loader').remove();
        
        $('.mariadb-versions').prepend(data.versions);
        $('.mariadb-versions').find('.loader').remove();
      }
    });
  }
});
