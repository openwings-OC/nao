$(document).foundation();

//Coller le menu en haut quant on scroll
var $menutest = $('#menutest');
new Foundation.Sticky($menutest, {marginTop: '0', stickyOn:'large'});
editModule();


