var keyboardSlider;

$( function (){
	
	$('.options > a.option').bind('click', function(){

		if ( $('.option.active').length == 0 ) {
		
			var time = $('.time > span').attr('data-time');
			
			var pontos = 10;
			var html = '<span class="text-ponto-3" style="margin-top: 76px;"><span style="font-weight: 900;">YES!!!</span><br>VOCÊ GANHOU<br>10 PONTOS</span>';
			var classe = 'pt3';
			
			if ( time <= 30 && time > 15 ) {
				
				pontos = 7
				html = '<span class="text-ponto-2" style="margin-top: 76px;"><span style="font-weight: 900;">YES!!</span><br>VOCÊ GANHOU<br>7 PONTOS</span>';
				classe = 'pt2';
				
			} else if ( time <= 15 ) {
				
				pontos = 5;
				html = '<span class="text-ponto-1" style="margin-top: 95px;">VOCÊ GANHOU <br>5 PONTOS</span>';
				classe = 'pt1';
				
			}
			
			$(this).removeClass().addClass('option').html(html).addClass(classe);
			
			$('.options > a').removeClass('active');
			$(this).addClass('active');
			
			var question = $(this).parents('.options').data('question');
			var name = $(this).data('name');
	
			$('.result').attr('name', 'p'+question);
			$('.result').attr('value', '{"name":"'+name+'", "tempo":"'+tempo+'", "value":"'+pontos+'"}');
			
			$('.time > span').addClass('active');
			
			$('.option').each(function(){
				
				if ( !$(this).hasClass('active') ){
					
					$(this).addClass('disabled');
					
				}
				
			});
			
			setTimeout(function(){
				
				document.getElementById("form-submit").submit();
			
			}, 2500);
		
		}

	});
	
	
	
});



var tempoFinal = 45;
var tempo = tempoFinal;
var tempoM = 0;

function countDown(){
	
	if ( tempo == 41 ) {
		$('.time .time-bar span').addClass('animated zoomIn').fadeIn(0);
	}
	
	if ( tempo == 30 ) {
		$('.time .time-bar span').html('+7').removeClass().addClass('p2');
	} else if ( tempo == 15 ) {
		$('.time .time-bar span').html('+5').removeClass().addClass('p3');
	}
	
	var porcentagemGeral = tempoM / tempoFinal * 100;
	
	$('.time .time-bar:nth-child(2)').css('width', porcentagemGeral+'%');
	$('.time .time-bar:nth-child(3)').css('width', porcentagemGeral+'%');
	$('.time .time-bar:nth-child(4)').css('width', porcentagemGeral+'%');

	if ( porcentagemGeral > 7 ) {
		
		$('.time .time-bar:nth-child(2)').find('span').fadeIn(500);
		
	}
	
	if ( porcentagemGeral > 38 ) {
		
		$('.time .time-bar:nth-child(3)').find('span').fadeIn(500);
		
	}
	
	if ( porcentagemGeral > 73) {
		
		$('.time .time-bar:nth-child(4)').find('span').fadeIn(500);
		
	}
	
	if ( $('.time > span.active').length == 0 ) {
		
		setTimeout( function (){
			
			if ( tempo != 0 ){
				
				tempo--;
				tempoM++;
				countDown();
				
				if ( tempo < 10 ) {
					
					tempo = '0'+tempo;
					
				}
				
				$('.time > span').html('00:'+tempo).attr('data-time', tempo);
			
			}
			
		}, 1000);
	
	}
	
}
countDown();