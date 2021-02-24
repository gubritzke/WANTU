/**
 * grupo de upload
 */
var uploadGrp;

/**
 * adiciona códigos para fazer o upload funcionar corretamente
 * <a href="javascript:;" class="uploadDownload color-blue" style="margin-right:15px;"><i class="fa fa-download"></i> Download</a>
 * <a href="javascript:;" class="uploadDelete color-red"><i class="fa fa-times"></i> Remover imagem</a>
 * <span class="uploadLoader"><i class="fa fa-spinner fa-pulse"></i> Carregando...</span>
 */
function uploadInit()
{
	var download = '<a href="javascript:;" class="uploadDownload color-blue" style="margin-right:15px;"><i class="fa fa-download"></i> Download</a>';
	var del = '<a href="javascript:;" class="uploadDelete color-red"><i class="fa fa-times"></i> Remover</a>';
	var loader = '<span class="uploadLoader"><i class="fa fa-spinner fa-pulse"></i> Carregando...</span>';
	
	$.each($('.uploadGrp'), function(){
		
		uploadGrp = $(this);
		
		uploadGrp.append(download).append(del).append(loader);
		uploadGrp.find(".uploadLoader").hide();
		
		if( uploadGrp.find("input").val() == "" )
		{
			uploadGrp.find(".uploadDelete").hide();
			uploadGrp.find(".uploadDownload").hide();
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
	if( confirm('Deseja realmente remover?') )
	{
		//grupo ativo
		uploadGrp = $(button).parents('.uploadGrp');
		
		//alterações
		uploadGrp.find('.image').css('background-image', '');
		uploadGrp.find('input').val('');
		uploadGrp.find('.uploadDelete').hide();
		uploadGrp.find('.uploadDownload').hide();
	}
}

/**
 * ação do clique no botão de download
 * @param button
 */
function uploadDownload(button)
{
	//grupo ativo
	uploadGrp = $(button).parents('.uploadGrp');
	
	//url da imagem
	var url = uploadGrp.find('.image').css('background-image');
	url = url.replace('url(','').replace(')','').replace(/\"/gi, "");
	
	//abre a imagem
    window.open(url, '_blank');
}

/**
 * enviando o form em ajax
 */
function uploadSubmit(event, element)
{
	//disable the default form submission
	event.preventDefault();
	
	//loading
	uploadGrp.find('.uploadDelete').hide();
	uploadGrp.find('.uploadDownload').hide();
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
			uploadGrp.find('.uploadDelete').show();
			uploadGrp.find('.uploadDownload').show();
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
	uploadGrp.find('.uploadDownload').show();
}

/**
 * eventos
 */
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
	
	$(document).on("click", ".uploadDownload", function(){
		uploadDownload(this);
	});
	
	uploadInit();
});