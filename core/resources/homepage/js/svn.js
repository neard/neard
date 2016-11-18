$(document).ready(function() {
  if ($('a[name=svn]').length) {
    $.ajax({
      data: {
        proc: 'svn'
      },
      success: function(data) {
        $('.svn-checkport').prepend(data.checkport);
        $('.svn-checkport').find('.loader').remove();
        
        $('.svn-versions').prepend(data.versions);
        $('.svn-versions').find('.loader').remove();
      }
    });
  }
});
