$(function(){
  $('#smt-switch')
    .show()
    .click(function() {
      document.cookie = "disable_smt=1; path=" + openpne.baseUrl;
      location.href = $(this).attr('href');
    });
  $('a.close').click(function() {
    $(this).parent().hide();
  });
});
