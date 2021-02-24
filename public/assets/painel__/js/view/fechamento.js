function publicidade_total()
{
	var target = $(".publicidade_total");
	var inputs = $(".publicidade_item");
	
	var total = 0;
	inputs.each( function(){
		value = $(this).val().replace(".","").replace(",",".");
		total += Number(value);
	});
	
	target.val(numberToReal(total)).trigger("change");
}

function roi_vendas()
{
	var target = $(".roi_receita");
	var publicidade_total = $(".publicidade_total").val().replace(".","").replace(",",".");
	var vendas = $(".vendas").val().replace(".","").replace(",",".");
	
	value = (vendas / publicidade_total);
	target.val(numberToReal(value));
}

function roi_comissao()
{
	var target = $(".roi_comissao");
	var publicidade_total = $(".publicidade_total").val().replace(".","").replace(",",".");
	var comissao = $(".comissao").val().replace(".","").replace(",",".");
	
	value = (comissao / publicidade_total);
	target.val(numberToReal(value));
}

function receita_liquida()
{
	var target = $(".receita_liquida");
	var publicidade_total = $(".publicidade_total").val().replace(".","").replace(",",".");
	var comissao = $(".comissao").val().replace(".","").replace(",",".");
	
	value = (comissao - publicidade_total);
	target.val(numberToReal(value));
}

function numberToReal(numero) 
{
    var numero = numero.toFixed(2).split('.');
    numero[0] = numero[0].split(/(?=(?:...)*$)/).join('.');
    return numero.join(',');
}

$( function(){
	
	//calcular total
	$(document).on("change", ".publicidade_item", function(){
		publicidade_total();
	});
	
	//calcular ROI Vendas
	$(document).on("change", ".vendas, .publicidade_total", function(){
		roi_vendas();
	});
	
	//calcular ROI Comissão
	$(document).on("change", ".comissao, .publicidade_total", function(){
		roi_comissao();
	});
	
	//calcular receita líquida
	$(document).on("change", ".comissao, .publicidade_total", function(){
		receita_liquida();
	});
	
});