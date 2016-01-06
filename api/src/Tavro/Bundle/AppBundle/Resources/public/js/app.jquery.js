/** Tavro */
jQuery(document).ready(function ($) {

  $("ul.nav-tabs li").on("click", function() {
    var cookieTabName = 'tab-'+document.URL.replace("/", "").replace(":", "").replace("%", "").replace("#", "");
    var cookieTabValue = $(this).find("a[role='tab']").attr("href");
    $.cookie(cookieTabName, cookieTabValue);
  });

  var cookieTabName = 'tab-'+document.URL.replace("/", "").replace(":", "");
  if ($.cookie(cookieTabName)) {
    $("ul.nav-tabs li a[href='"+$.cookie(cookieTabName)+"']").click();
  }

});