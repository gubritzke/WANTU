function repeatAdd(element)
{
	//definir grupo
	var grp = $(element).parents(".repeatGrp");

	//quantidade de itens existentes
	var count = grp.find(".repeatItem").length;
	
	//recupera o máximo de itens permitidos
	var max = grp.data("max");
	if( max == undefined ) max = 10;
	
	//seleciona o último item e clona
	var item = grp.find(".repeatItem:last").clone();
	item.find("input").val("");
	
	//adiciona o item
	if( count < max ) grp.append(item);
}

function repeatDel(element)
{
	//definir grupo
	var grp = $(element).parents(".repeatGrp");
	
	//quantidade de itens existentes
	var count = grp.find(".repeatItem").length;
	
	//recupera o mínimo de itens permitidos
	var min = grp.data("min");
	if( min == undefined ) min = 1;
	
	//seleciona o item para remover
	var item = $(element).parents(".repeatItem");
	
	//remove o item
	if( count > min ) item.remove();
}

function repeatCreateButtonAdd(element)
{
	var html = $("<th><a href='javascript:;' class='repeatAdd'><i class='fa fa-plus color-green'></i></a></th>");
	var target = $(element).find("thead tr");
	target.append(html);
}

function repeatCreateButtonDel(element)
{
	var html = $("<td align='center'><a href='javascript:;' class='repeatDel'><i class='fa fa-minus color-red'></i></a></td>");
	var target = $(element).find("tbody tr");
	target.append(html);
}

$( function(){
	
	//criar botão de add
	$.each($(".repeatGrp"), function(i, e){
		repeatCreateButtonAdd(e);
		repeatCreateButtonDel(e);
	});
	
	//executar ao clicar em add
	$(document).on("click", ".repeatAdd", function(){
		repeatAdd(this);
	});
	
	//executar ao clicar em del
	$(document).on("click", ".repeatDel", function(){
		repeatDel(this);
	});
	
});