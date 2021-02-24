$(init);

function init()
{
	$.each($('.select2'), function(){
		
		var content = $(this);
		var tags = content.data('tags');
		
		console.log(tags);
		
		content.select2({
			tags: tags,
		});
		
	});
	
}
