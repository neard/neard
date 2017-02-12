$(document).ready(function() {
  if ($('a[name=mongodb]').length) {
    $.ajax({
      data: {
        proc: 'mongodb'
      },
      success: function(data) {
        $('.mongodb-checkport').prepend(data.checkport);
        $('.mongodb-checkport').find('.loader').remove();
        
        $('.mongodb-versions').prepend(data.versions);
        $('.mongodb-versions').find('.loader').remove();
      }
    });
  }
});
