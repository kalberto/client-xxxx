(function () {
    angular.module('app', ['amChartsDirective','ngFileUpload']);

    /** FUNCAO DO SCROLL DO MENU **/

    // variaveis dos elementos utilizados
    var $doc = $(document);
	var $window = $(window);
	var $body = $('body')

	var $footer = $('footer');
	var $aside = $('aside.menu-lateral');
	var $nav = $('nav.js-scroll')

	//conta que faz troca da classe
	var scrollFunction = function(){
		if ( $window.scrollTop() + $window.height() - $nav.height() - 38 > $footer.position().top ){
			$nav.addClass('position-bottom');
		} else {
			$nav.removeClass('position-bottom')
		}
	}

	//variavel de espera
	var w = false;

    if( $body.attr('id','AreaCliente') && $window.outerWidth() > 768 ){

    	$doc.scroll(function(){
    		if(w){
    			return;
    		}

    		w = true;
    		scrollFunction();

    		setTimeout(function(){ w = false; },50)
    	});
    }

})();