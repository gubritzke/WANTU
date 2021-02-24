function searchKeyup(input)
{
    var value = input.val().toLowerCase();
    var items = input.parents(".boxAccordionContent").find(".scroll").find("label");
    
    if( value.length > 0 )
    {
    	items.stop().hide();
    	
    	$.each(items, function(i, e){
    		
			if( $(e).attr('data-value').indexOf(value) != '-1' )
			{
				$(e).show();
			}
			
		});
    	
    } else {
    	items.stop().show();
    }   
	
}

$( function(){
	
	//função ao digitar
	$(document).on('keyup', '.boxSearchInput', function(){
		searchKeyup($(this));
	});
	
});