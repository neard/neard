$(document).ready(function() {
  if ($('a[name=filezilla]').length) {
    $.ajax({
      data: {
        proc: 'filezilla'
      },
      success: function(data) {
        $('.filezilla-checkport').prepend(data.checkport);
        $('.filezilla-checkport').find('.loader').remove();
        
        $('.filezilla-versions').prepend(data.versions);
        $('.filezilla-versions').find('.loader').remove();
      }
    });
  }
});
