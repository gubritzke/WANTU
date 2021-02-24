//onload
$( function(){
	
	//executa ao clicar em add
	$(".repeatAdd").click( function(){
		repeatAdd(this);
	});
	
	//executa ao clicar em del
	$(document).on("click", ".repeatDel", function(){
		repeatDel(this);
	});
	
});

function repeatAdd(element)
{
	//recupera o elemento do grupo
	var grp = $($(element).data("repeat"));

	//quantidade de itens existentes
	var count = grp.find(".repeatItem").length;
	
	//recupera o máximo de itens permitidos
	var max = grp.data("max");

	//seleciona o último item e clona
	var item = grp.find(".repeatItem:last").clone();
	item.find("input").val("");
	
	//adiciona o item
	if( count < max ) grp.append(item);
}

function repeatDel(element)
{
	//recupera o elemento do grupo
	var grp = $($(element).parents(".repeatGrp"));
	
	//quantidade de itens existentes
	var count = grp.find(".repeatItem").length;
	
	//recupera o mínimo de itens permitidos
	var min = grp.data("min");
	
	//seleciona o item para remover
	var item = $(element).parents(".repeatItem");
	
	//remove o item
	if( count > min ) item.remove();
}