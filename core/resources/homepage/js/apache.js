$(document).ready(function() {
  if ($('a[name=apache]').length) {
    $.ajax({
      data: {
        proc: 'apache'
      },
      success: function(data) {
        $('.apache-checkport').prepend(data.checkport);
        $('.apache-checkport').find('.loader').remove();
        
        $('.apache-versions').prepend(data.versions);
        $('.apache-versions').find('.loader').remove();
        
        $('.apache-modulescount').prepend(data.modulescount);
        $('.apache-modulescount').find('.loader').remove();
        
        $('.apache-aliasescount').prepend(data.aliasescount);
        $('.apache-aliasescount').find('.loader').remove();
        
        $('.apache-vhostscount').prepend(data.vhostscount);
        $('.apache-vhostscount').find('.loader').remove();
        
        $('.apache-moduleslist').prepend(data.moduleslist);
        $('.apache-moduleslist').find('.loader').remove();
        
        $('.apache-aliaseslist').prepend(data.aliaseslist);
        $('.apache-aliaseslist').find('.loader').remove();
        
        $('.apache-wwwdirectory').prepend(data.wwwdirectory);
        $('.apache-wwwdirectory').find('.loader').remove();
        
        $('.apache-vhostslist').prepend(data.vhostslist);
        $('.apache-vhostslist').find('.loader').remove();
      }
    });
  }
});
