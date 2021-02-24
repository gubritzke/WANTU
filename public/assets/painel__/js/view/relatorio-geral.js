function ajaxRender(target)
{
	//define as vars
	var method = target.data("ajax-render");
	var form = target.find(".ajax-form");
	
	//add loading
	var loading = $("<i class='fa fa-spinner fa-pulse loading'></i>");
	target.html(loading);
	
	//data
	var data = {};
	if( form.length > 0 )
	{
		$.each(form.serializeArray(), function(i, e){
			data[e.name] = e.value;
		});
	}
	
	//method
	data['method'] = method;
	console.log(data);
	
	//ajax
	$.ajax({
		'method': 'POST',
		'data': data,
		'dataType': "JSON",
		'success': function($requestdata){
			
			//insere o código HTML
			target.html($requestdata.html);
			
			//init plugins
			initCalendarRange();
			initPerfectScroll();
		},
	});
}

$( function(){
	
	//loop para renderizar o conteúdo em ajax
	$('.ajax-render').each( function(){
		
		var target = $(this);
		ajaxRender(target);
		
	});
	
	//enviar formulário
	$(document).on("submit", ".ajax-form", function(){
		
		var target = $(this).parents('.ajax-render');
		ajaxRender(target);
		return false;
		
	});
	
});