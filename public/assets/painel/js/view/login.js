$(function(){
	
	var ultimo;
	
	atualizaForms('start');
	
	$(document).on('click','form.mainform .label', function(){
		
		$(this).find('input, select, textarea').focus();
		
	});
	
	$(document).on('focus','form.mainform input, form.mainform select, form.mainform textarea', function(){
		
		var e = $(this).parent();
		var value = e.find('input, select, textarea').val();
		ultimo = e;
		
		setTimeout(function(){
			$(e).addClass('active');
		},10);
		
	});
	
	$(document).on('blur', 'form.mainform input, form.mainform select, form.mainform textarea', function(){
		
		var e = $(this).parent();
		var value = $(this).val();
		
		if (value){
			$(e).addClass('active');
		} else {
			$(e).removeClass('active');
		}
		
	});
	
	function atualizaForms(start){
		
		$('form.mainform .label').each(function(){
			
			var value = $(this).find('input, select, textarea').val();
			if (value){
				
				$(this).addClass('active');
				
			} else {
				$(this).removeClass('active');
			}
			
		});
		
	}
	
});