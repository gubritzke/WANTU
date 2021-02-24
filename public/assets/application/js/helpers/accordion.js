function accordionInit()
{
	var loop = $(".boxAccordionHide");
	
	$.each(loop, function(i, e){
		var target = $(e).find(".boxAccordionContent");
		target.hide();
	});
}

function accordionClick(button)
{
	var grp = button.parents(".boxGrp");
	var target = grp.find(".boxAccordionContent");
	
	target.slideToggle();
	grp.toggleClass("boxAccordionHide");
	
}

$( function(){
	
	//função ao clicar
	$(document).on('click', '.boxAccordionBt', function(){
		accordionClick($(this));
	});
	
	//init
	accordionInit();
	
});