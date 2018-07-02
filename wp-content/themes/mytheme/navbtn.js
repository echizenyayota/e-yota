jQuery (function() {
  jQuery ("#navbtn").click(function(){
    jQuery ("#mainmenu").slideToggle();
  });
});

// スマホのナビメニューを正確に動かせるようにする
jQuery('ul#menu-menu li:has(.sub-menu) > a').click(function() {
  if (jQuery('#navbtn').css('display') != 'none') {
    jQuery('.sub-menu', jQuery(this).parent()).toggle();
    return false;
  }
});
