$(document).ready(function() {
  if ($('a[name=php]').length) {
    $.ajax({
      data: {
        proc: 'php'
      },
      success: function(data) {
        $('.php-status').prepend(data.status);
        $('.php-status').find('.loader').remove();
        
        $('.php-versions').prepend(data.versions);
        $('.php-versions').find('.loader').remove();
        
        $('.php-extscount').prepend(data.extscount);
        $('.php-extscount').find('.loader').remove();
        
        $('.php-pearversion').prepend(data.pearversion);
        $('.php-pearversion').find('.loader').remove();
        
        $('.php-extslist').prepend(data.extslist);
        $('.php-extslist').find('.loader').remove();
      }
    });
  }
});