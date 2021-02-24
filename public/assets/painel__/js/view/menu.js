function ajaxContarProdutos(target)
{
	//definir as variáveis
	var data = {};
	data['method'] = "ajaxContarProdutos";
	data['ids'] = target.data("ids");
	
	//ajax
	$.ajax({
		'method': 'POST',
		'data': data,
		'dataType': "JSON",
		'success': function($requestdata){
			
			//add a quantidade
			//target.append(" (" + $requestdata.quantidade + ")");
			
			//se a quantidade for ZERO
			if( $requestdata.quantidade == 0 )
			{
				target.css("color", "#eb1c24");
			}
			
		},
	});
}

$( function(){
	
	//loop nos links para contar os produtos
	$('.ajaxContarProdutos').each( function(){
		
		var target = $(this);
		ajaxContarProdutos(target);
		
	});
	
});