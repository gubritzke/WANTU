$( function (){
	
	if( typeof jQuery.fn.mask === 'function' )
	{
		
		//mask padrao
		$('input[name="telefone"],input[name="telefone_residencial"]').mask("(99) 99999999");
		$('input[name="celular"],input[name="telefone_celular"]').mask("(99) 999999999");
		$('input[name="cpf"]').mask("999.999.999-99");
		$('input[name="cnpj"]').mask("99.999.999/9999-99");
		$('input[name="data_inicial"],input[name="data_final"],input[name="data_nascimento"]').mask("99/99/9999");
		$('input[name="hora"]').mask("99:99");
		
	}

});

