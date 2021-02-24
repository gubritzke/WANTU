$( function(){
	
	$('.uploadSubmit').submit(function(event){
		uploadSubmit(event, this);
	});

	$(document).on("click", ".uploadClick", function(){
		uploadClick(this);
	});
	
	$(document).on("click", ".uploadDelete", function(){
		uploadDelete(this);
	});
	
	uploadInit();
});

/**
 * grupo de upload
 */
var uploadGrp = null;

/**
 * adiciona códigos para fazer o upload funcionar corretamente
 * <a href="javascript:;" class="uploadDelete"><i class="fa fa-times color-red"></i> Remover imagem</a>
 * <span class="uploadLoader"><i class="fa fa-spinner fa-pulse"></i> Carregando...</span>
 */
function uploadInit()
{
	var del = '<a href="javascript:;" class="uploadDelete"><i class="fa fa-times color-red"></i> Remover imagem</a>';
	var loader = '<span class="uploadLoader"><i class="fa fa-spinner fa-pulse"></i> Carregando...</span>';
	
	$.each($('.uploadGrp'), function(){
		
		uploadGrp = $(this);
		
		uploadGrp.append(del).append(loader);
		uploadGrp.find(".uploadLoader").hide();
		
		if( uploadGrp.find("input").val() == "" )
		{
			uploadGrp.find(".uploadDelete").hide();
		}
		
	});
}

/**
 * ação do clique no botão
 * @param button
 */
function uploadClick(button)
{
	//simula o clique no upload
	var target = $(button).data("target");
	$(target).trigger("click");
	
	//grupo ativo
	uploadGrp = $(button).parents('.uploadGrp');
}


/**
 * ação do clique no botão de deletar
 * @param button
 */
function uploadDelete(button)
{
	//grupo ativo
	uploadGrp = $(button).parents('.uploadGrp');
	
	//alterações
	uploadGrp.find('.image').css('background-image', '');
	uploadGrp.find('input').val('');
	uploadGrp.find('.uploadDelete').hide();
}

/**
 * enviando o form em ajax
 */
function uploadSubmit(event, element)
{
	//disable the default form submission
	event.preventDefault();
	
	//loading
	uploadGrp.find(".uploadLoader").show();
	
	//grab all form data  
	var formData = new FormData($(element)[0]);
	
	$.ajax({
		type: 'POST',
		data: formData,
		async: true,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function($returndata)
		{
			if( $returndata.error == true )
			{
				message($returndata.message, 'error');
				
			} else {
				
				addContent($returndata.filename);
				
			}
			
			$("#file").val("");
			uploadGrp.find(".uploadLoader").hide();
		}
	});
	
	return false;
}

/**
 * alterar o conteúdo
 * @param filename
 */
function addContent(filename)
{
	var css = 'url("' + filename + '")';
	uploadGrp.find('.image').css('background-image', css);
	uploadGrp.find('input').val(filename);
	uploadGrp.find('.uploadDelete').show();
}