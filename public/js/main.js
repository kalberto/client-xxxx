/* Modernizr 2.6.2 (Custom Build) | MIT & BSD */
;window.Modernizr=function(a,b,c){function z(a){j.cssText=a}function A(a,b){return z(m.join(a+";")+(b||""))}function B(a,b){return typeof a===b}function C(a,b){return!!~(""+a).indexOf(b)}function D(a,b){for(var d in a){var e=a[d];if(!C(e,"-")&&j[e]!==c)return b=="pfx"?e:!0}return!1}function E(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:B(f,"function")?f.bind(d||b):f}return!1}function F(a,b,c){var d=a.charAt(0).toUpperCase()+a.slice(1),e=(a+" "+o.join(d+" ")+d).split(" ");return B(b,"string")||B(b,"undefined")?D(e,b):(e=(a+" "+p.join(d+" ")+d).split(" "),E(e,b,c))}var d="2.6.2",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k,l={}.toString,m=" -webkit- -moz- -o- -ms- ".split(" "),n="Webkit Moz O ms",o=n.split(" "),p=n.toLowerCase().split(" "),q={},r={},s={},t=[],u=t.slice,v,w=function(a,c,d,e){var f,i,j,k,l=b.createElement("div"),m=b.body,n=m||b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),l.appendChild(j);return f=["&#173;",'<style id="s',h,'">',a,"</style>"].join(""),l.id=h,(m?l:n).innerHTML+=f,n.appendChild(l),m||(n.style.background="",n.style.overflow="hidden",k=g.style.overflow,g.style.overflow="hidden",g.appendChild(n)),i=c(l,a),m?l.parentNode.removeChild(l):(n.parentNode.removeChild(n),g.style.overflow=k),!!i},x={}.hasOwnProperty,y;!B(x,"undefined")&&!B(x.call,"undefined")?y=function(a,b){return x.call(a,b)}:y=function(a,b){return b in a&&B(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=u.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(u.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(u.call(arguments)))};return e}),q.csstransforms=function(){return!!F("transform")},q.csstransforms3d=function(){var a=!!F("perspective");return a&&"webkitPerspective"in g.style&&w("@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}",function(b,c){a=b.offsetLeft===9&&b.offsetHeight===3}),a},q.csstransitions=function(){return F("transition")};for(var G in q)y(q,G)&&(v=G.toLowerCase(),e[v]=q[G](),t.push((e[v]?"":"no-")+v));return e.addTest=function(a,b){if(typeof a=="object")for(var d in a)y(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},z(""),i=k=null,function(a,b){function k(a,b){var c=a.createElement("p"),d=a.getElementsByTagName("head")[0]||a.documentElement;return c.innerHTML="x<style>"+b+"</style>",d.insertBefore(c.lastChild,d.firstChild)}function l(){var a=r.elements;return typeof a=="string"?a.split(" "):a}function m(a){var b=i[a[g]];return b||(b={},h++,a[g]=h,i[h]=b),b}function n(a,c,f){c||(c=b);if(j)return c.createElement(a);f||(f=m(c));var g;return f.cache[a]?g=f.cache[a].cloneNode():e.test(a)?g=(f.cache[a]=f.createElem(a)).cloneNode():g=f.createElem(a),g.canHaveChildren&&!d.test(a)?f.frag.appendChild(g):g}function o(a,c){a||(a=b);if(j)return a.createDocumentFragment();c=c||m(a);var d=c.frag.cloneNode(),e=0,f=l(),g=f.length;for(;e<g;e++)d.createElement(f[e]);return d}function p(a,b){b.cache||(b.cache={},b.createElem=a.createElement,b.createFrag=a.createDocumentFragment,b.frag=b.createFrag()),a.createElement=function(c){return r.shivMethods?n(c,a,b):b.createElem(c)},a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+l().join().replace(/\w+/g,function(a){return b.createElem(a),b.frag.createElement(a),'c("'+a+'")'})+");return n}")(r,b.frag)}function q(a){a||(a=b);var c=m(a);return r.shivCSS&&!f&&!c.hasCSS&&(c.hasCSS=!!k(a,"article,aside,figcaption,figure,footer,header,hgroup,nav,section{display:block}mark{background:#FF0;color:#000}")),j||p(a,c),a}var c=a.html5||{},d=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,e=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,f,g="_html5shiv",h=0,i={},j;(function(){try{var a=b.createElement("a");a.innerHTML="<xyz></xyz>",f="hidden"in a,j=a.childNodes.length==1||function(){b.createElement("a");var a=b.createDocumentFragment();return typeof a.cloneNode=="undefined"||typeof a.createDocumentFragment=="undefined"||typeof a.createElement=="undefined"}()}catch(c){f=!0,j=!0}})();var r={elements:c.elements||"abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video",shivCSS:c.shivCSS!==!1,supportsUnknownElements:j,shivMethods:c.shivMethods!==!1,type:"default",shivDocument:q,createElement:n,createDocumentFragment:o};a.html5=r,q(b)}(this,b),e._version=d,e._prefixes=m,e._domPrefixes=p,e._cssomPrefixes=o,e.testProp=function(a){return D([a])},e.testAllProps=F,e.testStyles=w,e.prefixed=function(a,b,c){return b?F(a,b,c):F(a,"pfx")},g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+t.join(" "):""),e}(this,this.document),function(a,b,c){function d(a){return"[object Function]"==o.call(a)}function e(a){return"string"==typeof a}function f(){}function g(a){return!a||"loaded"==a||"complete"==a||"uninitialized"==a}function h(){var a=p.shift();q=1,a?a.t?m(function(){("c"==a.t?B.injectCss:B.injectJs)(a.s,0,a.a,a.x,a.e,1)},0):(a(),h()):q=0}function i(a,c,d,e,f,i,j){function k(b){if(!o&&g(l.readyState)&&(u.r=o=1,!q&&h(),l.onload=l.onreadystatechange=null,b)){"img"!=a&&m(function(){t.removeChild(l)},50);for(var d in y[c])y[c].hasOwnProperty(d)&&y[c][d].onload()}}var j=j||B.errorTimeout,l=b.createElement(a),o=0,r=0,u={t:d,s:c,e:f,a:i,x:j};1===y[c]&&(r=1,y[c]=[]),"object"==a?l.data=c:(l.src=c,l.type=a),l.width=l.height="0",l.onerror=l.onload=l.onreadystatechange=function(){k.call(this,r)},p.splice(e,0,u),"img"!=a&&(r||2===y[c]?(t.insertBefore(l,s?null:n),m(k,j)):y[c].push(l))}function j(a,b,c,d,f){return q=0,b=b||"j",e(a)?i("c"==b?v:u,a,b,this.i++,c,d,f):(p.splice(this.i++,0,a),1==p.length&&h()),this}function k(){var a=B;return a.loader={load:j,i:0},a}var l=b.documentElement,m=a.setTimeout,n=b.getElementsByTagName("script")[0],o={}.toString,p=[],q=0,r="MozAppearance"in l.style,s=r&&!!b.createRange().compareNode,t=s?l:n.parentNode,l=a.opera&&"[object Opera]"==o.call(a.opera),l=!!b.attachEvent&&!l,u=r?"object":l?"script":"img",v=l?"script":u,w=Array.isArray||function(a){return"[object Array]"==o.call(a)},x=[],y={},z={timeout:function(a,b){return b.length&&(a.timeout=b[0]),a}},A,B;B=function(a){function b(a){var a=a.split("!"),b=x.length,c=a.pop(),d=a.length,c={url:c,origUrl:c,prefixes:a},e,f,g;for(f=0;f<d;f++)g=a[f].split("="),(e=z[g.shift()])&&(c=e(c,g));for(f=0;f<b;f++)c=x[f](c);return c}function g(a,e,f,g,h){var i=b(a),j=i.autoCallback;i.url.split(".").pop().split("?").shift(),i.bypass||(e&&(e=d(e)?e:e[a]||e[g]||e[a.split("/").pop().split("?")[0]]),i.instead?i.instead(a,e,f,g,h):(y[i.url]?i.noexec=!0:y[i.url]=1,f.load(i.url,i.forceCSS||!i.forceJS&&"css"==i.url.split(".").pop().split("?").shift()?"c":c,i.noexec,i.attrs,i.timeout),(d(e)||d(j))&&f.load(function(){k(),e&&e(i.origUrl,h,g),j&&j(i.origUrl,h,g),y[i.url]=2})))}function h(a,b){function c(a,c){if(a){if(e(a))c||(j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}),g(a,j,b,0,h);else if(Object(a)===a)for(n in m=function(){var b=0,c;for(c in a)a.hasOwnProperty(c)&&b++;return b}(),a)a.hasOwnProperty(n)&&(!c&&!--m&&(d(j)?j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}:j[n]=function(a){return function(){var b=[].slice.call(arguments);a&&a.apply(this,b),l()}}(k[n])),g(a[n],j,b,n,h))}else!c&&l()}var h=!!a.test,i=a.load||a.both,j=a.callback||f,k=j,l=a.complete||f,m,n;c(h?a.yep:a.nope,!!i),i&&c(i)}var i,j,l=this.yepnope.loader;if(e(a))g(a,0,l,0);else if(w(a))for(i=0;i<a.length;i++)j=a[i],e(j)?g(j,0,l,0):w(j)?B(j):Object(j)===j&&h(j,l);else Object(a)===a&&h(a,l)},B.addPrefix=function(a,b){z[a]=b},B.addFilter=function(a){x.push(a)},B.errorTimeout=1e4,null==b.readyState&&b.addEventListener&&(b.readyState="loading",b.addEventListener("DOMContentLoaded",A=function(){b.removeEventListener("DOMContentLoaded",A,0),b.readyState="complete"},0)),a.yepnope=k(),a.yepnope.executeStack=h,a.yepnope.injectJs=function(a,c,d,e,i,j){var k=b.createElement("script"),l,o,e=e||B.errorTimeout;k.src=a;for(o in d)k.setAttribute(o,d[o]);c=j?h:c||f,k.onreadystatechange=k.onload=function(){!l&&g(k.readyState)&&(l=1,c(),k.onload=k.onreadystatechange=null)},m(function(){l||(l=1,c(1))},e),i?k.onload():n.parentNode.insertBefore(k,n)},a.yepnope.injectCss=function(a,c,d,e,g,i){var e=b.createElement("link"),j,c=i?h:c||f;e.href=a,e.rel="stylesheet",e.type="text/css";for(j in d)e.setAttribute(j,d[j]);g||(n.parentNode.insertBefore(e,n),m(c,0))}}(this,document),Modernizr.load=function(){yepnope.apply(window,[].slice.call(arguments,0))};

// Função de construção do slider
(function(c,b,e){var d=b.Modernizr;c.CBPFWSlider=function(f,g){this.$el=c(g);this._init(f)};c.CBPFWSlider.defaults={speed:500,easing:"ease"};c.CBPFWSlider.prototype={_init:function(f){this.options=c.extend(true,{},c.CBPFWSlider.defaults,f);this._config();this._initEvents()},_config:function(){this.$list=this.$el.children("ul");this.$items=this.$list.children("li");this.itemsCount=this.$items.length;this.support=d.csstransitions&&d.csstransforms;this.support3d=d.csstransforms3d;var h={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd",msTransition:"MSTransitionEnd",transition:"transitionend"},k={WebkitTransform:"-webkit-transform",MozTransform:"-moz-transform",OTransform:"-o-transform",msTransform:"-ms-transform",transform:"transform"};if(this.support){this.transEndEventName=h[d.prefixed("transition")]+".cbpFWSlider";this.transformName=k[d.prefixed("transform")]}this.current=0;this.old=0;this.isAnimating=false;this.$list.css("width",100*this.itemsCount+"%");if(this.support){this.$list.css("transition",this.transformName+" "+this.options.speed+"ms "+this.options.easing)}this.$items.css("width",100/this.itemsCount+"%");if(this.itemsCount>1){this.$navPrev=c('<span class="cbp-fwprev">&lt;</span>').hide();this.$navNext=c('<span class="cbp-fwnext">&gt;</span>');c("<nav/>").append(this.$navPrev,this.$navNext).appendTo(this.$el);var l="";for(var g=0;g<this.itemsCount;++g){var f=g===this.current?'<span class="cbp-fwcurrent"></span>':"<span></span>";l+=f}var j=c('<div class="cbp-fwdots"/>').append(l).appendTo(this.$el);this.$navDots=j.children("span")}},_initEvents:function(){var f=this;if(this.itemsCount>1){this.$navPrev.on("click.cbpFWSlider",c.proxy(this._navigate,this,"previous"));this.$navNext.on("click.cbpFWSlider",c.proxy(this._navigate,this,"next"));this.$navDots.on("click.cbpFWSlider",function(){f._jump(c(this).index())})}},_navigate:function(f){if(this.isAnimating){return false}this.isAnimating=true;this.old=this.current;if(f==="next"&&this.current<this.itemsCount-1){++this.current}else{if(f==="previous"&&this.current>0){--this.current}}this._slide()},_slide:function(){this._toggleNavControls();var g=-1*this.current*100/this.itemsCount;if(this.support){this.$list.css("transform",this.support3d?"translate3d("+g+"%,0,0)":"translate("+g+"%)")}else{this.$list.css("margin-left",-1*this.current*100+"%")}var f=c.proxy(function(){this.isAnimating=false},this);if(this.support){this.$list.on(this.transEndEventName,c.proxy(f,this))}else{f.call()}},_toggleNavControls:function(){switch(this.current){case 0:this.$navNext.show();this.$navPrev.hide();break;case this.itemsCount-1:this.$navNext.hide();this.$navPrev.show();break;default:this.$navNext.show();this.$navPrev.show();break}this.$navDots.eq(this.old).removeClass("cbp-fwcurrent").end().eq(this.current).addClass("cbp-fwcurrent")},_jump:function(f){if(f===this.current||this.isAnimating){return false}this.isAnimating=true;this.old=this.current;this.current=f;this._slide()},destroy:function(){if(this.itemsCount>1){this.$navPrev.parent().remove();this.$navDots.parent().remove()}this.$list.css("width","auto");if(this.support){this.$list.css("transition","none")}this.$items.css("width","auto")}};var a=function(f){if(b.console){b.console.error(f)}};c.fn.cbpFWSlider=function(g){if(typeof g==="string"){var f=Array.prototype.slice.call(arguments,1);this.each(function(){var h=c.data(this,"cbpFWSlider");if(!h){a("cannot call methods on cbpFWSlider prior to initialization; attempted to call method '"+g+"'");return}if(!c.isFunction(h[g])||g.charAt(0)==="_"){a("no such method '"+g+"' for cbpFWSlider instance");return}h[g].apply(h,f)})}else{this.each(function(){var h=c.data(this,"cbpFWSlider");if(h){h._init()}else{h=c.data(this,"cbpFWSlider",new c.CBPFWSlider(g,this))}})}return this}})(jQuery,window);

// Função de chamada do slider na página
$(document).ready(function() {
	$(function() {
		$( '#slider' ).cbpFWSlider();
	});

	$.fn.extend({
		// Define the threeBarToggle function by extending the jQuery object
		threeBarToggle: function(options){
			// Set the default options
			var defaults = {
				speed: 400,
				animate: true
			}
			var options = $.extend(defaults, options);

			return this.each(function(){
				$(this).empty().css({'background': '#911a1c'});
				$(this).addClass('tb-menu-toggle');
				$(this).prepend('<i></i><i></i><i></i>').on('click', function(event) {
					event.preventDefault();
					$(this).toggleClass('tb-active-toggle');
					if (options.animate) { $(this).toggleClass('tb-animate-toggle'); }
					$('.tb-mobile-menu').slideToggle(options.speed);
				});
				$(this).children().css('background', options.color);
			});
		},
		// Define the accordionMenu() function that adds the sliding functionality
		accordionMenu: function(options){
			// Set the default options
			var defaults = {
				speed: 400
			}
			var options =  $.extend(defaults, options);
			return this.each(function(){
				$(this).addClass('tb-mobile-menu');
				var menuItems = $(this).children('li');
				menuItems.find('.sub-menu').parent().addClass('tb-parent');
				$('.tb-parent ul').hide();
			});
		}
	});

	// Convert any element into a three bar toggle
	// Optional arguments are 'speed' (number in ms, 'slow' or 'fast') and 'animation' (true or false) to disable the animation on the toggle
	$('#menu-toggle').threeBarToggle({
		color: '#fff'
	});

	// Make any nested ul-based menu mobile
	// Optional arguments are 'speed' and 'accordion' (true or false) to disable the behavior of closing other sub
	$('#menu').accordionMenu();

	$('#marca-um').click(function () {
		$('#marca-dois, #marca-tres, #marca-quatro').css("opacity", "0.5");
		$('#marca-um').css("opacity", "1");
		$('#depo-dois, #depo-tres, #depo-quatro').fadeOut();
		$('#depo-um').delay(1000).fadeIn();
	});

	$('#marca-dois').click(function () {
		$('#marca-um, #marca-tres, #marca-quatro').css("opacity", "0.5");
		$('#marca-dois').css("opacity", "1");
		$('#depo-um, #depo-tres, #depo-quatro').fadeOut();
		$('#depo-dois').delay(1000).fadeIn();
	});

	$('#marca-tres').click(function () {
		$('#marca-dois, #marca-um, #marca-quatro').css("opacity", "0.5");
		$('#marca-tres').css("opacity", "1");
		$('#depo-um, #depo-dois, #depo-quatro').fadeOut();
		$('#depo-tres').delay(1000).fadeIn();
	});

	$('#marca-quatro').click(function () {
		$('#marca-dois, #marca-tres, #marca-um').css("opacity", "0.5");
		$('#marca-quatro').css("opacity", "1");
		$('#depo-um, #depo-dois, #depo-tres').fadeOut();
		$('#depo-quatro').delay(1000).fadeIn();
	});

	function scrollToAnchor(aid){ 
		var aTag = $("a[name='"+ aid +"']");
		$('html,body').animate({scrollTop: aTag.offset().top},'slow');
	};

	$("#anchor").click(function() {
		scrollToAnchor('top');
	});

	$("#contato").click(function() {
		if (window.location.pathname.includes('produtos') || window.location.pathname.includes('fastpack')){
			scrollToAnchor('contato-produtos');
		} else if (window.location.pathname.includes('contato')) {
			scrollToAnchor('contato');
		}
	});

	$('body').on('click','#fechar',function() {
		$('.overlay').removeClass('ativo');
	});

	//mostrar barra navbar
	var navbarHash = window.location.pathname;
	if (navbarHash.includes('empresa')){
		$('#nav-empresa').addClass('ativo');
	} else if (navbarHash.includes('produtos')){
		$('#nav-produtos').addClass('ativo');
	} else if (navbarHash.includes('noticias')){
		$('#nav-noticias').addClass('ativo');
	} else if (navbarHash.includes('contato')){
		$('#nav-contato').addClass('ativo');
	} else if (navbarHash.includes('cliente')){
		$('#nav-clientes').addClass('ativo');
	} else if (navbarHash.includes('comparativo')){
		$('#nav-comparativo').addClass('ativo');
	} else if (navbarHash.includes('politica-de-privacidade')){

	}else {
		$('#nav-home').addClass('ativo');
	}

	// Ativa e desativa video
	$("header .videos .open-video").click(function(event) {
        return $("header .videos video").get(0).pause(),
        $("#video").addClass("ativo"),
        setTimeout(function() {
            $("#video div video").get(0).play()
        }, 1e3),
        !1
    }),
    $("#video").not("video").click(function() {
        $("#video").removeClass("ativo"),
        $("#video div video").get(0).pause(),
        $("header .videos video").get(0).play()
    });


    // ----- Scripts Area de Clientes ------ //
    $('#AreaCliente .menu-mob a').on('click', function(){
    	$(this).toggleClass('ativo');
    	$('aside').toggleClass('ativo');
    });

    $('#AreaCliente aside.ativo a, .content').on('click', function(){
    	$('#AreaCliente aside.ativo').removeClass('ativo');
    	$('#AreaCliente .menu-mob a.ativo').removeClass('ativo');
    });

});

