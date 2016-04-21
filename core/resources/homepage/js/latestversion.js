$(document).ready(function() {
  if ($('.latestversion').length) {
    $.ajax({
      data: {
        proc: 'latestversion'
      },
      success: function(data) {
        if (data.display) {
          $('.latestversion-download').append(data.download);
          $('.latestversion-changelog').append(data.changelog);
          $('.latestversion').show();
        }
      }
    });
  }
});