/**
 * show successs message
 * @param str
 */
function message(str)
{
	message(str, 'success');
}

/**
 * show message
 * @param str
 * @param type
 */
function message(str, type)
{
	alert(str);
}

$( function (){
	
	//funcao popup
	$(document).on('click', '.popup, .box-popup .close', function(){
	
		var bgPopup = $('.bg-popup');
		var boxPopup = bgPopup.find('.box-popup');
		var html = $( this ).attr('data-load');
		var htmlText = $( this ).attr('data-text');
		var efeito = $( this ).attr('data-efeito');
	
		if ( bgPopup.hasClass('active') ) {
			
			bgPopup.removeClass('fadeIn');
			bgPopup.addClass('fadeOut');
			boxPopup.removeClass('fadeIn').addClass('fadeOut');
	
			setTimeout( function (){
	
				bgPopup.removeClass('active');
	
			}, 900);
	
		} else {
			
			//remove attr style do popup
			boxPopup.removeAttr('style');
			
			//abre o popup
			bgPopup.addClass('active');
			bgPopup.removeClass('fadeOut');
			bgPopup.addClass('fadeIn');
			boxPopup.removeClass('fadeOut').addClass('fadeIn');
			
			if ( htmlText ) {
				
				boxPopup.find('.ajax-load').html( htmlText );
				
				//reajusta a altura
    			var boxPopupHeigth = boxPopup.height();
        		var boxPopupMarginTop = boxPopupHeigth/2 + 0;
        		boxPopup.attr('style','margin-top:-'+boxPopupMarginTop+'px');
				
			} else {
				
				boxPopup.find('.ajax-load').html('<p style="text-transform:uppercase; text-align:center; font-size:25px;"><i class="fa fa-spin fa-spinner"></i></p>');
				
				//carrega o conteudo do popup
				$.ajax({
		
					url: html,
					success: function( data ) {
		
						//preenche o popup
						boxPopup.find('.ajax-load').html( data );
						
		    			//reajusta a altura
		    			var boxPopupHeigth = boxPopup.height();
		        		var boxPopupMarginTop = boxPopupHeigth/2 + 0;
		        		boxPopup.attr('style','margin-top:-'+boxPopupMarginTop+'px');
		
					}, error: function (){
		
						boxPopup.find('.ajax-load').html('<p style="text-transform:uppercase; text-align:center; font-size:25px;">Erro ao carregar...</p>');
		
					}
		
				});
				
			}
	
		}
		
		
	});
	
});

$( function (){
$('.open-menu').bind('click', function(){
	
	if ( !$(this).hasClass('active') ) {
	
		var i = 0;
		$('header nav > ul > li > ul > li > a').each( function (){
			
			var menu = $(this);
			menu.removeClass('fadeOutRight');
			
			setTimeout(  function(){
				
				menu.fadeOut(0).fadeIn(0).addClass('active').addClass('fadeInRight');
				
			}, i);
			
			i = i + 100;
			
		});
		
		$(this).addClass('active');
		$('header nav > ul > li > ul').slideDown('500');
	
	} else {
		
		var total = $('header nav > ul > li > ul > li > a').length;
		
		console.log( total );
		
		var i = 0;
		var ii = 0;
		$('header nav > ul > li > ul > li > a').each( function (){
			
			var menu = $(this);
			
			menu.removeClass('fadeInRight');
			
			setTimeout(  function(){
				
				menu.addClass('fadeOutRight');
				
				ii++;
				
				if ( ii == total ) {
					
					setTimeout( function (){
						
						$('header nav > ul > li > ul > li > a').removeClass('active');
						$('header nav > ul > li > ul').slideUp('500');
						
					}, 500);
					
				}
				
			}, i);
			
			i = i + 100;
			
		});
		
		$(this).removeClass('active');
		
	}
});
});
