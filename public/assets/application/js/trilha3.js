var keyboardSlider;
$( function (){
	$('.options > a.option').bind('click', function(){

		$('.options > a').removeClass('active');
		$(this).addClass('active');
		
		var question = $(this).parents('.options').data('question');
		var name = $(this).data('name');
		var value = $(this).data('value');


		$('.result').attr('name', 'p'+question);
		$('.result').attr('value', '{"name":"'+name+'", "value":"'+value+'"}');

	});
	
	if ( $('#ranger').length > 0 ) {
	
		keyboardSlider = document.getElementById('ranger');
	
		noUiSlider.create(keyboardSlider, {
			start: 3.04,
			step: 0,
			connect: [true, false],
			range: {
				'min': 0,
				'max': 6
			}
		});
		
		keyboardSlider.noUiSlider.on('change', function ( values, handle ) {
			
			var value = 3;
			
			if ( values[handle] > 0 && values[handle] < 1.28 ) {
			
				keyboardSlider.noUiSlider.set( 0.62 );
				value = 1;
				
			} else if ( values[handle] > 1.28 && values[handle] < 2.50 ) {
				
				keyboardSlider.noUiSlider.set( 1.88 );
				value = 2;
				
			} else if ( values[handle] > 2.50 && values[handle] < 3.63 ) {
				
				keyboardSlider.noUiSlider.set( 3.04 );
				value = 3;
				
			} else if ( values[handle] > 3.63 && values[handle] < 4.75 ) {
				
				keyboardSlider.noUiSlider.set( 4.25 );
				value = 4;
				
			}  else if ( values[handle] > 4.75 ) {
				
				keyboardSlider.noUiSlider.set( 5.33 );
				value = 5;
				
			}
			
			$('.result').val( value );
			
		});
		
		keyboardSlider.noUiSlider.on('update', function( values, handle, unencoded, tap, positions ){
			
			$('.noUi-base').removeAttr('style');
			
			var cor = '';
			
			if ( values[handle] > 0 && values[handle] < 1.28 ) {
			
				cor = '#ffa398';
				
			} else if ( values[handle] > 1.28 && values[handle] < 2.50 ) {
				
				cor = '#ffc48c';
				
			} else if ( values[handle] > 2.50 && values[handle] < 3.63 ) {
				
				cor = '#9ad9d2';
				
			} else if ( values[handle] > 3.63 && values[handle] < 4.75 ) {
				
				cor = '#bade95';
				
			}  else if ( values[handle] > 4.75 ) {
				
				cor = '#83dc9d';
				$('.noUi-base').attr('style','background:'+cor+'; border-radius:100px;');
				
			}
			
			var attrStyle = $('.noUi-connect').attr('style');
			$('.noUi-connect').attr('style',attrStyle+'background:'+cor+' !important;');
			$('.noUi-handle').attr('style','background:'+cor+' !important;');
			
		});
	
	}

});

function changeRanger( value ) {
	
	keyboardSlider.noUiSlider.set( value );
	
	if ( value > 0 && value < 1.28 ) {
		
		value = 1;
		
	} else if ( value > 1.28 && value < 2.50 ) {
		
		value = 2;
		
	} else if ( value > 2.50 && value < 3.63 ) {
		
		value = 3;
		
	} else if ( value > 3.63 && value < 4.75 ) {
		
		value = 4;
		
	}  else if ( value > 4.75 ) {
		
		value = 5;
		
	}
	
	$('.result').val( value );
	
}