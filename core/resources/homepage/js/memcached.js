$(document).ready(function() {
  if ($('a[name=memcached]').length) {
    $.ajax({
      data: {
        proc: 'memcached'
      },
      success: function(data) {
        $('.memcached-checkport').prepend(data.checkport);
        $('.memcached-checkport').find('.loader').remove();
        
        $('.memcached-versions').prepend(data.versions);
        $('.memcached-versions').find('.loader').remove();
      }
    });
  }
});
