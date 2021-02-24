$(function(){
	
	$(document).on('submit','form[action="/contato/send"]',function(){
		
		$(this).find('button').remove();
		$(this).append('<button data-ripple="rgba(0,0,0,0.4)" type="button" style="position: relative;"><i class="fa fa-cog fa-spin"></i> Carregando</button>')
		
	});
	
	// MASK
	$('input[name="telefone"],input[name="celular"]').mask("(99) 9999-9999?9");
	
	// BT CONTRATE
	$('.bt-contrate').bind('click', function(){
		
		$('form.form-contrate').toggleClass('active');
		
	});
	
	// MENU
	$('header .top-middle .right > ul > li, .close-menu').bind('click', function(){
		
		$('.main-menu, body').toggleClass('menu-opened');
		$('.main-menu li').toggleClass('animated fadeInRight');
		
	});
	
	$('.main-menu').bind('click', function(event){
		
		event.stopPropagation();
		
	});
	
	$( window ).scroll( function(){ 
		
		var scrolled = $(window).scrollTop();
		var header = $('header').height();
		
		if ( scrolled > header + 300 ) {
			
			$('header').addClass('active');
			$('section').css('marginTop', header+'px');
			
		} else {
			
			$('header').removeClass('active');
			$('section').css('marginTop', '0px');
			
		}
		
	});

	// MAD-RIPPLE // (jQ+CSS)
	$(document).on("mousedown", "[data-ripple]", function(e) {
	    
		var $self = $(this);
	    
		if($self.is(".btn-disabled")) {
			return;
		}
		
		if($self.closest("[data-ripple]")) {
			e.stopPropagation();
		}
	    
		var initPos = $self.css("position"),
			offs = $self.offset(),
			x = e.pageX - offs.left,
			y = e.pageY - offs.top,
			dia = Math.min(this.offsetHeight, this.offsetWidth, 100), // start diameter
			$ripple = $('<div/>', {class:"ripple", appendTo:$self});
	    
	    if(!initPos || initPos==="static") {
	      $self.css({position:"relative"});
	    }
	    
	    $('<div/>', {
	    	class : "rippleWave",
	    	css : {
	    		background: $self.data("ripple"),
	    		width: dia,
	    		height: dia,
	    		left: x - (dia/2),
	    		top: y - (dia/2),
	    	},
	    	appendTo : $ripple,
	    	one : {
	    		animationend : function(){
	    			$ripple.remove();
	    		}
	    	}
	    });
	});

});