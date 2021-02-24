$( function(){		//função ao alterar	$(document).on('change', '.select2', function(){		tagInit($(this));	});		//função ao remover	$(document).on('click', '.label .tag', function(){		tagDel($(this));	});		//função para calcular o disconto	$("input[name='preco_de'], input[name='preco']").change(priceCalcDiscount);		//init	priceCalcDiscount();	});/** * start */function tagInit(content){	//vars	var grp = content.parents(".label");	var text = content.find("option:selected").text().trim();	var name = content.data("name");	var value = content.val();	var allow_multiple = content.data("allow_multiple");		//verifica se o valor é vazio	if( value == '' ) return false;	//remove a tag caso não seja permitido mais de uma	if( !allow_multiple ) grp.find('.tag').remove();		//adiciona a tag	tagAdd(grp, text, name, value);		//define o nome do produto	tagNameCreate();		//limpa o select	content.val("").trigger("change");}/**  * adiciona tag em um grupo * @return example * <span class="tag tag-green tag-orange tag-blue" data-text="$text"> *   $text <input type="hidden" name="$name" value="$value" /> * </span>*/function tagAdd(grp, text, name, value){	//cria o html	var tipo = tagType(grp, value);	var span = $("<span>").addClass("tag " + tipo).data("text", text).text(text);	var input = $("<input>").attr("type", "hidden").attr("name", name).val(value);	var html = span.append(input);		//adiciona	grp.append(html);	}/** * remove a tag */function tagDel(tag){	//remove	tag.remove();		//define o nome do produto	tagNameCreate();}/** * define o tipo da tag */function tagType(grp, value){	//vars	var isInteger = (Math.floor(value) == value) && $.isNumeric(value);	var notIsFirst = (grp.find('.tag').length > 0);	var tipo = '';		//tag nova	if( !isInteger )	{		tipo = "tag-green";		//tag secundaria	} else if( notIsFirst ) {		tipo = "tag-blue";		//tag principal	} else {		tipo = "tag-orange";	}		return tipo;}/** * reorganiza os tipos das tags */function tagArrangeType(label){	var tags = label.find(".tag");		$.each(tags, function(i, e){				if( (i == 0) && $(e).hasClass("tag-blue") )		{			$(e).removeClass("tag-blue");			$(e).addClass("tag-orange");		}				if( (i != 0) && $(e).hasClass("tag-orange") )		{			$(e).removeClass("tag-orange");			$(e).addClass("tag-blue");		}			});}/** * define o nome do produto */function tagNameCreate(){	var content = $("#createName");	var input = content.find("input[name='produto']");	var grps = content.find(".label");	var name = [];		//loop nas tags	$.each(grps, function(){		var label = $(this);				//tags		tagArrangeType(label);				//name		var text = label.find(".tag").eq(0).data("text");		if( text != undefined ) name.push(text);			});		//insere o nome criado	name = name.join(" ");	input.val(name);		//verificar se o produto existe	productMatched();}/** * calcular o desconto entre os preços */function priceCalcDiscount(){	var price1 = $("input[name='preco_de']").val().replace('.', '').replace(',', '.');	var price2 = $("input[name='preco']").val().replace('.', '').replace(',', '.');		var discount = (100 - (price2 * 100 / price1));	if( $.isNumeric(discount) )	{		$("input[name='desconto']").val(discount + " %");	}}/** * busca os produtos para combinar */function productMatched(){	//validar	if( $("input[name='id_produto']").length > 0 ) return false;		//params	var filtros = $(".tag input").map(function(){return $(this).val();}).get();	var data = {"method": "matched", "filtros": filtros};	var target = $("#match");		$.ajax({		"data": data,		"method": "POST",		"dataType": "JSON",		success: function($requestdata){						console.log($requestdata);			target.find(".match-confirm").remove();						if( $requestdata.error == undefined )			{				target.prepend($requestdata.html);			}					}	});}