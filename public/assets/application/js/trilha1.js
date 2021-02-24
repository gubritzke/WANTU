$( function (){
	
	if( typeof jQuery.fn.mask === 'function' )
	{
		
		//mask padrao
		$('input[name="telefone"],input[name="telefone_residencial"]').mask("(99) 99999999");
		$('input[name="celular"],input[name="telefone_celular"]').mask("(99) 999999999");
		$('input[name="cpf"]').mask("999.999.999-99");
		$('input[name="cnpj"]').mask("99.999.999/9999-99");
		$('input[name="data_de_nascimento"],input[name="data_emissao"],input[name="data_emissao"],input[name="previsao_de_formatura"],input[name="data_de_inicio_empresarial"],input[name="data_de_termino_empresarial"]').mask("99/99/9999");
		$('input[name="hora"]').mask("99:99");
		
		//endereço busca cep
		$('input[name="cep"]').mask("99999-999");
		$('input[name="cep"]').blur(function(){
	        var cep = $(this).val().replace(/[^0-9]/, '');
	        if(cep !== '')
	        {
	        	var url = '//viacep.com.br/ws/'+cep+'/json';
	            $.getJSON(url, function(json){
	                $('input[name="endereco"]').val(json.logradouro);
	                $('input[name="bairro"]').val(json.bairro);
	                $('input[name="cidade"]').val(json.localidade);
	                $('select[name="estado"]').val(json.uf);
	            });
	        }
	    });
		
		
	}

});

function printCurriculo() {
    window.print();
}