$(document).foundation();

//Coller le menu en haut quant on scroll
var $menutest = $('#menutest');
// new Foundation.Sticky($menutest, {marginTop: '0', stickyOn:'large'});

$menu = new Foundation.Sticky($menutest, {marginTop: '0', stickyOn:'large'});

$menutest.click(function(){
    $menutest.foundation('_destroy');
})
