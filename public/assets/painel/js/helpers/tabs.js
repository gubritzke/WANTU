//onload
$( function(){
	
	//onclick
	$(".tabBt a").click(tabClick);
	
	//start
	init();
	
});

function init()
{
	//oculta todos os itens
	$(".tabItem").hide();
	
	//exibe o que possui class active
	$(".tabBt .active").each( function(i, e){
		tab = $(e).data('tab');
		tabShow(tab);
	});
}

//funcao para exibir a tab
function tabShow(tab)
{
	$("#" + tab).show();
}

//funcao ao clicar no bt
function tabClick()
{
	//oculta as tabs
	$(this).parents(".tabGrp").find(".tabItem").hide();
	
	//altera o bt ativo
	$(this).parents(".tabGrp").find(".tabBt a").removeClass("active");
	$(this).addClass("active");
	
	//exibe a tab clicada
	tab = $(this).data('tab');
	tabShow(tab);
}