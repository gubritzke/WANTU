$( function (){
	
	window.addEventListener('scroll', monitorU);
	
	$('.carousel-1').owlCarousel({
		loop:true,
		nav:false,
		autoplay:false,
		autoplayTimeout:7000,
		responsive:{
			0:{
				items:1
			},
			1800:{
				items:1
			},
		},
		onChanged: callbackCarousel
	});
	
});

function callbackCarousel(){
	
	setTimeout( function (){
		
		var aba = $('.vc-conteudo .owl-item.active > div').attr('data-aba');
		aba = parseFloat(aba);
		
		if ( aba ) {
			
			$('ul.menu li a').removeClass('active');
			$('ul.menu li a.test-'+aba).addClass('active');
		
		}
		
		console.log( aba );
	
	}, 100);
	
}

function monitorU(){
	
	var inicio = $('header').height() + $('#banner').height() - 120;
	var scrollPos = window.scrollY - inicio;
	
	console.log( scrollPos );
	
	if ( scrollPos <= '-18' ) {
		$('.cf-1 > a .fundo').attr('style','transform: translateY('+scrollPos+'px)');
	}
	
}

function chamaAncoraPorAltura($container){
	$('html, body').animate({
        scrollTop: $container.offset().top -200
    }, 1000);
}