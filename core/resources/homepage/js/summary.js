$(document).ready(function() {
  if ($('.summary').length) {
    $.ajax({
      data: {
        proc: 'summary'
      },
      success: function(data) {
        $('.summary-binapache').prepend(data.binapache);
        $('.summary-binapache').find('.loader').remove();
        
        $('.summary-binfilezilla').prepend(data.binfilezilla);
        $('.summary-binfilezilla').find('.loader').remove();
        
        $('.summary-binmailhog').prepend(data.binmailhog);
        $('.summary-binmailhog').find('.loader').remove();
        
        $('.summary-binmariadb').prepend(data.binmariadb);
        $('.summary-binmariadb').find('.loader').remove();
        
        $('.summary-binmysql').prepend(data.binmysql);
        $('.summary-binmysql').find('.loader').remove();
        
        $('.summary-binpostgresql').prepend(data.binpostgresql);
        $('.summary-binpostgresql').find('.loader').remove();
        
        $('.summary-binmemcached').prepend(data.binmemcached);
        $('.summary-binmemcached').find('.loader').remove();
        
        $('.summary-binnodejs').prepend(data.binnodejs);
        $('.summary-binnodejs').find('.loader').remove();
        
        $('.summary-binphp').prepend(data.binphp);
        $('.summary-binphp').find('.loader').remove();
      }
    });
  }
});